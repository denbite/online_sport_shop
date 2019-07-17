<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class UploadForm
    extends Model
{
    
    public $images;
    
    public $image;
    
    private $_type;
    
    private $_subject_id;
    
    public function __construct($type = null, $subject_id = null)
    {
        if ($type and $subject_id) {
            // add validation
            $this->_type = $type;
            $this->_subject_id = $subject_id;
        }
    
        return $this;
    }
    
    public function rules()
    {
        return [
            [ [ 'images' ], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10 ],
            [ [ 'image' ], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg' ],
        ];
    }
    
    public function setType($type)
    {
        if ($this->_type === null or array_key_exists($type, Image::getTypes())) {
            $this->_type = $type;
        }
    }
    
    public function setSubject($subject)
    {
        if ($this->_subject_id === null) {
            $this->_subject_id = (int) $subject;
        }
    }
    
    /**
     * Сохраняет файл на сервере и добавляет его в базу для дальнейшего доступа
     * @return bool
     */
    public function uploadImages()
    {
        // validation input data
        if (!empty($this->images) and !empty($this->_type) and !empty($this->_subject_id)) {
            foreach ($this->images as $index => $this->image) {
                if (!$this->uploadImage()) {
                    return false;
                }
            }
        
            return true;
        }
    
        return false;
    }
    
    public function uploadImage()
    {
        if ($this->validate()) {
            $mdl = new Image();
            
            // generate filename
            $mdl->url = Yii::$app->security->generateRandomString(16) . '_' . time() . '.' . $this->image->extension;
            $mdl->type = $this->_type;
            $mdl->subject_id = $this->_subject_id;
            
            // create path to save image
            $path = Yii::getAlias('@webroot') . $mdl->path;
            
            // check if path exists
            if (!file_exists($path)) {
                // if not -> create
                FileHelper::createDirectory($path, 0777);
            }
            if ($mdl->save()) {
                // save file and model
                if (!$this->image instanceof UploadedFile or !$this->image->saveAs($path . $mdl->url, false)) {
                    return false;
                }
            }
            
            unset($mdl);
            $this->image = null;
            
            return true;
            
        }
        
        return false;
    }
}