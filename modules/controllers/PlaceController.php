<?php
/**
 * Created by PhpStorm.
 * User: ryosuke
 * Date: 15/08/24
 * Time: 18:12
 */

namespace app\modules\controllers;

use app\models\Instagram;
use app\models\Location;
use app\models\Twitter;
use Yii;
class PlaceController extends DefaultController{

    public function actionIndex()
    {
        $location = new Location();
        $location->attributes = Yii::$app->request->get();
        $this->renderJsonpForJquery(Instagram::getPlaces($location));
    }

}