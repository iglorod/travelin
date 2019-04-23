<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property int $user_id
 * @property string $avatar
 * @property string $first_name
 * @property string $second_name
 * @property string $middle_name
 * @property int $birthday
 * @property int $gender
 * @property string $background_url
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'gender', 'prime', 'admin', 'ban'], 'integer'],
            [['avatar'], 'string', 'max' => 255],
            [['first_name', 'second_name', 'middle_name'], 'string', 'max' => 32],
            [['background_url'], 'string', 'max' => 500],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'avatar' => 'Avatar',
            'first_name' => 'First Name',
            'second_name' => 'Second Name',
            'middle_name' => 'Middle Name',
            'birthday' => 'Birthday',
            'gender' => 'Gender',
            'background_url' => 'Background Url',
            'prime' => 'Prime',
            'admin' => 'Admin',
            'ban'   => 'Ban'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    public function isBanned()
    {
        if($this->ban == "1") return true;
        return false;
    }
    
    public function getCountBanned($array){
        $count = 0;
        foreach($array as $val){
            if($val['ban'] == '1') $count++;
        }
        return $count;
    }

    public function post_count($array, $val){
        $count = 0;
        foreach($array as $val1){
            if($val1[0]['id_author'] == $val) $count++;
        }
        return $count;
    }
}
