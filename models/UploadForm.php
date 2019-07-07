<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;

class UploadForm
    extends Model
{
    
    public $images;
    
    private $_params;
    
    public function __construct(array $config = [])
    {
        if (is_array($config)) {
            $this->_params = $config;
        }
    }
    
    public function rules()
    {
        return [
            [ [ 'images' ], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10 ],
        ];
    }
    
    /**
     * Сохраняет файл на сервере и добавляет его в базу для дальнейшего доступа
     * @return bool
     */
    public function uploadItemImages()
    {
        
        if ($this->validate()) {
            // get type
            $type = !empty($this->_params['type']) ? $this->_params['type'] : null;
            // get subject_id
            $subject_id = !empty($this->_params['subject_id']) ? $this->_params['subject_id'] : null;
            // get available types
            $classes = Image::getTypes();
            // validation input data
            if (!empty($this->images) and !empty($type) and !empty($subject_id) and array_key_exists($type,
                                                                                                     $classes)) {
                foreach ($this->images as $image) {
                    
                    $mdl = new Image();
                    
                    // generate filename
                    $mdl->url = Yii::$app->security->generateRandomString(16) . '_' . time() . '.' . $image->extension;
                    $mdl->type = $type;
                    $mdl->subject_id = $subject_id;
                    // create path to save image
                    $path = Yii::getAlias('@webroot') . $mdl->getFilePath();
                    // check if path exists
                    if (!file_exists($path)) {
                        // if not -> create
                        FileHelper::createDirectory($path, 0777);
                    }
                    // save file and model
                    if (!$mdl->validate() or !$image->saveAs($path . $mdl->url) or !$mdl->save(false)) {
                        return false;
                    }
                    
                    unset($mdl);
                }
                
                return true;
            }
        }
        
        return false;
    }
}