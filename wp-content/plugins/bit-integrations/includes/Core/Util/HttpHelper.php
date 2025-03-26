<?php

namespace BitCode\FI\Core\Util;

use CURLFile;

final class HttpHelper
{
    public static $responseCode;

    public static function post($url, $data, $headers = null, $options = null)
    {
        return static::request($url, 'POST', $data, $headers, $options);
    }

    public static function get($url, $data, $headers = null, $options = null)
    {
        return static::request($url, 'GET', $data, $headers, $options);
    }

    public static function put($url, $data, $headers = null, $options = null)
    {
        return static::request($url, 'PUT', $data, $headers, $options);
    }

    public static function request($url, $type, $data, $headers = null, $options = null)
    {
        $headers['user-agent'] = 'wordpress/bit-integrations';
        $contentType = 'application/json';

        if (isset($headers['Content-Type'])) {
            $contentType = $headers['Content-Type'];
            $contentHeaderName = 'Content-Type';
        }

        if (isset($headers['content-type'])) {
            $contentHeaderName = 'Content-Type';
            $contentType = $headers['content-type'];
        }

        if (
            $type !== 'GET'
            && strpos(strtolower($contentType), 'form')
            && !strpos(strtolower($contentType), 'urlencoded')
        ) {
            $boundary = wp_generate_password(24);
            $headers[$contentHeaderName] = 'multipart/form-data; boundary=' . $boundary;
            $data = self::processFormData($data, $boundary);
        }

        static::$responseCode = null;
        $defaultOptions = [
            'method'  => $type,
            'headers' => $headers,
            'body'    => $data,
            'timeout' => 30
        ];

        $options = wp_parse_args($options, $defaultOptions);
        $requestReponse = wp_remote_request($url, $options);

        if (is_wp_error($requestReponse)) {
            return $requestReponse;
        }

        static::$responseCode = wp_remote_retrieve_response_code($requestReponse);
        $responseBody = wp_remote_retrieve_body($requestReponse);
        $jsonData = json_decode($responseBody);

        return \is_null($jsonData) ? $responseBody : $jsonData;
    }

    public static function processFormData($data, $boundary)
    {
        $payload = '';

        $count = 0;

        foreach ($data as $name => $value) {
            if (is_iterable($value)) {
                foreach ($value as $singleValue) {
                    $count++;
                    $payload .= self::processFormField($boundary, $name . '[]', $singleValue);
                }
            } else {
                $count++;
                $payload .= self::processFormField($boundary, $name, $value);
            }
        }

        $payload .= '--' . $boundary . '--';

        return $payload;
    }

    public static function processFormField($boundary, $name, $value)
    {
        $payload = '';
        if ($value instanceof CURLFile) {
            $payload .= self::localFile($boundary, $name, $value);
        } else {
            $payload .= self::formField($boundary, $name, $value);
        }

        return $payload;
    }

    public static function formField($boundary, $name, $value)
    {
        $payload = '--' . $boundary;
        $payload .= "\r\n";
        $payload .= 'Content-Disposition: form-data; name="' . $name
            . '"' . "\r\n\r\n";
        $payload .= \is_string($value) ? $value : wp_json_encode($value);
        $payload .= "\r\n";

        return $payload;
    }

    public static function localFile($boundary, $name, CURLFile $file)
    {
        $payload = '--' . $boundary;
        $payload .= "\r\n";
        $payload .= 'Content-Disposition: form-data; name="' . $name
            . '"; filename="' . basename($file->getFilename()) . '"' . "\r\n";
        $payload .= 'Content-Type: ' . $file->getMimeType() . "\r\n";
        $payload .= "\r\n";
        $payload .= file_get_contents($file->getFilename());
        $payload .= "\r\n";

        return $payload;
    }
}
