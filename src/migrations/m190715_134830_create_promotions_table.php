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
    
        $this->createTable('{{%promotion_item}}', [
            'item_id' => $this->integer()->notNull(),
            'promotion_id' => $this->integer()->unsigned()->notNull(),
        ]);
    
        $this->createIndex('index-item_promotion-item_id',
                           '{{%promotion_item}}',
                           [ 'item_id', 'promotion_id' ],
                           true);
    
        $this->addForeignKey(
            'fk_item_promotion',
            '{{%promotion_item}}',
            'item_id',
            '{{%item}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_item_promotion', '{{%promotion_item}}');
    
        $this->dropIndex('index-item_promotion-item_id', '{{%promotion_item}}');
    
        $this->dropTable('{{%promotion_item}}');
        $this->dropTable('{{%promotion}}');
    }
}
