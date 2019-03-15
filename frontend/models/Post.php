<?php

namespace frontend\models;

use Yii;
use common\models\User;
use frontend\models\Marker;
use frontend\models\Repost;
use frontend\models\PostLikes;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $id_author
 * @property string $id_place
 * @property string $text
 * @property int $created_at
 * @property int $updated_at
 * @property string $polilynes
 *
 * @property Marker[] $markers
 * @property User $author
 */
class Post extends \yii\db\ActiveRecord
{
    /** return map result from post/create */
    public $result_markers;
    public $result_polilyne;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_place', 'text'], 'required'],
            [['id_author', 'created_at', 'updated_at'], 'integer'],
            [['text', 'polilynes', 'result_markers', 'result_polilyne'], 'string'],
            [['id_place'], 'string', 'max' => 100],
            [['id_author'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_author' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_author' => 'Id Author',
            'id_place' => 'Id Place',
            'text' => 'Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'polilynes' => 'Polilynes',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarkers()
    {
        return $this->hasMany(Marker::className(), ['id_post' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'id_author']);
    }

    public function getLikesCount()
    {
        return PostLikes::find()->where(["id_post" => $this->id])->count();
    }

    public function getIsUserLiked()
    {
        $count = PostLikes::find()->where([ "id_post" => $this->id, "id_user" => Yii::$app->user->identity->id ])->count();
        if($count >= 1) return "done-by-user";
        return "";
    }

    public function getIsUserReposted()
    {
        $count = Repost::find()->where([ "id_post" => $this->id, "id_user" => Yii::$app->user->identity->id ])->count();
        if($count >= 1) return "done-by-user";
        return "";
    }

    public function getRepostsCount()
    {
        return Repost::find()->where(["id_post" => $this->id])->count();
    }
}
