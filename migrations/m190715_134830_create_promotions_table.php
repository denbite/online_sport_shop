<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%promotions}}`.
 */
class m190715_134830_create_promotions_table
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%promotion}}', [
            'id' => $this->primaryKey(),
            'type' => $this->boolean()->unsigned()->notNull(),
            'sale' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(\app\components\models\Status::STATUS_ACTIVE),
            'publish_from' => $this->integer()->unsigned()->notNull(),
            'publish_to' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ]);
        
        $this->createTable('{{%size_promotion}}', [
            'size_id' => $this->integer()->unsigned()->notNull(),
            'promotions_id' => $this->integer()->unsigned()->notNull(),
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%size_promotion}}');
        $this->dropTable('{{%promotion}}');
    }
}
