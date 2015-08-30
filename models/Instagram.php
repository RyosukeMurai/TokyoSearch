<?php

namespace app\models;

use app\components\ImageHelper;
use Yii;
use app\models\CurlModel;
use yii\helpers\Url;

/**
 * This is the model class for table "instagram".
 *
 * @property integer $id
 * @property string $external_id
 * @property string $videos
 * @property string $images
 * @property string $tags
 * @property string $caption
 * @property string $location
 * @property string $user
 * @property string $update
 */
class Instagram extends \yii\db\ActiveRecord
{
    //TODO 構成情報で初期化するようにする。
    const ACCESS_TOKEN = "2092714313.0a9d1bb.d0acbfab3c8846baac57a678cab21779";
    const POST_URL = "https://api.instagram.com/v1/locations/{location_id}/media/recent";
    const MEDIA_URL = "https://api.instagram.com/v1/media/search";
    const LOCATION_URL = "https://api.instagram.com/v1/locations/search";
    const MEDIA_FROM_TAG_URL = "https://api.instagram.com/v1/tags/{tag}/media/recent";
    const TAG_URL = "https://api.instagram.com/v1/tags/search";

    public $_pingicon;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instagram';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['external_id', 'videos', 'images', 'tags', 'caption', 'location', 'user'], 'safe'],
            [['external_id', 'videos', 'images', 'tags', 'caption', 'location', 'user'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'external_id' => 'External ID',
            'videos' => 'Videos',
            'images' => 'Images',
            'tags' => 'Tags',
            'caption' => 'Caption',
            'location' => 'Location',
            'user' => 'User',
            'update' => 'Update',
        ];
    }

    /**
     * @inheritdoc
     * @return InstagramQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InstagramQuery(get_called_class());
    }

    public static function httpGet($url)
    {
        $curl = new CurlModel();
        return json_decode($curl->get($url));
    }

    /**
     * Performs the data validation.
     * @param Location $location location
     * @return Instagram Place Object.
     * @throws InvalidParamException if the current scenario is unknown.
     */
    public static function getPostsById($location_id)
    {
        return static::httpGet(str_replace("{location_id}", $location_id, Instagram::POST_URL) . "?" . http_build_query([
                "access_token" => Instagram::ACCESS_TOKEN
            ]));
    }

    /**
     * Performs the data validation.
     * @param Location $location location
     * @return Instagram Place Object.
     * @throws InvalidParamException if the current scenario is unknown.
     */
    public static function getPostsByLocation($location)
    {
        /* todo realtime load ver
        return static::httpGet(instagram::MEDIA_URL . "?" . http_build_query([
                "lat" => $location->latitude,
                "lng" => $location->longitude,
                "access_token" => Instagram::ACCESS_TOKEN
            ]));
        */

        return static::find()->where('location is not null')->orderBy('update desc')->limit(200)->all();
    }

    /**
     * Performs the data validation.
     * @param Location $location location
     * @return Instagram Place Object.
     * @throws InvalidParamException if the current scenario is unknown.
     */
    public static function getTags($name)
    {
        return static::httpGet(instagram::TAG_URL . "?" . http_build_query([
                "q" => $name,
                "access_token" => Instagram::ACCESS_TOKEN
            ]));
    }

    /**
     * Performs the data validation.
     * @param Location $location location
     * @return Instagram Place Object.
     * @throws InvalidParamException if the current scenario is unknown.
     */
    public static function getTagsNameArray($name)
    {
        $array = [];
        $json = static::getTags($name);
        foreach($json->data as $key => $val){
            $array[] = $val->name;
        }

        return $array;
    }

    /**
     * Performs the data validation.
     * @param Location $location location
     * @return Instagram Place Object.
     * @throws InvalidParamException if the current scenario is unknown.
     */
    public static function getPostsByTag($tag)
    {
        return static::httpGet(str_replace("{tag}", urlencode($tag), instagram::MEDIA_FROM_TAG_URL) . "?" . http_build_query([
                "access_token" => Instagram::ACCESS_TOKEN
            ]));
    }

    /**
     * Performs the data validation.
     * @param Location $location location
     * @return Instagram Place Object.
     * @throws InvalidParamException if the current scenario is unknown.
     */
    public static function getPlaces($location)
    {
        return static::httpGet(instagram::LOCATION_URL . "?" . http_build_query([
                "lat" => $location->latitude,
                "lng" => $location->longitude,
                "access_token" => Instagram::ACCESS_TOKEN
            ]));
    }

    public static function savePosts($json)
    {
        foreach ($json->data as $key => $value) {
            $instance = static::findOne(['external_id' => $value->id]);
            $instance = $instance ? $instance : new static();
            $instance->attributes = $value;
            $instance->save();
        }

    }

    public function setAttributes($values)
    {
        foreach ($values as $key => $value) {
            if ($key == 'id') {
                $this->external_id = $value;
            } else if($key == "user") {
                $filename = basename($value->profile_picture, ".jpg").".png";
                $filepath = Url::to("/.resource/").$filename;
                if(!file_exists($filepath)) {
                    ImageHelper::createPingImg($value->profile_picture);
                }
                $this->{$key} = isset($value) ? json_encode($value) : null;
            } else if ($this->hasAttribute($key)) {
                $this->{$key} = isset($value) ? json_encode($value) : null;
            }
        }
    }

    public function getAttributes()
    {
        $attr =  parent::getAttributes();
        $attr['pingicon'] = $this->pingicon;

        return $attr;
    }

    public function setPingicon($value)
    {
        $this->_pingicon = $value;
    }

    public function getPingicon()
    {
        return $this->_pingicon;
    }

    public function afterFind()
    {
        foreach($this->attributes as $key => $value) {
            if(in_array($key, ['id', 'external_id', 'update']))continue;
            else if ($key == 'pingicon'){
                $this->pingicon = Url::to(['/.resource/'.basename($this->user->profile_picture, ".jpg").".png"], true);
            }
            else $this->{$key} = isset($value) ? json_decode($value) : null;
        }
        parent::afterFind();
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['pingicon'] = $this->pingicon;
        return $array;
    }


}
