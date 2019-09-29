<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "import".
 *
 * @property int    $id
 * @property int    $type
 * @property int    $user_id
 * @property string $params
 * @property string $result
 * @property int    $created_at
 */
class Import
    extends \yii\db\ActiveRecord
{
    
    const TYPE_UPLOAD_EXCEL = 1;
    
    const TYPE_IMPORT_FROM_EXCEL = 2;
    
    const RESULT_CODE_OK = 1;
    
    const RESULT_CODE_ERROR = 2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'import';
    }
    
    public static function getTypes()
    {
        return [
            self::TYPE_UPLOAD_EXCEL => 'Загрузка Excel',
            self::TYPE_IMPORT_FROM_EXCEL => 'Загрузка Excel',
        ];
    }
    
    public static function getTypesCssClass()
    {
        return [
            self::TYPE_UPLOAD_EXCEL => 'success',
            self::TYPE_IMPORT_FROM_EXCEL => 'info',
        ];
    }
    
    public static function getResultCodes()
    {
        return [
            self::RESULT_CODE_OK => 'OK',
            self::RESULT_CODE_ERROR => 'Error',
        ];
    }
    
    public static function getResultCodesCssClass()
    {
        return [
            self::RESULT_CODE_OK => 'success',
            self::RESULT_CODE_ERROR => 'danger',
        ];
    }
    
    public static function getStockBasePath()
    {
        return Yii::getAlias('@webroot') . '/files/stock/';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'type', 'user_id', 'created_at' ], 'required' ],
            [ [ 'type', 'user_id', 'created_at' ], 'integer' ],
            [ [ 'params', 'result' ], 'string' ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
            'user_id' => 'Пользователь',
            'params' => 'Параметры',
            'result' => 'Результат',
            'created_at' => 'Дата создания',
        ];
    }
}
