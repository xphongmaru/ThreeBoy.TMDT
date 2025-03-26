<?php

/**
 * Keap Integration
 */

namespace BitCode\FI\Actions\Keap;

use BitCode\FI\Core\Util\HttpHelper;
use BitCode\FI\Flow\FlowController;
use WP_Error;

/**
 * Provide functionality for keap integration
 */
class KeapController
{
    private $_integrationID;

    public function __construct($integrationID)
    {
        $this->_integrationID = $integrationID;
    }

    public static function refreshTagListAjaxHelper($queryParams)
    {
        // var_dump($queryParams->tokenDetails);
        // die;
        if (
            empty($queryParams->clientId)
            || empty($queryParams->clientSecret)
            || empty($queryParams->tokenDetails)
        ) {
            wp_send_json_error(
                __(
                    'Requested parameter is empty',
                    'bit-integrations'
                ),
                400
            );
        }
        $response = [];
        if ((\intval($queryParams->tokenDetails->generates_on) + (55 * 60)) < time()) {
            $response['tokenDetails'] = static::refreshAccessToken($queryParams);
        }

        $apiEndpoint = 'https://api.infusionsoft.com/crm/rest/v1/tags';
        $authorizationHeader['Content-Type'] = 'application/x-www-form-urlencoded';

        $tokenDetails = empty($response['tokenDetails']) ? $queryParams->tokenDetails : $response['tokenDetails'];

        $authorizationHeader['Authorization'] = 'Bearer ' . $tokenDetails->access_token;

        $tagListApiResponse = HttpHelper::get($apiEndpoint, null, $authorizationHeader);
        $tags = [];

        if (isset($tagListApiResponse->error)) {
            wp_send_json_error('Tags fetch failed', 400);
        } else {
            foreach ($tagListApiResponse->tags as $tag) {
                $tags[] = [
                    'id'   => (string) $tag->id,
                    'name' => $tag->name
                ];
            }
            wp_send_json_success($tags, 200);
        }
        if (!empty($response['tokenDetails']) && $response['tokenDetails'] && !empty($queryParams->id)) {
            static::saveRefreshedToken($queryParams->id, $response['tokenDetails'], $response);
        }
        wp_send_json_success($response, 200);
    }

    public static function refreshCustomFieldAjaxHelper($queryParams)
    {
        if (
            empty($queryParams->clientId)
            || empty($queryParams->clientSecret)
            || empty($queryParams->tokenDetails)
        ) {
            wp_send_json_error(
                __(
                    'Requested parameter is empty',
                    'bit-integrations'
                ),
                400
            );
        }
        $response = [];
        if ((\intval($queryParams->tokenDetails->generates_on) + (55 * 60)) < time()) {
            $response['tokenDetails'] = static::refreshAccessToken($queryParams);
        }
        $apiEndpoint = 'https://api.infusionsoft.com/crm/rest/v1/contacts/model';
        $tokenDetails = empty($response['tokenDetails']) ? $queryParams->tokenDetails : $response['tokenDetails'];
        $headers = [
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' . $tokenDetails->access_token
        ];

        $customFieldResponse = HttpHelper::get($apiEndpoint, null, $headers);
        if (isset($customFieldResponse->error)) {
            wp_send_json_error('Custom Field fetch failed', 400);

            return;
        }
        $customFields = [];
        foreach ($customFieldResponse->custom_fields as $field) {
            $customFields[] = ['key' => "custom_fields_{$field->id}", 'label' => $field->label, 'required' => false];
        }

        if (!empty($response['tokenDetails']) && !empty($queryParams->id)) {
            static::saveRefreshedToken($queryParams->id, $response['tokenDetails']);
        }
        wp_send_json_success($customFields, 200);
    }

    /**
     * Process ajax request for generate_token
     *
     * @param object $requestsParams Params for generate token
     *
     * @return JSON Keap api response and status
     */
    public static function generateTokens($requestsParams)
    {
        if (
            empty($requestsParams->clientId)
            || empty($requestsParams->clientSecret)
            || empty($requestsParams->redirectURI)
            || empty($requestsParams->code)
        ) {
            wp_send_json_error(
                __(
                    'Requested parameter is empty',
                    'bit-integrations'
                ),
                400
            );
        }

        $apiEndpoint = 'https://api.infusionsoft.com/token';
        $authorizationHeader['Content-Type'] = 'application/x-www-form-urlencoded';
        $requestParams = [
            'client_id'     => $requestsParams->clientId,
            'client_secret' => $requestsParams->clientSecret,
            'code'          => $requestsParams->code,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $requestsParams->redirectURI
        ];
        $apiResponse = HttpHelper::post($apiEndpoint, $requestParams, $authorizationHeader);

        if (is_wp_error($apiResponse) || !empty($apiResponse->error)) {
            wp_send_json_error(
                empty($apiResponse->error) ? 'Unknown' : $apiResponse->error,
                400
            );
        }
        $apiResponse->generates_on = time();
        wp_send_json_success($apiResponse, 200);
    }

