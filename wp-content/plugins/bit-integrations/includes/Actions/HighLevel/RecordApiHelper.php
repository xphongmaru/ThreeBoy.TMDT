<?php

/**
 * HighLevel Record Api
 */

namespace BitCode\FI\Actions\HighLevel;

use BitCode\FI\Log\LogHandler;
use BitCode\FI\Core\Util\Common;
use BitCode\FI\Core\Util\Helper;
use BitCode\FI\Core\Util\HttpHelper;

/**
 * Provide functionality for Record insert,update, exist
 */
class RecordApiHelper
{
    private $defaultHeader;

    private $integrationID;

    private $baseUrl = 'https://rest.gohighlevel.com/v1/';

    public function __construct($apiKey, $integId)
    {
        $this->defaultHeader = [
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json'
        ];

        $this->integrationID = $integId;
    }

    public function createContact($finalData, $selectedOptions, $actions)
    {
        if (empty($finalData['email'])) {
            return ['success' => false, 'message' => __('Request parameter(s) empty!', 'bit-integrations'), 'code' => 400];
        }

        $apiRequestData = self::formatContactData($finalData, $selectedOptions, $actions, 'createContact');

        $apiEndpoint = $this->baseUrl . 'contacts';

        $response = HttpHelper::post($apiEndpoint, wp_json_encode($apiRequestData), $this->defaultHeader);

        if (isset($response->contact)) {
            return ['success' => true, 'message' => __('Contact created successfully.', 'bit-integrations')];
        }

        return ['success' => false, 'message' => __('Failed to create contact!', 'bit-integrations'), 'response' => $response, 'code' => 400];
    }

    public function updateContact($finalData, $selectedOptions, $actions)
    {
        if (empty($selectedOptions['selectedContact']) && empty($finalData['id'])) {
            return ['success' => false, 'message' => __('Contact id not found in request!', 'bit-integrations'), 'code' => 400];
        }

        if (!empty($selectedOptions['selectedContact'])) {
            $id = $selectedOptions['selectedContact'];
        } else {
            $id = $finalData['id'];
        }

        $apiRequestData = self::formatContactData($finalData, $selectedOptions, $actions, 'updateContact');

        $apiEndpoint = $this->baseUrl . 'contacts/' . $id;

        $response = HttpHelper::put($apiEndpoint, wp_json_encode($apiRequestData), $this->defaultHeader);

        if (isset($response->contact)) {
            return ['success' => true, 'message' => __('Contact updated successfully.', 'bit-integrations')];
        }

        return ['success' => false, 'message' => __('Failed to update contact!', 'bit-integrations'), 'response' => $response, 'code' => 400];
    }

    public function createTask($finalData, $selectedOptions, $actions)
    {
        if (empty($selectedOptions['selectedContact']) && empty($finalData['contactId'])) {
            return ['success' => false, 'message' => __('Contact id not found in request!', 'bit-integrations'), 'code' => 400];
        }

        if (empty($finalData['dueDate']) || empty($finalData['title'])) {
            return ['success' => false, 'message' => __('Request parameter(s) empty!', 'bit-integrations'), 'code' => 400];
        }

        if (!empty($selectedOptions['selectedContact'])) {
            $contactId = $selectedOptions['selectedContact'];
        } else {
            $contactId = $finalData['contactId'];
        }

        $apiRequestData['title'] = $finalData['title'];
        $apiRequestData['description'] = !empty($finalData['description']) ? $finalData['description'] : '';
        $apiRequestData['dueDate'] = !empty($finalData['dueDate']) ? $finalData['dueDate'] : '';
        $apiRequestData['assignedTo'] = !empty($selectedOptions['selectedUser']) ? $selectedOptions['selectedUser'] : '';
        $apiRequestData['status'] = !empty($selectedOptions['selectedTaskStatus']) ? $selectedOptions['selectedTaskStatus'] : '';

        $apiEndpoint = $this->baseUrl . 'contacts/' . $contactId . '/tasks';

        $response = HttpHelper::post($apiEndpoint, wp_json_encode($apiRequestData), $this->defaultHeader);

        if (isset($response->id)) {
            return ['success' => true, 'message' => __('Task created successfully.', 'bit-integrations')];
        }

        return ['success' => false, 'message' => __('Failed to create task!', 'bit-integrations'), 'response' => $response, 'code' => 400];
    }

