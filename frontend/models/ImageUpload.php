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
   /*     [['image'/*,'marker_id', 'image_id'],'required'],*/
            /*[['image'],'file','extensions' => 'jpg,png,jpeg']*/
            [['marker_id'],'string']
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

}