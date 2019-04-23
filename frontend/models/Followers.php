<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "followers".
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_follower
 *
 * @property User $user
 * @property User $follower
 */
class Followers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'followers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_follower'], 'required'],
            [['id_user', 'id_follower'], 'integer'],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['id_follower'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_follower' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_follower' => 'Id Follower',
        ];
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
    public function getFollower()
    {
        return $this->hasOne(User::className(), ['id' => 'id_follower']);
    }

    public function deleteFollowing()
    {
        $this->delete();
    }

    public function getCount(){
        return Followers::find()->where(['id_user'=>$this->id_user])->count();
    }
}
