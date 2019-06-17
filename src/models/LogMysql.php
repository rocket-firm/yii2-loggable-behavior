<?php

namespace rocketfirm\loggable\models;

use yii\db\ActiveRecord;
use app\models\User;

class LogMysql extends ActiveRecord
{
	public static function tableName()
	{
		return 'logs';
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
		return new LogMysqlQuery(get_called_class());
	}

	public function getAuthor()
	{
		return $this->hasOne(User::className(), ['id' => 'author_id']);
	}


}