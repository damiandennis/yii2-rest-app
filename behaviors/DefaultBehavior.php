<?php

namespace app\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class DefaultBehavior extends Behavior
{

    public $defaults = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return ArrayHelper::merge(parent::events(), [
            ActiveRecord::EVENT_BEFORE_VALIDATE => function ($event) {
                foreach ($this->defaults as $attribute => $closure) {
                    if ($this->owner->isNewRecord) {
                        $this->defaults[$attribute]->bindTo($this->owner);
                        $this->defaults[$attribute]();
                    }
                }
            }
        ]);
    }
}