<?php

class Page {
    private $url;

    public function __construct($url) {
        $this->url = $url;
    }

    public function getHTML() {
        $html = "";

        if (function_exists('curl_init')) {
            $ch = curl_init();
            $timeout = 0;
            curl_setopt ($ch, CURLOPT_URL, $this->url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $html = curl_exec($ch);
            curl_close($ch);

        } else {
            $html = @file_get_contents($this->url);
        }

        return $html;
    }
}
