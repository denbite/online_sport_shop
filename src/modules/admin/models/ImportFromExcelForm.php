<?php

namespace app\modules\admin\models;

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "item".
 *
 * @property string $city
 * @property string $department
 * @property bool   $callBack
 * @property string $comment
 */
class ImportFromExcelForm
    extends Model
{
    
    public static $data = [];
    
    public static $reader;
    
    public $token;
    
    public $from;
    
    public $to;
    
    public $category_id;
    
    public function rules()
    {
        return [
            [ [ 'token', 'from', 'to', 'category_id' ], 'required', 'message' => 'Данное поле не может быть пустым' ],
            [ [ 'token' ], 'string' ],
            [ [ 'from', 'to', 'category_id' ], 'integer', 'min' => 1 ],
            [ [ 'to', ], 'compare', 'compareAttribute' => 'from', 'operator' => '>', 'type' => 'number' ],
            [ 'token', 'validateToken' ],
            [ [ 'data', 'reader' ], 'safe' ],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'token' => 'Пароль',
            'from' => 'Начало',
            'to' => 'Конец',
            'category_id' => 'Категория',
        ];
    }
    
    public function validateToken($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            
            if (!$user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Пароль введен неправильно');
            }
        }
    }
    
    public function setReader()
    {
        if (self::$reader === null) {
            self::$reader = new Xls();
            
            $filename = 'stock-' . Import::find()
                                         ->where([
                                                     'type' => Import::TYPE_UPLOAD_EXCEL,
                                                 ])
                                         ->filterWhere([
                                                           'like', 'result', 's:4:"code";i:' . Import::RESULT_CODE_OK,
                                                       ])
                                         ->orderBy([
                                                       'created_at' => SORT_DESC,
                                                   ])
                                         ->one()
                    ->created_at . '.xls';
            
            $path = Import::getStockBasePath() . $filename;
            
            if (file_exists($path) and self::$reader->canRead($path)) {
                return true;
            }
            
        }
        
        return false;
    }
}