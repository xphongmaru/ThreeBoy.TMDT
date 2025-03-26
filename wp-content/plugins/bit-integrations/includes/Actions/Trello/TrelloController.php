<?php

/**
 * Trello Integration
 */

namespace BitCode\FI\Actions\Trello;

use WP_Error;
use BitCode\FI\Core\Util\HttpHelper;

/**
 * Provide functionality for Trello integration
 */
class TrelloController
{
    private $baseUrl = 'https://api.trello.com/1/';

    private $_integrationID;

    private $accessToken;

    public function fetchAllBoards($queryParams)
    {
        if (
            empty($queryParams->accessToken)
            || empty($queryParams->clientId)
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
        $apiEndpoint = $this->baseUrl . 'members/me?key=' . $queryParams->clientId . '&token=' . $queryParams->accessToken;
        $getUserInfoResponse = HttpHelper::get($apiEndpoint, null);
        $apiEndpoint = $this->baseUrl . 'members/' . $getUserInfoResponse->username . '/boards?key=' . $queryParams->clientId . '&token=' . $queryParams->accessToken;
        $allBoardResponse = HttpHelper::get($apiEndpoint, null);

        $allList = [];
        if (!is_wp_error($allBoardResponse) && empty($allBoardResponse->response->error)) {
            $boardLists = $allBoardResponse;
            foreach ($boardLists as $boardList) {
                $allList[] = (object) [
                    'boardId'   => $boardList->id,
                    'boardName' => $boardList->name
                ];
            }
            uksort($allList, 'strnatcasecmp');
            $response['allBoardlist'] = $allList;
        } else {
            wp_send_json_error(
                $allBoardResponse->response->error->message,
                400
            );
        }
        wp_send_json_success($response, 200);
    }

    public function fetchAllLists($queryParams)
    {
        if (
            empty($queryParams->accessToken)
            || empty($queryParams->clientId)
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

        $apiEndpoint = $this->baseUrl . 'boards/' . $queryParams->boardId . '/lists?key=' . $queryParams->clientId . '&token=' . $queryParams->accessToken;
        $getListsResponse = HttpHelper::get($apiEndpoint, null);

        $allList = [];
        if (!is_wp_error($getListsResponse) && empty($getListsResponse->response->error)) {
            $singleBoardLists = $getListsResponse;
            foreach ($singleBoardLists as $singleBoardList) {
                $allList[] = (object) [
                    'listId'   => $singleBoardList->id,
                    'listName' => $singleBoardList->name
                ];
            }
            uksort($allList, 'strnatcasecmp');
            $response['alllists'] = $allList;
        } else {
            wp_send_json_error(
                $getListsResponse->response->error->message,
                400
            );
        }
        wp_send_json_success($response, 200);
    }

    public function fetchAllCustomFields($queryParams)
    {
        if (
            empty($queryParams->accessToken)
            || empty($queryParams->clientId)
        ) {
            wp_send_json_error(
                __(
                    'Requested parameter is empty',
                    'bit-integrations'
                ),
                400
            );
        }

        $allFields = apply_filters('btcbi_trello_get_all_custom_fields', [], $queryParams->boardId, $queryParams->clientId, $queryParams->accessToken);
        wp_send_json_success($allFields, 200);
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
        $integId = $integrationData->id;
        $fieldMap = $integrationDetails->field_map;
        $customFieldMap = $integrationDetails->custom_field_map ?? [];
        $defaultDataConf = $integrationDetails->default;

        if (
            empty($integrationDetails->listId)
            || empty($fieldMap)
            || empty($defaultDataConf)
        ) {
            return new WP_Error('REQ_FIELD_EMPTY', wp_sprintf(__('module, fields are required for %s api', 'bit-integrations'), 'Trello'));
        }
        $recordApiHelper = new RecordApiHelper($integrationDetails, $integId);
        $trelloApiResponse = $recordApiHelper->execute($fieldValues, $fieldMap, $customFieldMap);

        if (is_wp_error($trelloApiResponse)) {
            return $trelloApiResponse;
        }

        return $trelloApiResponse;
    }
}
