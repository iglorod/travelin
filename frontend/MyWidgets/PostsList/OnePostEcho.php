<?php
namespace frontend\MyWidgets\PostsList;

use Yii;
use yii\base\Widget;

class OnePostEcho extends Widget{

    public $trip;

    public function init(){
        parent::init();
    }

    public function run(){
        return $this->render('one-post', [
            'model'         => $this->trip,
        ]);
    }
}