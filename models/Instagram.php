<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\CurlModel;

/**
 * Instagram is the model behind the login form.
 */
class Instagram extends CurlModel
{
    //TODO 構成情報で初期化するようにする。
    const ACCESS_TOKEN = "2092714313.0a9d1bb.d0acbfab3c8846baac57a678cab21779";
    const POST_URL = "https://api.instagram.com/v1/locations/{location_id}/media/recent";
    const MEDIA_URL = "https://api.instagram.com/v1/media/search";
    const LOCATION_URL = "https://api.instagram.com/v1/locations/search";

    /**
     * Performs the data validation.
     * @param Location $location location
     * @return Instagram Place Object.
     * @throws InvalidParamException if the current scenario is unknown.
     */
    public function getPostsById($location_id)
    {
        $response = $this->get(str_replace("{location_id}", $location_id, Instagram::POST_URL)."?".http_build_query([
                "access_token" =>Instagram::ACCESS_TOKEN
            ]));

        return json_decode($response);
    }

    /**
     * Performs the data validation.
     * @param Location $location location
     * @return Instagram Place Object.
     * @throws InvalidParamException if the current scenario is unknown.
     */
    public function getPostsByLocation($location)
    {
        $res = $this->get(instagram::MEDIA_URL."?".http_build_query([
                "lat" => $location->latitude,
                "lng" => $location->longitude,
                "access_token" => Instagram::ACCESS_TOKEN
            ]));
        return json_decode($res);
    }

    /**
     * Performs the data validation.
     * @param Location $location location
     * @return Instagram Place Object.
     * @throws InvalidParamException if the current scenario is unknown.
     */
    public function getPlaces($location)
    {
        $res = $this->get(instagram::LOCATION_URL."?".http_build_query([
                "lat" => $location->latitude,
                "lng" => $location->longitude,
                "access_token" => Instagram::ACCESS_TOKEN
            ]));
        return json_decode($res);
    }
}

