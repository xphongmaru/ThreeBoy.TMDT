<?php

/**
 * ZohoProjects Files Api
 */

namespace BitCode\FI\Actions\ZohoProjects;

use BitCode\FI\Core\Util\HttpHelper;

/**
 * Provide functionality for Upload files
 */
final class FilesApiHelper
{
    private $_defaultHeader;

    private $_apiDomain;

    private $_payloadBoundary;

    private $_basepath;

    /**
     * @param object $tokenDetails Api token details
     * @param int    $formID       ID of the form, for which integration is executing
     * @param int    $entryID      Current submittion ID
     */
    public function __construct($tokenDetails, $formID, $entryID)
    {
        $this->_payloadBoundary = wp_generate_password(24);
        $this->_defaultHeader['Authorization'] = "Zoho-oauthtoken {$tokenDetails->access_token}";
        $this->_defaultHeader['content-type'] = 'multipart/form-data; boundary=' . $this->_payloadBoundary;
        $this->_apiDomain = urldecode($tokenDetails->api_domain);
        $this->_basepath = UPLOAD_DIR . DIRECTORY_SEPARATOR . $formID . DIRECTORY_SEPARATOR . $entryID . DIRECTORY_SEPARATOR;
    }

    /**
     * Helps to execute upload files api
     *
     * @param mixed $files        Files path
     * @param bool  $isAttachment Check upload type
     * @param mixed $module       Attachment Module name
     * @param mixed $recordID     Record id
     * @param mixed $portalId
     * @param mixed $projectId
     * @param mixed $event
     * @param mixed $eventId
     * @param mixed $dataCenter
     *
     * @return array $uploadedFiles ID's of uploaded file in Zoho Projects
     */
    public function uploadFiles($files, $portalId, $projectId, $event, $eventId, $dataCenter)
    {
        $uploadFileEndpoint = "https://projectsapi.zoho.{$dataCenter}/restapi/portal/{$portalId}/projects/{$projectId}/" . ($event === 'task' || $event === 'subtask' ? 'tasks' : 'bugs') . "/${eventId}/attachments/";

        $payload = '';
        if (\is_array($files)) {
            foreach ($files as $fileIndex => $fileName) {
                if (file_exists("{$this->_basepath}{$fileName}")) {
                    $payload .= '--' . $this->_payloadBoundary;
                    $payload .= "\r\n";
                    $payload .= 'Content-Disposition: form-data; name="' . 'uploaddoc'
                        . '"; filename="' . basename("{$this->_basepath}{$fileName}") . '"' . "\r\n";
                    $payload .= "\r\n";
                    $payload .= file_get_contents("{$this->_basepath}{$fileName}");
                    $payload .= "\r\n";
                }
            }
        } elseif (file_exists("{$this->_basepath}{$files}")) {
            $payload .= '--' . $this->_payloadBoundary;
            $payload .= "\r\n";
            $payload .= 'Content-Disposition: form-data; name="' . 'uploaddoc'
                . '"; filename="' . basename("{$this->_basepath}{$files}") . '"' . "\r\n";
            $payload .= "\r\n";
            $payload .= file_get_contents("{$this->_basepath}{$files}");
            $payload .= "\r\n";
        }

        if (empty($payload)) {
            return false;
        }
        $payload .= '--' . $this->_payloadBoundary . '--';

        return HttpHelper::post($uploadFileEndpoint, $payload, $this->_defaultHeader);
    }
}
