<?php

namespace app\models;

use http\Exception\InvalidArgumentException;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "image".
 *
 * @property int    $id
 * @property int    $type
 * @property int    $subject_id
 * @property string $url
 * @property int    $sort
 * @property int    $created_at
 */
class Image
    extends \yii\db\ActiveRecord
{
    
    const TYPE_ITEM = 1;
    
    const TYPE_CATEGORY = 2;
    
    const TYPE_BANNER = 3;
    
    const SIZE_ORIGINAL = 1;
    
    const SIZE_MEDIUM = 2;
    
    const SIZE_THUMBNAIL = 3;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'type', 'subject_id', 'url', ], 'required' ],
            [ [ 'type', 'subject_id', 'sort' ], 'integer' ],
            [ [ 'sort' ], 'default', 'value' => function ($model)
            {
                return count(self::find()
                                 ->where([ 'type' => $model->type, 'subject_id' => $model->subject_id ])
                                 ->all());
            } ],
            [ [ 'url' ], 'string', 'max' => 255 ],
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
            'type' => 'Тип',
            'subject_id' => 'Объект ID',
            'url' => 'Путь',
            'sort' => 'Сортировка',
            'created_at' => 'Дата создания',
        ];
    }
    
    public function getPath($size = self::SIZE_ORIGINAL)
    {
        if (array_key_exists($size, self::getSizes())) {
            
            $class = self::getTypes()[$this->type];
            
            $folder = self::getSizes()[$size];
            
            return "/files/{$class}/{$class}-{$this->subject_id}{$folder}";
        }
        
        return '';
    }
    
    public function getColor()
    {
        if ($this->type == self::TYPE_ITEM) {
            return $this->hasOne(ItemColor::className(), [ 'id' => 'subject_id' ]);
        }
        
        return null;
    }
    
    public static function getTypes()
    {
        return [
            self::TYPE_ITEM => 'Item',
            self::TYPE_CATEGORY => 'Category',
            self::TYPE_BANNER => 'Poster',
        ];
    }
    
    public static function getSizes()
    {
        return [
            self::SIZE_ORIGINAL => '/',
            self::SIZE_MEDIUM => '/medium/',
            self::SIZE_THUMBNAIL => '/thumbnails/',
        ];
    }
    
    /**
     * @param $type
     * @param $subject_id
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getImagesBySubject($type, $subject_id)
    {
        // todo-cache: add cache(30 sec) and clear value before insert in query
        
        return self::find()
                   ->where([
                               'subject_id' => $subject_id,
                               'type' => $type,
                           ])
                   ->orderBy([ 'sort' => SORT_ASC ])
                   ->asArray(false)
                   ->all();
    }
    
    /**
     * @param $type
     * @param $subject_id
     *
     * @return array
     */
    public static function getInitialPreviewConfigBySubject($type, $subject_id)
    {
        return ArrayHelper::toArray(self::getImagesBySubject($type, $subject_id), [
                                                                                    Image::className() => [
                                                                                        'caption' => 'url',
                                                                                        'key' => 'id',
                                                                                    ],
                                                                                ]
        );
    }
    
    /**
     * Return relative path to image
     *
     * @param int $image_id
     * @param int $size if u want to get cropped image
     *
     * @return null|string
     */
    public static function getLink($image_id, $size = self::SIZE_ORIGINAL)
    {
        // todo-cache: add cache (few hours) md5($image_id . '_' . $size)
        if ($image = self::findOne([ 'id' => (int) $image_id ]) and array_key_exists($image->type,
                                                                                     self::getTypes()) and array_key_exists($size,
                                                                                                                            self::getSizes())) {
            
            $class = self::getTypes()[$image->type];
            $size = self::getSizes()[$size];
            
            return "/files/{$class}/{$class}-{$image->subject_id}" . $size . $image->url;
            
        }
        
        return null;
    }
    
    /**
     * @param $type
     * @param $subject_id
     * @param $size
     *
     * @return array
     */
    public static function getUrlsBySubject($type, $subject_id, $size = self::SIZE_ORIGINAL)
    {
        $urls = [];
        // todo-cache: add cache(30 sec)
        if (array_key_exists($type, self::getTypes()) and array_key_exists($size, self::getSizes())) {
    
            $ids = array_column(self::getImagesBySubject($type, $subject_id), 'id');
    
            foreach ($ids as $id) {
                $urls[] = self::getLink($id, $size);
            }
        }
        
        return $urls;
    }
    
    public static function resize($path, $width_p = false, $height_p = false, $add_watermark = false)
    {
        $filename = $path;
        
        $info = getimagesize($filename);
        $width = $info[0];
        $height = $info[1];
        $type = $info[2];
        
        switch ($type) {
            case IMAGETYPE_JPEG:
                $img = imageCreateFromJpeg($filename);
                break;
            case IMAGETYPE_PNG:
                $img = imageCreateFromPng($filename);
                imageSaveAlpha($img, true);
                break;
            default:
                throw new InvalidArgumentException('Invalid image type given');
        }
        
        // new image sizes
        if ($width_p !== false or $height_p !== false) {
            $w = $width_p;
            $h = $height_p;
        }
        
        if (empty($w)) {
            $w = ceil($h / ( $height / $width ));
        }
        if (empty($h)) {
            $h = ceil($w / ( $width / $height ));
        }
        
        $tmp = imageCreateTrueColor($w, $h);
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_JPEG) {
            imagealphablending($tmp, true);
            imageSaveAlpha($tmp, true);
            $transparent = imagecolorallocate($tmp, 255, 255, 255);
            imagefill($tmp, 0, 0, $transparent);
            imagecolortransparent($tmp, $transparent);
        }
        
        $tw = ceil($h / ( $height / $width ));
        $th = ceil($w / ( $width / $height ));
        if ($tw < $w) {
            imageCopyResampled($tmp, $img, ceil(( $w - $tw ) / 2), 0, 0, 0, $tw, $h, $width, $height);
        } else {
            imageCopyResampled($tmp, $img, 0, ceil(( $h - $th ) / 2), 0, 0, $w, $th, $width, $height);
        }
    
        if ($add_watermark) {
            self::watermark($tmp, '75%', '75%', $w, $h);
        }
        
        if ($type == IMAGETYPE_JPEG) {
            imagejpeg($tmp, $filename);
        } elseif ($type == IMAGETYPE_PNG) {
            imagepng($tmp, $filename);
        }
    }
    
    public static function watermark(&$img, $x = '50%', $y = '50%', $width = false, $height = false)
    {
        $watermark = Yii::getAlias('@webroot') . '/images/watermark.png';
        
        $info = getimagesize($watermark);
        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                $tmp = imageCreateFromJpeg($watermark);
                break;
            case IMAGETYPE_PNG:
                $tmp = imageCreateFromPng($watermark);
                break;
            default:
                throw new InvalidArgumentException('Invalid watermark type given');
        }
        
        if (strpos($x, '%') !== false and $width !== false) {
            $x = intval($x);
            $x = ceil(( $width * $x / 100 ) - ( $info[0] / 100 * $x ));
        }
        if (strpos($y, '%') !== false and $height !== false) {
            $y = intval($y);
            $y = ceil(( $height * $y / 100 ) - ( $info[1] / 100 * $y ));
        }
        
        imagecopy($img, $tmp, $x, $y, 0, 0, $info[0], $info[1]);
        imagedestroy($tmp);
    }
}
