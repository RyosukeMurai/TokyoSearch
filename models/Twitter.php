<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\CurlModel;

use Abraham\TwitterOAuth\TwitterOAuth;
/**
 * Twitter is the model behind the login form.
 */
class Twitter extends CurlModel
{
    //Twitter API を利用するための Twitter Apps の情報を入れる
    protected $consumerKey       = 'UUk8IACHQb5PveOaIqwpRuAyr';
    protected $consumerSecret    = 'DfT5jQGmklsu2TdMTNn6pFzKIlwvdtwvUhhELJk6j2ke01MWYZ';
    protected $accessToken       = '3264190082-gX6jwAEWcNYOY261An3IAmEwd2RATYZv9TMiz2k';
    protected $accessTokenSecret = 'PTaTcdw1vVsqZw2oDPwAFXCPfz7XpSjEBU4aR27H6V4e0';

    protected $location;

    public function getPosts()
    {
        //接続情報
        $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $this->accessToken, $this->accessTokenSecret);

        $content = $connection->get("search/tweets", [
            "geocode" => $this->location,
            "result_type" => "recent",
        ]);

        return $content;
    }


    public function setLocation($value)
    {
        $this->location = $value['coords']['latitude'].','.$value['coords']['longitude'].',0.5km';
    }
}