    public static function refreshAccessToken($requestsParams)
    {
        if (
            empty($requestsParams->clientId)
            || empty($requestsParams->clientSecret)
            || empty($requestsParams->tokenDetails)
        ) {
            wp_send_json_error(
                __(
                    'Requested parameter is empty',
                    'bit-integrations'
                ),
                400
            );
        }
        $apiEndpoint = 'https://api.infusionsoft.com/token';
        $tokenDetails = $requestsParams->tokenDetails;
        $authorizationHeader['Authorization'] = 'Basic ' . base64_encode("{$requestsParams->clientId}:{$requestsParams->clientSecret}");
        $requestParams = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $requestsParams->tokenDetails->refresh_token,
        ];
        $apiResponse = HttpHelper::post($apiEndpoint, $requestParams, $authorizationHeader);
        if (is_wp_error($apiResponse) || !empty($apiResponse->error)) {
            return false;
        }
        $tokenDetails->generates_on = time();
        $tokenDetails->access_token = $apiResponse->access_token;
        $tokenDetails->refresh_token = $apiResponse->refresh_token;

        return $tokenDetails;
    }

    /**
     * Save updated access_token to avoid unnecessary token generation
     *
     * @param object $integrationData Details of flow
     * @param array  $fieldValues     Data to send Mail Chimp
     *
     * @return null
     */
    public function execute($integrationData, $fieldValues)
    {
        $integrationDetails = $integrationData->flow_details;
        $tokenDetails = $integrationDetails->tokenDetails;
        // $listId = $integrationDetails->listId;
        // $tags = $integrationDetails->tags;
        $fieldMap = $integrationDetails->field_map;
        $actions = $integrationDetails->actions;
        // $addressFields = $integrationDetails->address_field;

        if (
            empty($tokenDetails)
            || empty($fieldMap)
        ) {
            return new WP_Error('REQ_FIELD_EMPTY', wp_sprintf(__('module, fields are required for %s api', 'bit-integrations'), 'Keap'));
        }

        if ((\intval($tokenDetails->generates_on) + (60 * 60 * 23)) < time()) {
            $requiredParams['clientId'] = $integrationDetails->clientId;
            $requiredParams['clientSecret'] = $integrationDetails->clientSecret;
            $requiredParams['tokenDetails'] = $tokenDetails;
            $newTokenDetails = static::refreshAccessToken((object) $requiredParams);
            $tokenDetails = $newTokenDetails;
            self::saveRefreshedToken($this->_integrationID, $tokenDetails);
        }

        // $requiredParams['clientId'] = $integrationDetails->clientId;
        // $requiredParams['clientSecret'] = $integrationDetails->clientSecret;
        // $requiredParams['tokenDetails'] = $tokenDetails;
        // $newTokenDetails = static::refreshAccessToken((object)$requiredParams);
        // $tokenDetails = $newTokenDetails;
        // self::saveRefreshedToken($this->_integrationID, $tokenDetails);

        $recordApiHelper = new RecordApiHelper($tokenDetails, $this->_integrationID);
        $keapApiResponse = $recordApiHelper->execute(
            $integrationDetails,
            $fieldValues,
            $fieldMap,
            $actions
        );

        if (is_wp_error($keapApiResponse)) {
            return $keapApiResponse;
        }

        return $keapApiResponse;
    }

    private static function saveRefreshedToken($integrationID, $tokenDetails)
    {
        if (empty($integrationID)) {
            return;
        }

        $flow = new FlowController();
        $keapDetails = $flow->get(['id' => $integrationID]);
        if (is_wp_error($keapDetails)) {
            return;
        }

        $newDetails = json_decode($keapDetails[0]->flow_details);
        $newDetails->tokenDetails = $tokenDetails;
        $flow->update($integrationID, ['flow_details' => wp_json_encode($newDetails)]);
    }
}
