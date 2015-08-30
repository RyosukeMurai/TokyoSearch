<?php
/**
 * Created by PhpStorm.
 * User: ryosuke
 * Date: 15/08/25
 * Time: 22:11
 */

namespace app\modules\controllers;


use app\models\User;

class UserController extends DefaultController{

    public function actionSignup()
    {
        $user = new User(['scenario' => 'signup']);

        if($user->load(\Yii::$app->request->post(), '') && $user->save()){
            $this->renderJsonpForJquery(['result'=>'1']);
        } else {
            $this->renderJsonpForJquery($user->errors);
        }
    }
}