    public function updateTask($finalData, $selectedOptions, $actions)
    {
        if (empty($selectedOptions['selectedContact']) && empty($finalData['contactId'])) {
            return ['success' => false, 'message' => __('Contact id not found in request!', 'bit-integrations'), 'code' => 400];
        }

        if (empty($selectedOptions['updateTaskId']) && empty($finalData['taskId'])) {
            return ['success' => false, 'message' => __('Task id not found in request!', 'bit-integrations'), 'code' => 400];
        }

        if (empty($finalData['dueDate']) || empty($finalData['title'])) {
            return ['success' => false, 'message' => __('Request parameter(s) empty!', 'bit-integrations'), 'code' => 400];
        }

        if (!empty($selectedOptions['selectedContact'])) {
            $contactId = $selectedOptions['selectedContact'];
        } else {
            $contactId = $finalData['contactId'];
        }

        if (!empty($selectedOptions['updateTaskId'])) {
            $taskId = $selectedOptions['updateTaskId'];
        } else {
            $taskId = $finalData['taskId'];
        }

        $apiRequestData['title'] = $finalData['title'];
        $apiRequestData['description'] = !empty($finalData['description']) ? $finalData['description'] : '';
        $apiRequestData['dueDate'] = !empty($finalData['dueDate']) ? $finalData['dueDate'] : '';
        $apiRequestData['assignedTo'] = !empty($selectedOptions['selectedUser']) ? $selectedOptions['selectedUser'] : '';
        $apiRequestData['status'] = !empty($selectedOptions['selectedTaskStatus']) ? $selectedOptions['selectedTaskStatus'] : '';

        $apiEndpoint = $this->baseUrl . 'contacts/' . $contactId . '/tasks/' . $taskId;

        $response = HttpHelper::put($apiEndpoint, wp_json_encode($apiRequestData), $this->defaultHeader);

        if (isset($response->id)) {
            return ['success' => true, 'message' => __('Task updated successfully.', 'bit-integrations')];
        }

        return ['success' => false, 'message' => __('Failed to update task!', 'bit-integrations'), 'response' => $response, 'code' => 400];
    }

    public function createOpportunity($finalData, $selectedOptions, $actions)
    {
        if (empty($selectedOptions['selectedPipeline']) || empty($selectedOptions['selectedStage']) || empty($finalData['title'])) {
            return ['success' => false, 'message' => __('Request parameter(s) empty!', 'bit-integrations'), 'code' => 400];
        }

        if ($selectedOptions['selectedContact']) {
            $contactId = $selectedOptions['selectedContact'];
        } else {
            $contactId = !empty($finalData['contactId']) ? $finalData['contactId'] : '';
        }

        if (empty($finalData['email']) && empty($finalData['phone']) && empty($contactId)) {
            return ['success' => false, 'message' => __('Either a Contact ID, Email, or Phone Number is required!', 'bit-integrations'), 'code' => 400];
        }

        $apiRequestData = self::formatOpportunityData($finalData, $selectedOptions, $actions, $contactId, 'createOpportunity');

        $apiEndpoint = $this->baseUrl . 'pipelines/' . $selectedOptions['selectedPipeline'] . '/opportunities';

        $response = HttpHelper::post($apiEndpoint, wp_json_encode($apiRequestData), $this->defaultHeader);

        if (isset($response->id)) {
            return ['success' => true, 'message' => __('Opportunity created successfully.', 'bit-integrations')];
        }

        return ['success' => false, 'message' => __('Failed to create opportunity!', 'bit-integrations'), 'response' => $response, 'code' => 400];
    }

