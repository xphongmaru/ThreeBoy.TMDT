<?php

/**
 * Discord Files Api
 */

namespace BitCode\FI\Actions\Discord;

use CURLFile;
use BitCode\FI\Core\Util\HttpHelper;

/**
 * Provide functionality for Upload files
 */
final class FilesApiHelper
{
    private $_defaultHeader;

    private $_payloadBoundary;

    public function __construct()
    {
        $this->_payloadBoundary = wp_generate_password(24);
        $this->_defaultHeader['Content-Type'] = 'multipart/form-data; boundary=' . $this->_payloadBoundary;
    }

    /**
     * Helps to execute upload files api
     *
     * @param string $apiEndPoint  discord API base URL
     * @param array  $data         Data to pass to API
     * @param mixed  $_accessToken
     * @param mixed  $channel_id
     *
     * @return array $uploadResponse discord API response
     */
    public function uploadFiles($apiEndPoint, $data, $_accessToken, $channel_id)
    {
        $uploadFileEndpoint = $apiEndPoint . '/channels/' . $channel_id . '/messages';

        if (\is_array($data['file'])) {
            $file = $data['file'][0];
        } else {
            $file = $data['file'];
        }

        if (empty($file)) {
            return false;
        }

        return HttpHelper::post(
            $uploadFileEndpoint,
            [
                'filename' => new CURLFile($file)
            ],
            [
                'Content-Type'  => 'multipart/form-data',
                'Authorization' => 'Bot ' . $_accessToken
            ]
        );
    }
}
