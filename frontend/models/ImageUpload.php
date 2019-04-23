<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\BaseYii;
use yii\web\UploadedFile;

class ImageUpload extends Model{

    public $image;
    public $image_name;
    public $folder;

    public function rules(){
        return [
            //[['image'],'file','extensions' => 'jpg,png,jpeg']
        ];
    }

    public function uploadFile($currentImage){
            if (file_exists(Yii::getAlias('@frontend') . '/web/uploads/' . $this->folder . $currentImage) && $currentImage!='') {
                unlink(Yii::getAlias('@frontend') . '/web/uploads/' . $this->folder . $currentImage);
            }

            $image_array_1 = explode(";", $this->image);
            $image_array_2 = explode(",", $image_array_1[1]);
            $data = base64_decode($image_array_2[1]);

            $expl = explode(".", $this->image_name);
            $baseName = $expl[0];

            $filename = strtolower(md5(uniqid($baseName)) . '.png');
            $target = Yii::getAlias('@frontend') . '/web/uploads/' . $this->folder . $filename;
            file_put_contents($target, $data);

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