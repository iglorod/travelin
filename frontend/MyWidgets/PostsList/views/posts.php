<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use frontend\MyWidgets\PostsList\OnePostEcho;
use frontend\MyWidgets\PostsList\OneRepostEcho;
use yii\helpers\StringHelper;

?>

<div>
    <?php foreach($trips as $model){
        if(StringHelper::basename(get_class($model)) == 'Repost'){
            echo OneRepostEcho::widget([
                'trip' => $model
            ]);
        }else{
            echo OnePostEcho::widget([
                'trip' => $model
            ]);
        } ?>
    <?php } ?>
    <div class="col-xs-12 text-center">
        <?php echo LinkPager::widget(['pagination'=>$pagination]); ?>
    </div>
</div>

<?= Html::hiddenInput('link', "", ['id'=>'hidden-link-post']); ?>
<?= Html::hiddenInput('link', Url::to(['post/view'], true) . "&id=", ['id'=>'hidden-link-const']); ?>

<div class="modal fade" id="reposting-post" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header grey-modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title modal-title-repost-post"> <ion-icon name="repeat"></ion-icon> Repost</h4>
      </div>
      <div class="modal-body repost-description-modal-body">
          <span class="title-repost-description-span">Add description to repost <span>(optional)</span></span>
          <hr>
          <span class="sub-repost-description-span">Tell others travelers what impressed you in this post...</span>
        <?= Html::textArea ('descriptionArea', $value = '', $options = ['class' => 'description-repost-area form-control'] ) ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <span class="btn btn-primary btn-repost">Repost</a>
      </div>
    </div>
  </div>
</div>

<?php
function getImage($text){
    $start_string = stristr($text, '<img');
    $end = strpos($start_string, '>', 0);
    
    $result = substr($start_string, 0, $end+1);
    
    if (strpos($result, 'width="') !== false) {
    $first_width = strpos($result, 'width="') + strlen('width="');
    $second_width = strpos($result, '"',$first_width);
    $first_width = $first_width-strlen('width="');
    $total_width = substr($result, $first_width, $second_width-$first_width+1);
    
    $result = str_replace($total_width, "", $result);
    }
    
    if (strpos($result, 'height="') !== false) {
    $first_heigth = strpos($result, 'height="') + strlen('height="');
    $second_heigth = strpos($result, '"',$first_heigth);
    $first_heigth = $first_heigth-strlen('height="');
    $total_heigth = substr($result, $first_heigth, $second_heigth-$first_heigth+1);
    
    $result = str_replace($total_heigth, "", $result);
    }

    if (strpos($result, 'style="') !== false) {
      $first_style = strpos($result, 'style="') + strlen('style="');
      $second_style = strpos($result, '"',$first_style);
      $first_style = $first_style-strlen('style="');
      $total_style = substr($result, $first_style, $second_style-$first_style+1);
      
      $result = str_replace($total_style, "", $result);
    }

    return $result;
}
?>
    