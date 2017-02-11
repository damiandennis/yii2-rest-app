<?php

namespace app\modules\api\v1\controllers;

use yii\rest\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        return [
            'success' => true
        ];
    }
}