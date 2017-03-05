<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\gii\child_model;

use ReflectionClass;
use Yii;
use yii\gii\CodeFile;
use yii\gii\generators\model\Generator;
use yii\helpers\Inflector;

/**
 * @inheritdoc
 */
class ChildGenerator extends Generator
{

    public function getName()
    {
        return 'Child Model Generator';
    }

    /**
     * @inheritdoc
     *
     * Updated to remove or change prefix depending on the prefix used. i.e Tbl to empty string.
     */
    protected function generateClassName($tableName, $useSchemaName = null)
    {
        if (isset($this->classNames[$tableName])) {
            return $this->classNames[$tableName];
        }

        $schemaName = '';
        $fullTableName = $tableName;
        if (($pos = strrpos($tableName, '.')) !== false) {
            if (($useSchemaName === null && $this->useSchemaName) || $useSchemaName) {
                $schemaName = substr($tableName, 0, $pos) . '_';
            }
            $tableName = substr($tableName, $pos + 1);
        }

        $db = $this->getDbConnection();
        $patterns = [];
        $patterns[] = "/^{$db->tablePrefix}(.*?)$/";
        $patterns[] = "/^(.*?){$db->tablePrefix}$/";
        if (strpos($this->tableName, '*') !== false) {
            $pattern = $this->tableName;
            if (($pos = strrpos($pattern, '.')) !== false) {
                $pattern = substr($pattern, $pos + 1);
            }
            $patterns[] = '/^' . str_replace('*', '(\w+)', $pattern) . '$/';
        }
        $className = $tableName;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $tableName, $matches)) {
                $className = $matches[1];
                break;
            }
        }

        /**
         * Remove the tbl prefix
         */
        $className = preg_replace(['/^tbl/'], [''], $className);

        return $this->classNames[$fullTableName] = Inflector::id2camel($schemaName . $className, '_');
    }

    /**
     * @inheritdoc
     *
     * Updated to remove or change prefix depending on the prefix used. i.e Tbl to empty string.
     */
    protected function generateRelationName($relations, $table, $key, $multiple)
    {
        if (!empty($key) && substr_compare($key, 'id', -2, 2, true) === 0 && strcasecmp($key, 'id')) {
            $key = rtrim(substr($key, 0, -2), '_');
        }
        if ($multiple) {
            $key = Inflector::pluralize($key);
        }
        $name = $rawName = Inflector::id2camel($key, '_');

        /**
         * Remove the tbl and elearning prefix
         */
        $name = $rawName = preg_replace(['/^Tbl/'], [''], $name);

        $i = 0;
        while (isset($table->columns[lcfirst($name)])) {
            $name = $rawName . ($i++);
        }
        while (isset($relations[$table->fullName][$name])) {
            $name = $rawName . ($i++);
        }

        return $name;
    }

    /**
     * Gets the shortname of the class.
     *
     * @param $className
     * @return string
     */
    public function getShortName($className)
    {
        $function = new \ReflectionClass($className);
        return $function->getShortName();
    }

}
