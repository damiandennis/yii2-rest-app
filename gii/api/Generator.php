<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\gii\api;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * This generator will generate a controller and one or a few action view files.
 *
 * @property array $actionIDs An array of action IDs entered by the user. This property is read-only.
 * @property string $controllerFile The controller class file path. This property is read-only.
 * @property string $controllerID The controller ID. This property is read-only.
 * @property string $controllerNamespace The namespace of the controller class. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\Generator
{
    /**
     * @var string the controller class name
     */
    public $controllerClass;

    /**
     * @var string the model used for the endpoint.
     */
    public $modelClass;


    /**
     * @var string the search model used for the endpoint.
     */
    public $searchClass;

    /**
     * @var string the base class of the controller
     */
    public $baseClass = 'app\modules\api\v1\controllers\base\ActiveController';


    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'API Controller Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator helps you to quickly generate a new controller class with
            one or several controller actions and their corresponding views.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['controllerClass', 'modelClass', 'baseClass', 'searchClass'], 'filter', 'filter' => 'trim'],
            [['controllerClass', 'baseClass', 'modelClass', 'searchClass'], 'required'],
            ['controllerClass', 'match', 'pattern' => '/^[\w\\\\]*Controller$/', 'message' => 'Only word characters and backslashes are allowed, and the class name must end with "Controller".'],
            ['controllerClass', 'validateNewClass'],
            ['baseClass', 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'baseClass' => 'Base Class',
            'controllerClass' => 'Controller Class',
            'modelClass' => 'Model Class',
            'searchClass' => 'Search Class'
        ];
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return [
            'controller.php',
            'endpoints.php'
        ];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return ['baseClass'];
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return [
            'controllerClass' => 'This is the name of the controller class to be generated. You should
                provide a fully qualified namespaced class (e.g. <code>app\controllers\PostController</code>),
                and class name should be in CamelCase ending with the word <code>Controller</code>. Make sure the class
                is using the same namespace as specified by your application\'s controllerNamespace property.',
            'modelClass' => 'This is the model that will be used in the controller',
            'searchClass' => 'This is the search model that will be used in the controller',
            'baseClass' => 'This is the class that the new controller class will extend from. Please make sure the class exists and can be autoloaded.',
        ];
    }

    /**
     * @inheritdoc
     */
    public function successMessage()
    {
        return "The controller has been generated successfully.";
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];

        $files[] = new CodeFile(
            $this->getControllerFile(),
            $this->render('controller.php', ['modelClass' => $this->modelClass, 'searchClass' => $this->searchClass])
        );
        $files[] = new CodeFile(
            $this->getEndpointFile(),
            $this->render('endpoints.php', ['endpoints' => $this->getEndpoints()])
        );

        return $files;
    }

    /**
     * @return string the controller class file path
     */
    public function getControllerFile()
    {
        return Yii::getAlias('@' . str_replace('\\', '/', $this->controllerClass)) . '.php';
    }

    /**
     * @return array|mixed
     */
    public function getEndpoints()
    {
        $endpoints = [];
        if (file_exists($this->getEndpointFile())) {
            $filePath = $this->getEndpointFile();
            $endpoints = require($filePath);
        }
        $endpoint = str_replace(['\\', 'app/modules/', '/controllers'], ['/', '', ''], $this->getControllerNamespace()) . '/' . $this->getControllerID();
        if (!in_array($endpoint, $endpoints)) {
            $endpoints[] = $endpoint;
        }
        return $endpoints;
    }

    /**
     * @return bool|string
     */
    public function getEndpointFile()
    {
        return Yii::getAlias("@app/config/endpoints.php");
    }

    /**
     * @return string the controller ID
     */
    public function getControllerID()
    {
        $name = StringHelper::basename($this->controllerClass);
        return Inflector::camel2id(substr($name, 0, strlen($name) - 10));
    }

    /**
     * @return string the namespace of the controller class
     */
    public function getControllerNamespace()
    {
        $name = StringHelper::basename($this->controllerClass);
        return ltrim(substr($this->controllerClass, 0, - (strlen($name) + 1)), '\\');
    }
}
