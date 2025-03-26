<?php

namespace BitCode\FI\Actions\Dropbox;

use WP_Error;
use BitCode\FI\Log\LogHandler;
use BitCode\FI\Core\Util\Common;
use BitCode\FI\Core\Util\HttpHelper;

class RecordApiHelper
{
    protected $token;

    protected $errorApiResponse = [];

    protected $successApiResponse = [];

    protected $contentBaseUri = 'https://content.dropboxapi.com';

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function uploadFile($folder, $filePath)
    {
        if ($filePath === '') {
            return false;
        }

        $body = file_get_contents(Common::filePath(trim($filePath)));

        if (!$body) {
            return new WP_Error(423, 'Can\'t open file!');
        }

        $apiEndPoint = $this->contentBaseUri . '/2/files/upload';
        $headers = [
            'Authorization'   => 'Bearer ' . $this->token,
            'Content-Type'    => 'application/octet-stream',
            'Dropbox-API-Arg' => wp_json_encode(['path' => $folder . '/' . trim(basename($filePath)), 'mode' => 'add', 'autorename' => true, 'mute' => true, 'strict_conflict' => false]),
        ];

        return HttpHelper::post($apiEndPoint, $body, $headers);
    }

    public function handleAllFiles($folderWithFile, $actions, $folderKey = null)
    {
        foreach ($folderWithFile as $folder => $filePath) {
            $folder = $folderKey ? $folderKey : $folder;
            if ($filePath == '') {
                continue;
            }

            if (\is_array($filePath)) {
                return $this->handleAllFiles($filePath, $actions, $folder);
            }
            $response = $this->uploadFile($folder, $filePath);
            $this->storeInState($response);
            $this->deleteFile($filePath, $actions);
        }
    }

    public function deleteFile($filePath, $actions)
    {
        if (isset($actions->delete_from_wp) && $actions->delete_from_wp) {
            if (file_exists($filePath)) {
                wp_delete_file($filePath);
            }
        }
    }

    public function executeRecordApi($integrationId, $fieldValues, $fieldMap, $actions)
    {
        $folderWithFile = [];
        foreach ($fieldMap as $value) {
            if (!\is_null($fieldValues[$value->formField])) {
                $folderWithFile[$value->dropboxFormField] = $fieldValues[$value->formField];
            }
        }
        $this->handleAllFiles($folderWithFile, $actions);

        if (\count($this->successApiResponse) > 0) {
            LogHandler::save($integrationId, wp_json_encode(['type' => 'dropbox', 'type_name' => 'file_upload']), 'success', __('All Files Uploaded.', 'bit-integrations') . wp_json_encode($this->successApiResponse));
        }
        if (\count($this->errorApiResponse) > 0) {
            LogHandler::save($integrationId, wp_json_encode(['type' => 'dropbox', 'type_name' => 'file_upload']), 'error', __('Some Files Can\'t Upload.', 'bit-integrations') . wp_json_encode($this->errorApiResponse));
        }

        return true;
    }

    protected function storeInState($response)
    {
        if (isset($response->id)) {
            $this->successApiResponse[] = $response;
        } else {
            $this->errorApiResponse[] = $response;
        }
    }
}
