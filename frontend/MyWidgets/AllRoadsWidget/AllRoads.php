<?php
namespace frontend\MyWidgets\AllRoadsWidget;

use Yii;
use yii\base\Widget;

class AllRoads extends Widget{

    public $trip_polilynes;

    public $image_maded;

    public function init(){
        parent::init();
    }

    public function run(){
        return $this->render('all-roads', [
            'trip_polilynes'    => $this->trip_polilynes,
            'image_maded'       => $this->image_maded
        ]);
    }
}