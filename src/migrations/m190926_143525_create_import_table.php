<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%import}}`.
 */
class m190926_143525_create_import_table
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%import}}', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger(),
            'user_id' => $this->integer(),
            'params' => $this->text(),
            'result' => $this->text(),
            'created_at' => $this->integer(),
        
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%import}}');
    }
}
