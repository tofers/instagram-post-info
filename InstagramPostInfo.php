<?php

namespace tofers\instagrampost;
/**
 * Class InstagramPostInfo
 * @package tofers\instagrampost
 *
 *
 * @property string $src
 * @property string $account
 * @property string $location
 * @property string $image
 * @property string $date
 * @property integer $like
 *
 * @property string $error
 * @property string $error_code
 * @property string $error_text
 */
class InstagramPostInfo
{
    public $src;
    public $account;
    public $location;
    public $image;
    public $date;
    public $like;

    public $error;
    public $error_code;
    public $error_text;

    public function __construct($src)
    {
        // remove param url
        $src = strtok($src, '?');
        if (strpos($src, "embed") === false) {
            if (substr($src, -1) != "/") {
                $src .= "/";
            }
            $src .= "embed/";
        }
        $instagram_post = $src;
        $this->src = $instagram_post;
        $c_instagram = curl_init($instagram_post);
        curl_setopt($c_instagram, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c_instagram, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($c_instagram, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36 OPR/71.0.3770.284');

        $instagram_content = curl_exec($c_instagram);
        $instagram_code = curl_getinfo($c_instagram, CURLINFO_HTTP_CODE);

        if ($instagram_code != 200) {
            $this->error = $instagram_code;
            $this->error_code = curl_errno($c_instagram);
            $this->error_text = curl_strerror($this->error_code);
            return false;
        }

        //$instagram_content = file_get_contents($instagram_post);

        $doc = new \DOMDocument();
        @$doc->loadHTML($instagram_content);

        $xpath = new \DOMXpath($doc);
        $images = $xpath->query('//img[@class="EmbeddedMediaImage"]');

        // all links in .blogArticle
        $image_src = false;
        foreach ($images as $container) {
            if (!$image_src) {
                $image_src = $container->getAttribute("src");
                $this->image = $image_src;
            }
        }

        $accounts = $xpath->query('//span[@class="UsernameText"]');
        $account = false;
        foreach ($accounts as $container) {
            if (!$this->account) {
                $this->account = trim(preg_replace("/[\r\n]+/", " ", $container->nodeValue));
            }
        }
        $locations = $xpath->query('//a[@class="Location"]');
        foreach ($locations as $container) {
            if (!$this->location) {
                $this->location = trim(preg_replace("/[\r\n]+/", " ", $container->nodeValue));
            }
        }

        $likes = $xpath->query('//div[@class="SocialProof"]');
        foreach ($likes as $container) {
            $arr = $container->getElementsByTagName("a");
            foreach ($arr as $item) {
                $like = trim(preg_replace("/[\r\n]+/", " ", $item->nodeValue));
                $this->like = preg_replace("/[^0-9]/", '', $like);
            }
        }
        if ($image_src) {
            $image_headers = get_headers($image_src);
            foreach ($image_headers as $image_header) {
                $dh = explode(':', $image_header);
                if ($dh[0] == "Last-Modified" && isset($dh[1])) {
                    $this->date = str_ireplace("Last-Modified:", '', $image_header);
                }
            }
        }
        return true;
    }
}