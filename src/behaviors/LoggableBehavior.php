<?php

namespace rocketfirm\loggable\behaviors;

use rocketfirm\loggable\models\LogRedis;
use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\Json;

class LoggableBehavior extends Behavior
{
    /**
     * @var array list of drivers
     *
     * ```php
     * [
     *     'drivers' => ['mysql', 'redis']
     * ]
     * ```
     */
    public $drivers = ['mysql'];

    public $skipAttributes = ['updated_at'];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->drivers = (array)$this->drivers;
        $this->skipAttributes = (array)$this->skipAttributes;
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'touch',
            ActiveRecord::EVENT_AFTER_UPDATE => 'touch'
        ];
    }

    /**
     * @param $event Event
     */
    public function touch($event)
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        $log = new LogRedis();

        $changedAttributes = [];
        if (!empty($event->changedAttributes) && ($event->name === ActiveRecord::EVENT_AFTER_UPDATE)) {
            foreach ($event->changedAttributes as $attribute => $value) {
                if (in_array($attribute, $this->skipAttributes)) {
                    continue;
                }

                $changedAttributes[$attribute] = [
                    'old' => $value,
                    'new' => $owner->{$attribute},
                ];
            }
        }

        $log->attributes = [
            'model_classname' => get_class($owner),
            'model_id' => $owner->getPrimaryKey(),
            'author_id' => Yii::$app->user->id,
            'changed_attributes' => Json::encode($changedAttributes),
            'message' => 'Операция: ' . ($event->name === ActiveRecord::EVENT_AFTER_INSERT ? 'Создание' : 'Обновление'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $log->save();
    }
}