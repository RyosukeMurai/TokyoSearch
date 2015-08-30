<?php
/**
 * Created by PhpStorm.
 * User: ryosuke
 * Date: 15/08/30
 * Time: 18:53
 */

namespace app\modules\controllers;


use app\components\ImageHelper;

class ImgController extends DefaultController {

    public function actionPing($src){
        $filename = basename($src);
        $filepath = Url::to(ImgController::PING_IMG_DIR).$filename;

        if(!file_exists($filepath)) {
            ImageHelper::createPingImg($src);
        }

        $path = Url::to(['/.resource/'.$filename], true);
        $this->renderJsonpForJquery(['src'=>$path]);
        \Yii::$app->end();
    }
}