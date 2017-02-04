<?php

namespace Saver\Core;

/**
 * Class SaverFile
 * @property $fileName string
 * @property $url string
 * @package Saver\Core
 */
class SaverFile
{
    private $fileName;
    private $url;
    
    /**
     * Mime Types
     * @var array
     */
    private static $mime = [
        'image/png' => 'png',
        'image/jpeg' => 'jpeg',
        'image/gif' => 'gif'
    ];

    /**
     * SaverFile constructor.
     * Send url as parameter
     * @param $url
     */
    public function __construct($url)
    {
        $this->fileName = md5(time());
        $this->url = $url;
    }

    /**
     * Return fileName
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Save Image
     * @throws \Exception
     */
    public function saveByUrl()
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
            throw new \Exception("Returned with fail");
        }
        $mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        if (!in_array($mime, array_keys(self::$mime))) {
            throw new \Exception("Unsupported mime type");
        }
        curl_close($ch);
        $ch = curl_init();
        $this->fileName = $this->fileName . "." . self::$mime[$mime];
        $fp = fopen($this->fileName, "w+");
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_REFERER, $this->url);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_exec($ch);

        curl_close($ch);
        fclose($fp);
    }
}