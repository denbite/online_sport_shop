<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image}}`.
 */
class m190703_201556_create_image_table
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'type' => $this->boolean()->notNull(),
            'subject_id' => $this->integer()->notNull(),
            'url' => $this->string()->notNull(),
            'sort' => $this->smallInteger()->unsigned()->defaultValue(0),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%image}}');
    }
}
