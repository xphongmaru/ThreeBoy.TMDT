<?php

/**
 * MailerLite Integration
 */

namespace BitCode\FI\Actions\MailerLite;

use BitCode\FI\Core\Util\HttpHelper;
use WP_Error;

/**
 * Provide functionality for MailerLite integration
 */
class MailerLiteController
{
    protected $_defaultHeader;

    private static $_baseUrlV1 = 'https://api.mailerlite.com/api/v2/';

    private static $_baseUrlV2 = 'https://connect.mailerlite.com/api/';

    public function fetchAllGroups($refreshFieldsRequestParams)
    {
        if (empty($refreshFieldsRequestParams->auth_token)) {
            wp_send_json_error(
                __(
                    'Requested parameter is empty',
                    'bit-integrations'
                ),
                400
            );
        }
        // var_dump('fjkasfhjklas');
        // die;
        if ('v2' === $refreshFieldsRequestParams->version) {
            $apiKey = $refreshFieldsRequestParams->auth_token;
            $endpoint = self::$_baseUrlV2 . 'groups';
            $header = [
                'Authorization' => "Bearer {$apiKey}",
                'Accept'        => 'application/json',
            ];
            $response = HttpHelper::get($endpoint, null, $header);
            $formattedResponse = [];

            foreach ($response->data as $value) {
                $formattedResponse[]
                    = [
                        'group_id' => $value->id,
                        'name'     => $value->name,
                    ];
            }
        } else {
            $apiEndpoints = self::$_baseUrlV1 . 'groups/';

            $header = [
                'X-Mailerlite-Apikey' => $refreshFieldsRequestParams->auth_token,
            ];

            $response = HttpHelper::get($apiEndpoints, null, $header);
            $formattedResponse = [];

            foreach ($response as $value) {
                $formattedResponse[]
                    = [
                        'group_id' => $value->id,
                        'name'     => $value->name,
                    ];
            }
        }

        if ($response !== 'Unauthorized' || $response !== 'Unauthenticated.') {
            wp_send_json_success($formattedResponse, 200);
        } else {
            wp_send_json_error(
                'The token is invalid',
                400
            );
        }
    }

    public function mailerliteRefreshFields($refreshFieldsRequestParams)
    {
        if (empty($refreshFieldsRequestParams->auth_token)) {
            wp_send_json_error(
                __(
                    'Requested parameter is empty',
                    'bit-integrations'
                ),
                400
            );
        }

        if ('v2' === $refreshFieldsRequestParams->version) {
            $apiEndpoints = self::$_baseUrlV2 . 'fields';

            $apiKey = $refreshFieldsRequestParams->auth_token;
            $header = [
                'Authorization' => 'Bearer ' . $apiKey,
            ];

            $response = HttpHelper::get($apiEndpoints, null, $header);

            $newResponse = [];
            foreach ($response->data as $value) {
                if ('email' !== $value->key) {
                    $newResponse[] = [
                        'key'      => $value->key,
                        'label'    => $value->name,
                        'required' => 'email' === $value->key ? true : false,
                    ];
                }
            }

            $email[] = [
                'key'      => 'email',
                'label'    => 'Email',
                'required' => true,
            ];

            $formattedResponse = array_merge($email, $newResponse);

            if (isset($response->data)) {
                wp_send_json_success($formattedResponse, 200);
            } elseif (isset($response->message) && 'Unauthenticated.' === $response->message) {
                wp_send_json_error(
                    __(
                        'Invalid API Token',
                        'bit-integrations'
                    ),
                    401
                );
            }
        } else {
            $apiEndpoints = self::$_baseUrlV1 . 'fields';

            $apiKey = $refreshFieldsRequestParams->auth_token;
            $header = [
                'X-Mailerlite-Apikey' => $apiKey,
            ];

            $response = HttpHelper::get($apiEndpoints, null, $header);

            $formattedResponse = [];
            foreach ($response as $value) {
                $formattedResponse[] = [
                    'key'      => $value->key,
                    'label'    => $value->title,
                    'required' => 'email' === $value->key ? true : false,
                ];
            }
        }
        if (\count($response) > 0) {
            wp_send_json_success($formattedResponse, 200);
        } else {
            wp_send_json_error(
                'The token is invalid',
                400
            );
        }
    }

    public function execute($integrationData, $fieldValues)
    {
        $integrationDetails = $integrationData->flow_details;
        $integId = $integrationData->id;
        $auth_token = $integrationDetails->auth_token;
        $version = $integrationDetails->version;
        $groupIds = $integrationDetails->group_ids;
        $fieldMap = $integrationDetails->field_map;
        $type = $integrationDetails->mailer_lite_type;
        $actions = $integrationDetails->actions;

        if (
            empty($fieldMap)
            || empty($auth_token)
        ) {
            return new WP_Error('REQ_FIELD_EMPTY', wp_sprintf(__('module, fields are required for %s api', 'bit-integrations'), 'MailerLite'));
        }
        $recordApiHelper = new RecordApiHelper($auth_token, $integrationDetails, $integId, $actions, $version);
        $mailerliteApiResponse = $recordApiHelper->execute(
            $groupIds,
            $type,
            $fieldValues,
            $fieldMap,
            $auth_token
        );

        if (is_wp_error($mailerliteApiResponse)) {
            return $mailerliteApiResponse;
        }

        return $mailerliteApiResponse;
    }
}
