<?php

namespace app\behaviors;

use yii\behaviors\TimestampBehavior as BaseTimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\validators\RequiredValidator;

class TimestampBehavior extends BaseTimestampBehavior
{
    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return time() * 1000;
        }
        return parent::getValue($event);

    }

    public function events()
    {
        return ArrayHelper::merge(parent::events(), [
            ActiveRecord::EVENT_BEFORE_VALIDATE => function ($modelEvent) {
                $rules = $this->owner->getValidators();
                foreach ($rules as $key => $rule) {
                    if ($rule instanceof RequiredValidator) {
                        foreach ($this->attributes as $event => $attributes) {
                            switch ($event) {
                                case 'beforeInsert':
                                    $rule->attributes = array_filter($rule->attributes, function ($attribute) use ($attributes) {
                                        if (in_array($attribute, $attributes)) {
                                            return false;
                                        }
                                        return true;
                                    });
                                    break;
                            }
                        }
                    }
                }

            }
        ]);
    }
}
