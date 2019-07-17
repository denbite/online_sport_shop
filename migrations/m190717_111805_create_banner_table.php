<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%banner}}`.
 */
class m190717_111805_create_banner_table
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%banner}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->string(),
            'link' => $this->string(),
            'link_title' => $this->string(),
            'status' => $this->boolean()->unsigned()->defaultValue(0),
            'publish_from' => $this->integer()->unsigned()->notNull()->defaultValue(time()),
            'publish_to' => $this->integer()->unsigned()->notNull()->defaultValue(time()),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(time()),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(time()),
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%banner}}');
    }
}
