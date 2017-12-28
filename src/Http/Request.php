<?php

namespace Singiu\Netease\Http;

class Request
{
    private $ch;
    private $base_uri;

    public function __construct($options = [])
    {
        $this->ch = curl_init();
        if (array_key_exists('base_uri', $options) && is_string($options['base_uri'])) {
            $this->base_uri = $options['base_uri'];
        }
    }

    /**
     * @param $url
     * @param array $options
     * @return Response
     */
    public function post($url, $options = [])
    {
        if ($this->base_uri != null && $this->base_uri != '')
            $url = rtrim(trim($this->base_uri), '/') . '/' . ltrim(trim($url), '/');
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, true);
        if (is_array($options)) self::setOptions($this->ch, $options);
        return new Response($this->ch);
    }

    private function setOptions(&$ch, $options)
    {
        if (array_key_exists('query', $options) && is_array($options['query'])) {
            $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $url .= '?' . http_build_query($options['query']);
            curl_setopt($ch, CURLOPT_URL, $url);
        }

        if (array_key_exists('timeout', $options) && is_int($options['timeout'])) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout']);
        }

        if (array_key_exists('data', $options) && is_array($options['data'])) {
            $fields = http_build_query($options['data']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }

        if (array_key_exists('headers', $options) && is_array($options['headers'])) {
            $header = [];
            foreach ($options['headers'] as $key => $value) {
                array_push($header, $key . ': ' . $value);
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
    }
}