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
        $this->addColumn('{{%item_color}}', 'html', $this->string(7)->after('color')->defaultValue('#000000'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%item_color}}', 'html');
    }
}
