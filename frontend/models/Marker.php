<?php

namespace frontend\models;

use Yii;
use frontend\models\Post;

/**
 * This is the model class for table "marker".
 *
 * @property int $id
 * @property int $id_post
 * @property double $lat
 * @property double $lng
 * @property string $title
 * @property string $text
 *
 * @property Post $post
 * @property MarkerImage[] $markerImages
 */
class Marker extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'marker';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_post', 'lat', 'lng'], 'required'],
            [['id_post'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['text'], 'string'],
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
            'id_post' => 'Id Post',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'title' => 'Title',
            'text' => 'Text',
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
    public function getMarkerImages()
    {
        return $this->hasMany(MarkerImage::className(), ['id_marker' => 'id']);
    }
}
