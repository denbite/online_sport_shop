<?php

use yii\db\Migration;

/**
 * Class m190709_151714_add_settings_to_items
 */
class m190709_151714_add_settings_to_items
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%item_color}}', 'html', $this->string(6)->after('color')->defaultValue('FFFFFF'));
        
        $this->createTable('{{%item_description}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->unsigned()->notNull(),
            'small_text' => $this->string(255),
            'small_list' => $this->string(255),
            'text' => $this->string(),
            'list' => $this->string()->comment('serialized array'),
            'updated_at' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%item_description}}');
        
        $this->dropColumn('{{%item_color}}}', 'html');
    }
}