    public function updateOpportunity($finalData, $selectedOptions, $actions)
    {
        if (empty($selectedOptions['selectedPipeline']) || empty($selectedOptions['selectedStage']) || empty($finalData['title'])) {
            return ['success' => false, 'message' => __('Request parameter(s) empty!', 'bit-integrations'), 'code' => 400];
        }

        if (empty($selectedOptions['selectedOpportunity']) && empty($finalData['opportunityId'])) {
            return ['success' => false, 'message' => __('Opportunity id not found in request!', 'bit-integrations'), 'code' => 400];
        }

        if (!empty($selectedOptions['selectedOpportunity'])) {
            $opportunityId = $selectedOptions['selectedOpportunity'];
        } else {
            $opportunityId = $finalData['opportunityId'];
        }

        if ($selectedOptions['selectedContact']) {
            $contactId = $selectedOptions['selectedContact'];
        } else {
            $contactId = !empty($finalData['contactId']) ? $finalData['contactId'] : '';
        }

        if (empty($finalData['email']) && empty($finalData['phone']) && empty($contactId)) {
            return ['success' => false, 'message' => __('Either a Contact ID, Email, or Phone Number is required!', 'bit-integrations'), 'code' => 400];
        }

        $apiRequestData = self::formatOpportunityData($finalData, $selectedOptions, $actions, $contactId, 'updateOpportunity');

        $apiEndpoint = $this->baseUrl . 'pipelines/' . $selectedOptions['selectedPipeline'] . '/opportunities/' . $opportunityId;

        $response = HttpHelper::put($apiEndpoint, wp_json_encode($apiRequestData), $this->defaultHeader);

        if (isset($response->id)) {
            return ['success' => true, 'message' => __('Opportunity updated successfully.', 'bit-integrations')];
        }

        return ['success' => false, 'message' => __('Failed to update opportunity!', 'bit-integrations'), 'response' => $response, 'code' => 400];
    }

    public function generateReqDataFromFieldMap($data, $fieldMap)
    {
        $dataFinal = [];
        foreach ($fieldMap as $value) {
            $triggerValue = $value->formField;
            $actionValue = $value->highLevelField;
            if ($triggerValue === 'custom') {
                $dataFinal[$actionValue] = Common::replaceFieldWithValue($value->customValue, $data);
            } elseif (!\is_null($data[$triggerValue])) {
                $dataFinal[$actionValue] = $data[$triggerValue];
            }
        }

        return $dataFinal;
    }

    public function execute($fieldValues, $fieldMap, $selectedTask, $selectedOptions, $actions)
    {
        if (isset($fieldMap[0]) && empty($fieldMap[0]->formField)) {
            $finalData = [];
        } else {
            $finalData = $this->generateReqDataFromFieldMap($fieldValues, $fieldMap);
        }

        $type = $typeName = '';

        if ($selectedTask === 'createContact') {
            $response = $this->createContact($finalData, $selectedOptions, $actions);
            $type = 'Contact';
            $typeName = 'Create Contact';
        } elseif ($selectedTask === 'updateContact') {
            $response = $this->updateContact($finalData, $selectedOptions, $actions);
            $type = 'Contact';
            $typeName = 'Update Contact';
        } elseif ($selectedTask === 'createTask') {
            $response = $this->createTask($finalData, $selectedOptions, $actions);
            $type = 'Task';
            $typeName = 'Create Task';
        } elseif ($selectedTask === 'updateTask') {
            $response = $this->updateTask($finalData, $selectedOptions, $actions);
            $type = 'Task';
            $typeName = 'Update Task';
        } elseif ($selectedTask === 'createOpportunity') {
            $response = $this->createOpportunity($finalData, $selectedOptions, $actions);
            $type = 'Opportunity';
            $typeName = 'Create Opportunity';
        } elseif ($selectedTask === 'updateOpportunity') {
            $response = $this->updateOpportunity($finalData, $selectedOptions, $actions);
            $type = 'Opportunity';
            $typeName = 'Update Opportunity';
        }

        if ($response['success']) {
            $res = ['message' => $response['message']];
            LogHandler::save($this->integrationID, wp_json_encode(['type' => $type, 'type_name' => $typeName]), 'success', wp_json_encode($res));
        } else {
            LogHandler::save($this->integrationID, wp_json_encode(['type' => $type, 'type_name' => $typeName]), 'error', wp_json_encode($response));
        }

        return $response;
    }

