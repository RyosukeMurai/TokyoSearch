<?php

namespace app\modules\controllers;

use yii\web\Controller;
use Yii;
class DefaultController extends Controller
{
    public $layout = false;

    public function actionIndex(){
        exit('index');
    }

    protected function renderJsonpForJquery($object)
    {
        Yii::$app->response->clear();
        echo Yii::$app->request->get('callback').'('.json_encode($object).');';
        Yii::$app->end();
    }

}
