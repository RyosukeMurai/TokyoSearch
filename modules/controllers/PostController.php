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
class PostController extends DefaultController
{

    public function actionIndex($location_id = null)
    {
        $instagram = new Instagram();
        $this->renderJsonpForJquery($instagram->getPosts(['location_id' => $location_id]));
    }

    public function actionPostsByLocation()
    {
        $location = new Location();
        $location->attributes = Yii::$app->request->get();
        $instagram = new Instagram();
        $this->renderJsonpForJquery($instagram->getPostsByLocation($location));
    }
}