<?php
namespace frontend\MyWidgets\PostsList;

use Yii;
use yii\base\Widget;

class PostsAllOut extends Widget{

    /** count of pages are availible */
    public $pagination;

    public $trips;

    public function init(){
        parent::init();
    }

    public function run(){
        return $this->render('posts', [
            'trips'         => $this->trips,
            'pagination'    => $this->pagination,
        ]);
    }
}