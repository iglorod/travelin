<?php

namespace frontend\models;

use Yii;
use common\models\User;
use frontend\models\Post;
use frontend\models\RepostLikes;

/**
 * This is the model class for table "repost".
 *
 * @property int $id
 * @property int $id_post
 * @property int $id_user
 * @property string $description
 * @property int $created_at
 *
 * @property Post $post
 * @property User $user
 * @property RepostLikes[] $repostLikes
 */
class Repost extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'repost';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_post', 'id_user', 'description', 'created_at'], 'required'],
            [['id_post', 'id_user', 'created_at'], 'integer'],
            [['description'], 'string', 'max' => 500],
            [['id_post'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['id_post' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_post' => 'Id Post',
            'id_user' => 'Id User',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'id_post']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepostLikes()
    {
        return $this->hasMany(RepostLikes::className(), ['id_repost' => 'id']);
    }
    
    public function getRepostLikesCount()
    {
        return RepostLikes::find()->where(['id_repost' => $this->id])->count();
    }
    
    public function getIsUserLikedRepost()
    {
        $count = RepostLikes::find()->where([ "id_repost" => $this->id, "id_user" => Yii::$app->user->identity->id ])->count();
        if($count >= 1) return "done-by-user";
        return "";
    }
}
