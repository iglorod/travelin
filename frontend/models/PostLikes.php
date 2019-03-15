<?php

namespace frontend\models;

use Yii;
use common\models\User;
use frontend\models\Post;

/**
 * This is the model class for table "post_likes".
 *
 * @property int $id
 * @property int $id_post
 * @property int $id_user
 *
 * @property Post $post
 * @property User $user
 */
class PostLikes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_likes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_post', 'id_user'], 'required'],
            [['id_post', 'id_user'], 'integer'],
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

    public function deleteLike()
    {
        $this->delete();
    }

    public function getCount($id){
        return PostLikes::find()->where(['id_post'=>$id])->count();
    }
}
