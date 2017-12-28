<?php

namespace Singiu\Netease\Http;
class Response
{
    private $status_code;
    private $content;
    private $url;

    public function __construct($ch)
    {
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $this->content = curl_exec($ch);
        $this->status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }

    public function getResponseText()
    {
        return $this->content;
    }

    public function getResponseJson()
    {
        return json_decode($this->content, true);
    }
}