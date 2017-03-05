<?php

namespace app\modules\api\v1\controllers\base;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Json;
use yii\web\HttpException;

class ActiveController extends \yii\rest\ActiveController
{

    public $searchClass = null;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBasicAuth::className(),
                HttpBearerAuth::className()
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        if ($this->searchClass !== null) {
            $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        }
        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function prepareDataProvider()
    {
        if (!class_exists($this->searchClass)) {
            throw new HttpException(500, 'search class does not exist.');
        }

        $class = new \ReflectionClass($this->searchClass);

        $params = [];
        if (isset(\Yii::$app->request->queryParams['filters'])) {
            $filters = Json::decode(\Yii::$app->request->queryParams['filters']);
            foreach ($filters as $key => $filter) {
                $params[$class->getShortName()][] = $filter;
            }
        }

        $searchModel = new $this->searchClass;
        return $searchModel->search($params);
    }
}