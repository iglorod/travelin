<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "pathway".
 *
 * @property int $id
 * @property double $lat
 * @property double $len
 * @property string $brief_descr
 * @property int $id_post
 *
 * @property Imageway[] $imageways
 * @property Post $post
 */
class Pathway extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pathway';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lat', 'len', 'id_post'], 'required'],
            [['lat', 'len'], 'number'],
            [['brief_descr'], 'string'],
            [['id_post'], 'integer'],
            [['id_post'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['id_post' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lat' => 'Lat',
            'len' => 'Len',
            'brief_descr' => 'Brief Descr',
            'id_post' => 'Id Post',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageways()
    {
        return $this->hasMany(Imageway::className(), ['id_pathway' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'id_post']);
    }
}
