<?php
/**
 * AllowedFiltersBehavior class file.
 */
namespace app\behaviors;

use yii\base\Behavior;
use yii\db\ActiveQuery;
use yii\web\HttpException;

class AllowedFiltersBehavior extends Behavior
{

    /**
     * Extracts the name / values for passing into the model.
     *
     * @param array $params
     * @return array
     * @throws HttpException
     */
    public function extractFieldValues($params)
    {
        $filteredParams = [];
        if ($params) {
            foreach ($params as $modelName => $data) {
                $filteredParams[$modelName] = [];
                foreach ($data as $key => $field) {
                    if (!isset($field['name'])) {
                        throw new HttpException(500, 'all filters must have a name.');
                    }
                    if (!isset($field['value'])) {
                        throw new HttpException(500, 'all filters must have a value.');
                    }
                    $filteredParams[$modelName][$field['name']] = $field['value'];
                }
            }
        }
        return $filteredParams;
    }

    /**
     * Filters a list of fields.
     *
     * @param ActiveQuery $query
     * @param array $params
     * @throws HttpException
     */
    public function addAllowedFilters($query, $params, $alias=null)
    {
        if (property_exists($this->owner, 'allowedFilteredFields') && is_array($params)) {
            $fields = array_keys($this->owner->allowedFilteredFields);
            $fieldValues = $this->owner->allowedFilteredFields;
            if ($params) {
                foreach ($params as $key => $field) {

                    /*
                     * If there are ignored filters skip.
                     */
                    if (property_exists($this->owner, 'ignoreFilteredFields')) {
                        if (in_array($field['name'], $this->owner->ignoreFilteredFields)) {
                            continue;
                        }
                    }

                    if (!isset($field['operator'])) {
                        $field['operator'] = '=';
                    }
                    if (in_array($field['name'], $fields)) {
                        if (is_array($fieldValues[$field['name']])) {
                            if (in_array($field['operator'], $fieldValues[$field['name']])) {
                                $query->andFilterWhere([$field['operator'], $alias? $alias.'.'.$field['name'] : $field['name'], $field['value']]);
                            }
                        } elseif ($fieldValues[$field['name']] == '*') {
                            $query->andFilterWhere([$field['operator'], $alias? $alias.'.'.$field['name'] : $field['name'], $field['value']]);
                        }
                    }
                }
            }
        }
    }
}
