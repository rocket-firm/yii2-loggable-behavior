<?php

namespace rocketfirm\loggable\models;

use yii\redis\ActiveRecord;

class LogRedis extends ActiveRecord
{
    public function attributes()
    {
        return ['id', 'model_classname', 'model_id', 'author_id', 'changed_attributes', 'message', 'created_at'];
    }

    public function rules()
    {
        return [
            [['model_classname', 'model_id', 'author_id'], 'required'],
            [['changed_attributes', 'message'], 'string'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Пользователь',
            'changed_attributes' => 'Обновленные данные',
            'message' => 'Сообщение',
            'created_at' => 'Дата создания',
        ];
    }

    public static function find()
    {
        return new LogRedisQuery(get_called_class());
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }
}