<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%item}}`.
 */
class m190630_092214_create_item_table extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%item}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(11)->notNull(),
            'firm' => $this->string(128),
            'model' => $this->string(128),
            'collection' => $this->string(64),
            'code' => $this->string(64)->unique()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer(12)->notNull()->defaultValue(time()),
            'updated_at' => $this->integer(12)->notNull()->defaultValue(time()),
        ]);
        
        $this->createTable('{{%item_color}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer(11)->notNull(),
            'code' => $this->string(32)->notNull(),
            'color' => $this->string(128),
            'status' => $this->boolean()->defaultValue(1),
        ]);
        
        $this->createTable('{{%item_color_size}}', [
            'id' => $this->primaryKey(),
            'color_id' => $this->integer(11)->notNull(),
            'size' => $this->string(32),
            'quantity' => $this->integer(11)->unsigned()->defaultValue(0),
            'price' => $this->integer(11)->unsigned()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
        ]);
        
        $this->batchInsert('{{%item}}', [
            'category_id', 'firm', 'model', 'collection', 'code',
        ],
                           [
                               [ 2, '47 Brand', 'FRANCHISE DODGERS', '2018`SS', 'FRANC12RPF' ],
                               [ 4, 'Saucony', 'JAZZ O QUILTED', '2017`SS', '60295' ],
                           ]
        );
        
        $this->batchInsert('{{%item_color}}', [
            'item_id', 'code', 'color',
        ],
                           [
                               [ 1, 'RY', 'Royal', ],
                               [ 2, '2s', 'Light Blue', ],
                               [ 2, '3s', 'Pink', ],
                           ]
        );
        
        $this->batchInsert('{{%item_color_size}}', [
            'color_id', 'size', 'quantity', 'price',
        ],
                           [
                               [ 1, 'M', 1, 1050 ],
                               [ 1, 'L', 11, 1050 ],
                               [ 1, 'XL', 16, 1050 ],
                               [ 2, '8.5', 0, 2950 ],
                               [ 2, '9', 7, 2950 ],
                               [ 2, '9.5', 3, 2950 ],
                               [ 3, '8.5', 26, 2950 ],
                               [ 3, '9', 17, 2950 ],
                           ]
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%item_color_size}}');
        $this->dropTable('{{%item_color}}');
        $this->dropTable('{{%item}}');
    }
}
