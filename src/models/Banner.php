<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "banner".
 *
 * @property int    $id
 * @property string $title
 * @property string $description
 * @property string $link
 * @property string $link_title
 * @property int    $status
 * @property int    $publish_from
 * @property int    $publish_to
 * @property int    $updated_at
 * @property int    $created_at
 */
class Banner
    extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banner';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'status' ], 'integer' ],
            [ [ 'title', 'description', 'link', 'link_title' ], 'string', 'max' => 255 ],
            [ [ 'publish_from', 'publish_to' ], 'safe' ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'link' => 'Link',
            'link_title' => 'Link Title',
            'status' => 'Status',
            'publish_from' => 'Publish From',
            'publish_to' => 'Publish To',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            $this->publish_from = strtotime($this->publish_from);
            $this->publish_to = strtotime($this->publish_to);
            
            if ($this->publish_from <= $this->publish_to) {
                return true;
            }
        }
        
        return false;
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function getImage()
    {
        return $this->hasOne(Image::className(), [ 'subject_id' => 'id' ])
                    ->andWhere([ 'sort' => 0, 'type' => Image::TYPE_BANNER ]);
    }
}
