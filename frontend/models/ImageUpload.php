<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\BaseYii;
use yii\web\UploadedFile;

class ImageUpload extends Model{

    public $image;

    public function rules(){
        return [
            //[['image'],'file','extensions' => 'jpg,png,jpeg']
        ];
    }

    public function uploadFile($currentImage){
            if (file_exists(Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $currentImage) && $currentImage!='') {
                unlink(Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $currentImage);
            }

            $info = pathinfo($_FILES['userFile']['name']);
            $name = $this->image['name'];
            $expl = explode(".", $name);
            $baseName = $expl[0];
            $extension = $expl[1];

            $filename = strtolower(md5(uniqid($baseName)) . '.' . $extension);

            $target = Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $filename;
            move_uploaded_file( $this->image['tmp_name'], $target);
            return $filename;
    }

    public function deleteOldFiles($fileList){
        foreach ($fileList as $key => $value){
            if (file_exists(Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $value) && $value!='') {
                unlink(Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $value);
            }
        }
    }

}