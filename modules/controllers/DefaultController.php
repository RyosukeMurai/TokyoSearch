<?php

namespace app\modules\controllers;

use yii\base\Model;
use yii\web\Controller;
use Yii;
class DefaultController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;
    public function actionIndex(){
        exit('index');
    }

    protected function renderJsonpForJquery($object)
    {
        if($object instanceof Model){
            $object = $object->toArray();
        } else if(is_array($object)){
            foreach($object as $key => $value){
                if($value instanceof Model){
                    $object[$key] = $value->toArray();
                }
            }
        }

        Yii::$app->response->clear();
        echo Yii::$app->request->get('callback').'('.json_encode($object).');';
        Yii::$app->end();
    }

}
