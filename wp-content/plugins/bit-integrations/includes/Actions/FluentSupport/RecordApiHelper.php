<?php

/**
 * Freshdesk Record Api
 */

namespace BitCode\FI\Actions\FluentSupport;

use BitCode\FI\Log\LogHandler;
use BitCode\FI\Core\Util\Common;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Models\Customer;
use FluentSupport\App\Services\Helper;
use BitCode\FI\Core\Util\Helper as BtcbiHelper;

/**
 * Provide functionality for Record insert, upsert
 */
class RecordApiHelper
{
    private $_integrationID;

    public function __construct($integrationId)
    {
        $this->_integrationID = $integrationId;
    }

    public function generateReqDataFromFieldMap($data, $fieldMap)
    {
        $dataFinal = [];

        foreach ($fieldMap as $key => $value) {
            $triggerValue = $value->formField;
            $actionValue = $value->fluentSupportFormField;

            $value = $triggerValue === 'custom' && isset($value->customValue) ? Common::replaceFieldWithValue($value->customValue, $data) : $data[$triggerValue] ?? null;

            if (str_starts_with($actionValue, 'cf_')) {
                $dataFinal['custom_fields'][$actionValue] = $value;
            } elseif (!\is_null($data[$triggerValue])) {
                $dataFinal[$actionValue] = $value;
            }
        }

        return $dataFinal;
    }

    public function createCustomer($finalData, $attachments = null)
    {
        $customer = Customer::maybeCreateCustomer($finalData);

        if (isset($customer->id)) {
            $finalData['customer_id'] = $customer->id;

            return $this->createTicketByExistCustomer($finalData, $customer, $attachments);
        }
        wp_send_json_error(
            __(
                'Create Customer Failed!',
                'bit-integrations'
            ),
            400
        );
    }

    public function getCustomerExits($customer_email)
    {
        $customer = Customer::where('email', $customer_email)->first();

        return isset($customer->id) ? $customer->id : null;
    }

    public function createTicketByExistCustomer($finalData, $customer, $attachments = null)
    {
        if (!isset($finalData['mailbox_id']) || empty($finalData['mailbox_id'])) {
            $mailbox = Helper::getDefaultMailBox();
            $finalData['mailbox_id'] = $mailbox->id ?? null;
        }

        $ticket = Ticket::create($finalData);

        if (!isset($ticket->id)) {
            wp_send_json_error(
                __(
                    'Create Ticket Failed!',
                    'bit-integrations'
                ),
                400
            );
        }

        if (isset($finalData['custom_fields']) && \is_array($finalData['custom_fields'])) {
            $fields = apply_filters('fluent_support/ticket_custom_fields', []);

            if (!empty($fields)) {
                $customFields = [];
                $keys = array_keys($fields);
                $validData = array_intersect_key($finalData['custom_fields'], array_flip($keys));

                foreach ($validData as $dataKey => $value) {
                    if ($fields[$dataKey]['type'] == 'checkbox') {
                        $customFields[$dataKey] = \is_array($value) ? $value : explode(',', $value);
                    } else {
                        $customFields[$dataKey] = $value;
                    }
                }

                $ticket->syncCustomFields($customFields);
            }
        }

        if (!empty($attachments)) {
            static::uploadTicketFiles($finalData, $attachments, $ticket, $finalData['customer_id'], $this->_integrationID);
        }

        return $ticket;
    }

    public function execute(
        $fieldValues,
        $fieldMap,
        $integrationDetails
    ) {
        $attachments = null;
        $finalData = $this->generateReqDataFromFieldMap($fieldValues, $fieldMap);
        $customerExits = $this->getCustomerExits($finalData['email']);
        $finalData['client_priority'] = !empty($integrationDetails->actions->client_priority) ? $integrationDetails->actions->client_priority : 'normal';
        $finalData['agent_id'] = $integrationDetails->actions->support_staff;

        if (isset($integrationDetails->actions->business_inbox) && !empty($integrationDetails->actions->business_inbox)) {
            $finalData['mailbox_id'] = $integrationDetails->actions->business_inbox;
        }
        if (isset($integrationDetails->actions->attachment) && !empty($integrationDetails->actions->attachment)) {
            $attachments = $fieldValues[$integrationDetails->actions->attachment];
        }

        if ($customerExits) {
            $finalData['customer_id'] = $customerExits;
            $apiResponse = $this->createTicketByExistCustomer($finalData, $customerExits, $attachments);
        } else {
            $apiResponse = $this->createCustomer($finalData, $attachments);
        }

        if (isset($apiResponse->errors)) {
            LogHandler::save($this->_integrationID, ['type' => 'Ticket', 'type_name' => 'add-Ticket'], 'error', $apiResponse);
        } else {
            LogHandler::save($this->_integrationID, ['type' => 'Ticket', 'type_name' => 'add-Ticket'], 'success', $apiResponse);
        }

        return $apiResponse;
    }

    private static function uploadTicketFiles($finalData, $attachments, $ticket, $customer, $flowId)
    {
        if (BtcbiHelper::proActionFeatExists('FluentSupport', 'uploadTicketAttachments')) {
            do_action('btcbi_fluent_support_upload_ticket_attachments', $finalData, $attachments, $ticket, $customer, $flowId);
        }

        LogHandler::save($flowId, ['type' => 'Ticket', 'type_name' => 'Upload-Ticket-Attachments'], 'error', wp_sprintf(__('%s plugin is not installed or activate', 'bit-integrations'), 'Bit Integration Pro'));
    }
}
