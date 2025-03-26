<?php

/**
 * OmniSend    Record Api
 */

namespace BitCode\FI\Actions\OmniSend;

use BitCode\FI\Core\Util\Common;
use BitCode\FI\Core\Util\HttpHelper;
use BitCode\FI\Log\LogHandler;

/**
 * Provide functionality for Record insert, upsert
 */
class RecordApiHelper
{
    private $_integrationID;

    private $_integrationDetails;

    private $_defaultHeader;

    private $baseUrl = 'https://api.omnisend.com/v3/';

    public function __construct($integrationDetails, $integId)
    {
        $this->_integrationDetails = $integrationDetails;
        $this->_integrationID = $integId;
        $this->_defaultHeader = [
            'X-API-KEY' => $this->_integrationDetails->api_key
        ];
    }

    public function addContact(
        $channels,
        $emailStatus,
        $smsStatus,
        $finalData
    ) {
        $apiEndpoints = $this->baseUrl . 'contacts';
        $splitChannels = [];
        if (!empty($channels)) {
            $splitChannels = explode(',', $channels);
        } else {
            return ['success' => false, 'message' => __('At least one channel is required', 'bit-integrations'), 'code' => 400];
        }
        $email = $finalData['email'];
        $phone = $finalData['phone_number'];

        $identifires = [];
        if (\count($splitChannels)) {
            foreach ($splitChannels as $channel) {
                $status = $channel === 'email' ? $emailStatus : $smsStatus;
                $type = $channel === 'email' ? 'email' : 'phone';
                $id = $channel === 'email' ? $email : $phone;
                $identifires[] = (object) [
                    'channels' => [
                        $channel => [
                            'status' => $status
                        ]
                    ],
                    'type' => $type,
                    'id'   => $id

                ];
            }
        }

        $requestParams['identifiers'] = $identifires;

        if ($this->_integrationDetails->actions->tag && !empty($this->_integrationDetails->selected_tags)) {
            $requestParams['tags'] = explode(',', $this->_integrationDetails->selected_tags);
        }

        foreach ($finalData as $key => $value) {
            if ($key !== 'email' && $key !== 'phone_number') {
                $requestParams[$key] = $value;
            }
        }

        return HttpHelper::post($apiEndpoints, wp_json_encode($requestParams), $this->_defaultHeader);
    }

    public function generateReqDataFromFieldMap($data, $fieldMap)
    {
        $dataFinal = [];
        foreach ($fieldMap as $key => $value) {
            $triggerValue = $value->formField;
            $actionValue = $value->omniSendFormField;
            if ($triggerValue === 'custom') {
                $dataFinal[$actionValue] = Common::replaceFieldWithValue($value->customValue, $data);
            } elseif (!\is_null($data[$triggerValue])) {
                $dataFinal[$actionValue] = $data[$triggerValue];
            }
        }

        return $dataFinal;
    }

    public function execute(
        $channels,
        $emailStatus,
        $smsStatus,
        $fieldValues,
        $fieldMap
    ) {
        $finalData = $this->generateReqDataFromFieldMap($fieldValues, $fieldMap);
        $apiResponse = $this->addContact(
            $channels,
            $emailStatus,
            $smsStatus,
            $finalData
        );

        if (isset($apiResponse->error)) {
            LogHandler::save($this->_integrationID, wp_json_encode(['type' => 'contact', 'type_name' => 'add-contact']), 'error', wp_json_encode($apiResponse));
        } else {
            LogHandler::save($this->_integrationID, wp_json_encode(['type' => 'contact', 'type_name' => 'add-contact']), 'success', wp_json_encode($apiResponse));
        }

        return $apiResponse;
    }
}
