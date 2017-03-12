<?php
/**
 * Created by PhpStorm.
 * User: damian
 * Date: 4/06/15
 * Time: 1:00 PM
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\Inflector;

class ModelGenController extends Controller
{
    public $excludeList = [];

    /**
     * Generates base classes for all tables unless table is not null.
     *
     * @param null|string|array $table If not null then a single/array of model/s will be generated.
     */
    public function actionIndex($table = null)
    {
        $connection = Yii::$app->db;//get connection
        $dbSchema = $connection->schema;
        if ($table === null) {
            $tables = $dbSchema->getTableNames();
        } else {
            if (!is_array($table)) {
                $tables = [$table];
            } else {
                $tables = $table;
            }
        }
        foreach($tables as $tbl) {
            // If excluded then dont generate.
            if (in_array($tbl, $this->excludeList)) {
                continue;
            }

            $modelName = str_replace(' ', '', ucwords(str_replace(['_'], [' '], $tbl)));

            if ($modelName !== null) {
                $this->stdout("generating {$modelName} for table {$tbl}..\n", Console::FG_GREEN);
                $command = dirname(__FILE__).'/../yii gii/base_model' .
                    " --tableName={$tbl}" .
                    " --modelClass={$modelName}" .
                    " --ns=app\\\\models" .
                    " --interactive=0" .
                    " --enableI18N=1" .
                    " --overwrite=1" .
                    " --useTablePrefix=1" .
                    " --baseClass=\\\\yii\\\\db\\\\ActiveRecord";

                $failed = exec($command, $output);
                array_map(function($row) {
                    $this->stdout($row . "\n");
                }, $output);
                if ($failed) {
                    $this->stdout("failed to generate {$modelName} for table {$tbl}..\n", Console::FG_RED);
                }
            }
        }
    }

    /**
     * Generates child classes for all tables unless table is not null.
     *
     * @param null|string|array $table If not null then a single/array of model/s will be generated.
     */
    public function actionChildClasses($table = null)
    {
        $connection = Yii::$app->db;//get connection
        $dbSchema = $connection->schema;
        if ($table === null) {
            $tables = $dbSchema->getTableNames();
        } else {
            if (!is_array($table)) {
                $tables = [$table];
            } else {
                $tables = $table;
            }
        }
        foreach($tables as $tbl) {
            // If excluded then dont generate.
            if (in_array($tbl, $this->excludeList)) {
                continue;
            }

            $modelName = str_replace(' ', '', ucwords(str_replace(['_'], [' '], $tbl)));

            if ($modelName !== null) {
                $baseClass = "app\\\\models\\\\{$modelName}";
                $this->stdout("generating {$modelName} for table {$tbl}..\n", Console::FG_GREEN);

                $command = dirname(__FILE__).'/../yii gii/child_model' .
                    " --tableName={$tbl}" .
                    " --modelClass={$modelName}" .
                    " --ns=app\\\\modules\\\\api\\\\v1\\\\models" .
                    " --interactive=0" .
                    " --enableI18N=1" .
                    " --overwrite=1" .
                    " --useTablePrefix=1" .
                    " --baseClass={$baseClass}";

                $failed = exec($command, $output);
                array_map(function($row) {
                    $this->stdout($row . "\n");
                }, $output);
                if ($failed) {
                    $this->stdout("failed to generate {$modelName} for table {$tbl}..\n", Console::FG_RED);
                }
            }
        }
    }

    public function actionSearch($table = null)
    {
        $connection = Yii::$app->db;//get connection
        $dbSchema = $connection->schema;
        if ($table === null) {
            $tables = $dbSchema->getTableNames();
        } else {
            if (!is_array($table)) {
                $tables = [$table];
            } else {
                $tables = $table;
            }
        }
        foreach($tables as $tbl) {
            // If excluded then dont generate.
            if (in_array($tbl, $this->excludeList)) {
                continue;
            }

            $modelName = Inflector::camelize(str_replace(['tbl_'], [''], $tbl));

            if ($modelName !== null) {
                $this->stdout("generating {$modelName}Search for model {$modelName}..\n", Console::FG_GREEN);

                $command = dirname(__FILE__).'/../yii gii/search' .
                    ' --modelClass=app\\\\modules\\\\api\\\\v1\\\\models\\\\' . $modelName .
                    ' --searchModelClass=app\\\\modules\\\\api\\\\v1\\\\models\\\\search\\\\' . $modelName . 'Search' .
                    ' --overwrite=1' .
                    ' --interactive=0';

                $failed = exec($command, $output);
                array_map(function($row) {
                    $this->stdout($row . "\n");
                }, $output);
                if ($failed) {
                    $this->stdout("failed to generate {$modelName}Search for model {$modelName}..\n", Console::FG_RED);
                }
            }
        }
    }

    public function actionController($table = null)
    {
        $connection = Yii::$app->db;//get connection
        $dbSchema = $connection->schema;
        if ($table === null) {
            $tables = $dbSchema->getTableNames();
        } else {
            if (!is_array($table)) {
                $tables = [$table];
            } else {
                $tables = $table;
            }
        }
        foreach($tables as $tbl) {
            // If excluded then dont generate.
            if (in_array($tbl, $this->excludeList)) {
                continue;
            }

            $modelName = str_replace(' ', '', ucwords(str_replace(['tbl', '_'], [' '], $tbl)));

            if ($modelName !== null) {
                $this->stdout("generating {$modelName} Controller for model {$modelName}..\n", Console::FG_GREEN);

                $command = dirname(__FILE__).'/../yii gii/api' .
                    ' --controllerClass=app\\\\modules\\\\api\\\\v1\\\\controllers\\\\' . Inflector::pluralize($modelName) . 'Controller' .
                    ' --modelClass=app\\\\modules\\\\api\\\\v1\\\\models\\\\' . $modelName .
                    ' --searchClass=app\\\\modules\\\\api\\\\v1\\\\models\\\\search\\\\' . $modelName . 'Search' .
                    ' --overwrite=1' .
                    ' --interactive=0';

                $failed = exec($command, $output);
                array_map(function($row) {
                    $this->stdout($row . "\n");
                }, $output);
                if ($failed) {
                    $this->stdout("failed to generate {$modelName}Search for model {$modelName}..\n", Console::FG_RED);
                }
            }

        }
    }

    /**
     * Regenerates all models.
     * @param null $table
     */
    public function actionModel($table = null)
    {
        $this->actionIndex($table);
        $this->actionChildClasses($table);
    }

    /**
     * Generates base and child classes for all tables unless table is not null.
     *
     * @param null|string|array $table If not null then a single/array of model/s will be generated.
     */
    public function actionApi($table = null)
    {
        $this->actionIndex($table);
        $this->actionChildClasses($table);
        $this->actionSearch($table);
        $this->actionController($table);
    }
}
