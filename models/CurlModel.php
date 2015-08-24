<?php

namespace app\models;

use linslin\yii2\curl;
use Yii;
use yii\base\Model;

/**
 * UrlRequest is the model behind the login form.
 */
class CurlModel extends Model
{

    protected $url = "";

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
        ];
    }

    public function get($url)
    {
        $curl = new curl\Curl();
        return $curl->get($url);
    }

    public function post($config = [])
    {
    }
}

