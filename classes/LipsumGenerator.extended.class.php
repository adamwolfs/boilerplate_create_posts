<?php
/** --------------------------------------------------------------------------------------------------------
  LipsumGenerator_extended_class class
  Extends the LipSumGenerator for two purposes.
  1. Replaces the <br> return as P tags.
  2. Sets the Curl Option to ignore SSL Verify otherwise it wont work during http development
-------------------------------------------------------------------------------------------------------- */

namespace MasterOdin\Gists;

require(BOILERPLATE_CREATE_POSTS_DIR . 'includes\lorem_ipsum\LipsumGenerator.class.php');
class LipsumGenerator_extended_class extends LipsumGenerator
{
    public static function getParagraphs($amount = 5, $start = true)
    {
        $json_response = static::sendRequest('paras', intval($amount), $start === true);
        return "<p>" . str_replace("\n", "</p>\n<p>", $json_response) . "</p>";
    }

    private static function sendRequest($type, $amount, $start)
    {
        $start = ($start === true) ? "yes" : "no";
        $url = static::BASE_URL . '?' . http_build_query(array("what" => $type, "amount" => $amount, "start" => $start));
        $ch = curl_init($url);
        $timeout = 5;
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($data, true);
        return $json['feed']['lipsum'];
    }

}