    private static function formatContactData($finalData, $selectedOptions, $actions, $module = 'contact')
    {
        $staticFieldsKey = ['email', 'firstName', 'lastName', 'name', 'phone', 'dateOfBirth', 'address1', 'city', 'state', 'country', 'postalCode', 'companyName', 'website'];
        $apiRequestData = $customFieldsData = [];

        foreach ($finalData as $key => $value) {
            if (\in_array($key, $staticFieldsKey)) {
                $apiRequestData[$key] = $value;
            } else {
                $keyFieldType = explode('_bihl_', $key);
                $fieldKey = $keyFieldType[0];
                $fieldType = $keyFieldType[1];

                if ($fieldType === 'MULTIPLE_OPTIONS' || $fieldType === 'CHECKBOX') {
                    $customFieldsData[$fieldKey] = \is_string($value) ? explode(',', str_replace(' ', '', $value)) : $value;
                } else {
                    $customFieldsData[$fieldKey] = $value;
                }
            }
        }

        if (!empty($customFieldsData)) {
            $apiRequestData['customField'] = $customFieldsData;
        }

        if ((isset($selectedOptions['selectedTags']) && !empty($selectedOptions['selectedTags'])) || !empty($actions)) {
            if (Helper::proActionFeatExists('HighLevel', 'contactUtilities')) {
                $filterResponse = apply_filters('btcbi_high_level_contact_utilities', $module, $selectedOptions, $actions);

                if ($filterResponse !== $module && !empty($filterResponse)) {
                    $apiRequestData = array_merge($apiRequestData, $filterResponse);
                }
            }
        }

        return $apiRequestData;
    }

    private static function formatOpportunityData($finalData, $selectedOptions, $actions, $contactId, $module = 'opportunity')
    {
        $apiRequestData['title'] = $finalData['title'];
        $apiRequestData['status'] = !empty($selectedOptions['selectedTaskStatus']) ? $selectedOptions['selectedTaskStatus'] : '';
        $apiRequestData['stageId'] = $selectedOptions['selectedStage'];
        $apiRequestData['email'] = !empty($finalData['email']) ? $finalData['email'] : '';
        $apiRequestData['phone'] = !empty($finalData['phone']) ? $finalData['phone'] : '';
        $apiRequestData['assignedTo'] = !empty($selectedOptions['selectedUser']) ? $selectedOptions['selectedUser'] : '';
        $apiRequestData['monetaryValue'] = !empty($finalData['monetaryValue']) ? $finalData['monetaryValue'] : '';
        $apiRequestData['contactId'] = $contactId;
        $apiRequestData['name'] = !empty($finalData['name']) ? $finalData['name'] : '';
        $apiRequestData['companyName'] = !empty($finalData['companyName']) ? $finalData['companyName'] : '';

        if (!empty($selectedOptions['selectedTags'])) {
            if (Helper::proActionFeatExists('HighLevel', 'opportunityUtilities')) {
                $filterResponse = apply_filters('btcbi_high_level_opportunity_utilities', $module, $selectedOptions, $actions);

                if ($filterResponse !== $module && !empty($filterResponse)) {
                    $apiRequestData = array_merge($apiRequestData, $filterResponse);
                }
            }
        }

        return $apiRequestData;
    }
}
