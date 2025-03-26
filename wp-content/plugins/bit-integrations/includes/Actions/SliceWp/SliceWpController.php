<?php

namespace BitCode\FI\Actions\SliceWp;

use WP_Error;

class SliceWpController
{
    public static function pluginActive($option = null)
    {
        if (is_plugin_active('slicewp/index.php')) {
            return $option === 'get_name' ? 'slicewp/index.php' : true;
        }

        return false;
    }

    public static function authorizeSliceWp()
    {
        if (self::pluginActive()) {
            wp_send_json_success(true, 200);
        }
        wp_send_json_error(wp_sprintf(__('%s must be activated!', 'bit-integrations'), 'SliceWp affiliate'));
    }

    public function execute($integrationData, $fieldValues)
    {
        $integrationDetails = $integrationData->flow_details;
        $integId = $integrationData->id;
        $mainAction = $integrationDetails->mainAction;
        $fieldMap = $integrationDetails->field_map;
        if (
            empty($integId)
            || empty($mainAction)
        ) {
            return new WP_Error('REQ_FIELD_EMPTY', wp_sprintf(__('module, fields are required for %s api', 'bit-integrations'), 'sliceWp affiliate'));
        }
        $recordApiHelper = new RecordApiHelper($integrationDetails, $integId);
        $sliceWpApiResponse = $recordApiHelper->execute(
            $mainAction,
            $fieldValues,
            $fieldMap,
            $integrationDetails
        );

        if (is_wp_error($sliceWpApiResponse)) {
            return $sliceWpApiResponse;
        }

        return $sliceWpApiResponse;
    }
}
