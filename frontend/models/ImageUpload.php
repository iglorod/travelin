<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\BaseYii;
use yii\web\UploadedFile;

class ImageUpload extends Model{

    public $image;
    public $marker_id;
    public $image_id;

    public function rules(){
        return [
        [['image'/*,'marker_id', 'image_id'*/],'required'],
            [['image'],'file','extensions' => 'jpg,png,jpeg']
        ];
    }

    public function uploadFile(UploadedFile $file, $currentImage){
        $this->image = $file;

        if($this->validate()) {
            if (file_exists(Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $currentImage) && $currentImage!='') {
                unlink(Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $currentImage);
            }

            $filename = strtolower(md5(uniqid($file->baseName)) . '.' . $file->extension);

            $file->saveAs(Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $filename);
            return $filename;
        }
    }

}