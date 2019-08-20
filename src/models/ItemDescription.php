<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "item_description".
 *
 * @property int    $id
 * @property int    $item_id
 * @property string $small_text
 * @property string $small_list
 * @property string $text
 * @property string $list serialized array
 * @property int    $updated_at
 * @property int    $created_at
 */
class ItemDescription
    extends \yii\db\ActiveRecord
{
    
    const ITEMS_SEPARATOR = ';';
    
    const PARTS_SEPARATOR = ':';
    
    public $small_list_array;
    
    public $list_array;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_description';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'item_id' ], 'required' ],
            [ [ 'item_id' ], 'integer' ],
            [ [ 'item_id' ], 'unique' ],
            [ [ 'small_text', 'small_list', 'text', 'list' ], 'string' ],
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Товар',
            'small_text' => 'Краткое описание',
            'small_list' => 'Краткий список',
            'small_list_array' => 'Преимущества',
            'text' => 'Описание',
            'list' => 'Таблица характеристик',
            'updated_at' => 'Дата обновления',
            'created_at' => 'Дата создания',
        ];
    }
    
    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            if (!empty($data[$this->formName()]['small_list_array']) and $list = $data[$this->formName()]['small_list_array'] and is_array($list)) {
                foreach ($list as $index => $one) {
                    if (empty($one)) {
                        unset($list[$index]);
                    }
                }
                $this->small_list = implode(self::ITEMS_SEPARATOR, $list);
                unset($list);
            }
            
            if (!empty($data[$this->formName()]['list_array']) and $list = $data[$this->formName()]['list_array'] and is_array($list) and key_exists('key',
                                                                                                                                                     $list) and key_exists('value',
                                                                                                                                                                           $list) and count($list['key']) == count($list['value'])) {
                for ($i = 0; $i < count($list['key']); $i++) {
                    $list['result'][] = implode(self::PARTS_SEPARATOR,
                                                [ $list['key'][$i], $list['value'][$i] ]);
                }
                
                $this->list = implode(self::ITEMS_SEPARATOR, $list['result']);
                unset($list);
            }
            
            return true;
        }
        
        return false;
    }
}
