<?php
namespace frontend\MyWidgets\PostsList;

use Yii;
use yii\base\Widget;

class OneRepostEcho extends Widget{

    public $trip;

    public function init(){
        parent::init();
    }

    public function run(){
        return $this->render('one-repost', [
            'model'         => $this->trip,
        ]);
    }
}