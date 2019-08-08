<?php

use yii\db\Migration;

/**
 * Class m190730_114657_add_config_nova_poshta
 */
class m190730_114657_add_config_nova_poshta
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%config}}', [
            'group' => 'Ключи',
            'name' => 'nova_poshta_api_key',
            'value' => 'c6b5e8342f1ebeaf6986be7836a2516b',
            'label' => 'Нова Пошта API key',
        ]);
        
        $this->insert('{{%config}}', [
            'group' => 'Ключи',
            'name' => 'instagram_access_token',
            'value' => '16644581087.28c0b1e.199ddf1312384a5a98d4f6e69d2c06e6',
            'label' => 'Instagram API Access Token',
        ]);
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%config}}', [ 'name' => 'instagram_access_token' ]);
        $this->delete('{{%config}}', [ 'name' => 'nova_poshta_api_key' ]);
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190730_114657_add_config_nova_poshta cannot be reverted.\n";

        return false;
    }
    */
}
