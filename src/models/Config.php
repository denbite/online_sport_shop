<?php

namespace app\models;

/**
 * This is the model class for table "config".
 *
 * @property string $name
 * @property string $value
 * @property string $label
 */
class Config
    extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'name' ], 'required' ],
            [ [ 'name' ], 'string', 'max' => 64 ],
            [ [ 'value', 'label' ], 'string', 'max' => 255 ],
            [ [ 'name' ], 'unique' ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'value' => 'Value',
            'label' => 'Label',
        ];
    }
}
