<?php

namespace app\modules\api\v1\controllers;
use app\modules\api\v1\models\User;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class UsersController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className()
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['my-account'],
                    'allow' => true,
                ],
                [
                    'allow' => false
                ]
            ]
        ];
        return $behaviors;
    }

    public function actionMyAccount()
    {
        if (!\Yii::$app->user->isGuest) {
            return User::findIdentity(\Yii::$app->user->id);
        }
    }

}