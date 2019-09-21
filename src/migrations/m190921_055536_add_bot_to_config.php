<?php

use yii\db\Migration;

/**
 * Class m190921_055536_add_bot_to_config
 */
class m190921_055536_add_bot_to_config
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%config}}', [
            'group' => 'Ключи',
            'name' => 'telegram_bot_token',
            'value' => '880300477:AAGpQg9-AtluPiI83DxuNlvdldO9_TZOQxs',
            'label' => 'Токен для Телеграм бота @AqusitaShop',
        ]);
        
        $this->insert('{{%config}}', [
            'group' => 'Ключи',
            'name' => 'telegram_admin_id',
            'value' => '309168436',
            'label' => 'Telegram id @denbite',
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%config}}', [ 'name' => 'telegram_admin_id' ]);
        $this->delete('{{%config}}', [ 'name' => 'telegram_bot_token' ]);
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190921_055536_add_bot_to_config cannot be reverted.\n";

        return false;
    }
    */
}
