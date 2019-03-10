<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\BaseYii;
use yii\web\UploadedFile;

class ImageUpload extends Model{

    public $image;
    public $image_name;

    public function rules(){
        return [
            //[['image'],'file','extensions' => 'jpg,png,jpeg']
        ];
    }

    public function uploadFile($currentImage){
            if (file_exists(Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $currentImage) && $currentImage!='') {
                unlink(Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $currentImage);
            }

            $image_array_1 = explode(";", $this->image);
            $image_array_2 = explode(",", $image_array_1[1]);
            $data = base64_decode($image_array_2[1]);

            $expl = explode(".", $this->image_name);
            $baseName = $expl[0];

            $filename = strtolower(md5(uniqid($baseName)) . '.png');
            $target = Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $filename;
            file_put_contents($target, $data);

            return $filename;

     /*       $info = pathinfo($_FILES['userFile']['name']);
            $name = $this->image['name'];
            $expl = explode(".", $name);
            $baseName = $expl[0];
            $extension = $expl[1];

            $filename = strtolower(md5(uniqid($baseName)) . '.' . $extension);

            $target = Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $filename;
            move_uploaded_file( $this->image['tmp_name'], $target);
            return $filename;*/
    }

    public function deleteOldFiles($fileList){
        foreach ($fileList as $key => $value){
            if (file_exists(Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $value) && $value!='') {
                unlink(Yii::getAlias('@frontend') . '/web/uploads/marker_images/' . $value);
            }
        }
    }

}