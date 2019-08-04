<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%item}}`.
 */
class m190630_092214_create_item_table
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%item}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(11)->unsigned()->notNull(),
            'firm' => $this->string(128),
            'model' => $this->string(128),
            'collection' => $this->string(64),
            'status' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer(12)->notNull()->defaultValue(time()),
            'updated_at' => $this->integer(12)->notNull()->defaultValue(time()),
        ]);
    
        $this->createTable('{{%item_description}}', [
            'item_id' => $this->integer()->notNull(),
            'small_text' => $this->text()->defaultValue(''),
            'small_list' => $this->text()->defaultValue(''),
            'text' => $this->text()->defaultValue(''),
            'list' => $this->text()->defaultValue(''),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(time()),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(time()),
        ]);
        
        $this->createTable('{{%item_color}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'code' => $this->string(64)->notNull(),
            'color' => $this->string(128),
            'status' => $this->boolean()->defaultValue(1),
        ]);
        
        $this->createTable('{{%item_color_size}}', [
            'id' => $this->primaryKey(),
            'color_id' => $this->integer()->notNull(),
            'size' => $this->string(32),
            'quantity' => $this->integer(11)->unsigned()->defaultValue(0),
            'price' => $this->integer(11)->unsigned()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
        ]);
    
        $this->createIndex('index-item_color-item_id',
                           '{{%item_color}}',
                           [ 'item_id', 'code' ],
                           true);
    
        $this->createIndex('index-item_color_size-color_id',
                           '{{%item_color_size}}',
                           [ 'color_id', 'size' ],
                           true);
    
        $this->createIndex('index-item_description-item_id',
                           '{{%item_description}}',
                           'item_id',
                           true);
    
        $this->addForeignKey(
            'fk_item_color',
            '{{%item_color}}',
            'item_id',
            '{{%item}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    
        $this->addForeignKey(
            'fk_item_description',
            '{{%item_description}}',
            'item_id',
            '{{%item}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    
        $this->addForeignKey(
            'fk_color_size',
            '{{%item_color_size}}',
            'color_id',
            '{{%item_color}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    
        $this->batchInsert('{{%item}}', [
            'category_id', 'firm', 'model', 'collection',
        ],
                           [
                               [ 4, '47 Brand', 'FRANCHISE DODGERS', '2018`SS' ],
                               [ 7, 'Saucony', 'JAZZ O QUILTED', '2017`SS' ],
                               [ 5, 'Funky Trunks', 'Pigs', '2018`SS' ],
                               [ 6, 'Funkita', 'Bears', '2019`SS' ],
                           ]
        );
    
        $this->batchInsert('{{%item_description}}', [
            'item_id', 'small_text', 'small_list', 'text', 'list',
        ],
                           [
                               [ 1, '', '', '', '' ],
                               [ 2, '', '', '', '' ],
                               [ 3, '', '', '', '' ],
                               [ 4, '', '', '', '' ],
                           ]
        );
        
        $this->batchInsert('{{%item_color}}', [
            'item_id', 'code', 'color',
        ],
                           [
                               [ 1, 'RY', 'Royal', ],
                               [ 2, '2s', 'Light Blue', ],
                               [ 2, '3s', 'Pink', ],
                               [ 3, 'YW', 'YellowWhite', ],
                               [ 4, 'R', 'Red', ],
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
                               [ 4, '9', 17, 2950 ],
                               [ 4, '9.5', 17, 2950 ],
                               [ 5, '10', 17, 2950 ],
                               [ 5, 'S', 17, 2950 ],
                               [ 5, 'M', 17, 2950 ],
                               [ 5, 'L', 17, 2950 ],
                               [ 5, 'XL', 17, 2950 ],
                           ]
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    
        $this->dropForeignKey('fk_color_size', '{{%item_color_size}}');
        $this->dropForeignKey('fk_item_description', '{{%item_description}}');
        $this->dropForeignKey('fk_item_color', '{{%item_color}}');
    
        $this->dropIndex('index-item_description-item_id', '{{%item_description}}');
        $this->dropIndex('index-item_color_size-color_id', '{{%item_color_size}}');
        $this->dropIndex('index-item_color-item_id', '{{%item_color}}');
        
        $this->dropTable('{{%item_color_size}}');
        $this->dropTable('{{%item_color}}');
        $this->dropTable('{{%item_description}}');
        $this->dropTable('{{%item}}');
    }
}
