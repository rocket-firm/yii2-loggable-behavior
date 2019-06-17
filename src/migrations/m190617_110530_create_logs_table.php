<?php

namespace rocketfirm\loggable\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%logs}}`.
 */
class m190617_110530_create_logs_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%logs}}', [
			'id' => $this->primaryKey(),
			'model_classname' => $this->string()->notNull()->comment('Класс модели'),
			'model_id' => $this->integer()->notNull()->comment('Id объекта'),
			'author_id' => $this->integer()->notNull()->comment('Пользователь'),
			'changed_attributes' => $this->text()->comment('Обновленные данные'),
			'message' => $this->text()->comment('Сообщение'),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP()')->comment('Дата создания'),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%logs}}');
	}
}
