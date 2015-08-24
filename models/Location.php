<?php
/**
 * Created by PhpStorm.
 * User: ryosuke
 * Date: 15/08/24
 * Time: 20:55
 */

namespace app\models;


use yii\base\Model;

class Location extends Model {

    public $latitude, $longitude, $altitude, $accuracy, $altitudeAccuracy, $heading, $speed;

    public function rules(){
        return [
            [['latitude','longitude','altitude','accuracy','altitudeAccuracy','heading','speed'], 'safe']
        ];
    }

}