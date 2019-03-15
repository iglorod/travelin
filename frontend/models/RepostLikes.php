<?php

namespace frontend\models;

use Yii;
use common\models\User;
use frontend\models\Post;

/**
 * This is the model class for table "repost_likes".
 *
 * @property int $id
 * @property int $id_repost
 * @property int $id_user
 *
 * @property Repost $repost
 * @property User $user
 */
class RepostLikes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'repost_likes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_repost', 'id_user'], 'required'],
            [['id_repost', 'id_user'], 'integer'],
            [['id_repost'], 'exist', 'skipOnError' => true, 'targetClass' => Repost::className(), 'targetAttribute' => ['id_repost' => 'id']],
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
            'id_repost' => 'Id Repost',
            'id_user' => 'Id User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepost()
    {
        return $this->hasOne(Repost::className(), ['id' => 'id_repost']);
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
        return RepostLikes::find()->where(['id_repost'=>$id])->count();
    }
}
