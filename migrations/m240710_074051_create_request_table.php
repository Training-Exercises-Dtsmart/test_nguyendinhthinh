<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%request}}`.
 */
class m240710_074051_create_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%request}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'start_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
            'reason' => $this->string(),
            'status' => $this->integer(), //0:pending, 1:approved, 2:reject
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'deleted_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk_request_user', 'request', 'user_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_request_user', 'request');
        $this->dropTable('{{%request}}');
    }
}
