<?php

namespace frontend\models;

use Yii;
use frontend\models\Marker;

/**
 * This is the model class for table "marker_image".
 *
 * @property int $id
 * @property string $name
 * @property string $text
 * @property int $id_marker
 *
 * @property Marker $marker
 */
class MarkerImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'marker_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'id_marker'], 'required'],
            [['id_marker'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['text'], 'string', 'max' => 40],
            [['id_marker'], 'exist', 'skipOnError' => true, 'targetClass' => Marker::className(), 'targetAttribute' => ['id_marker' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'text' => 'Text',
            'id_marker' => 'Id Marker',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarker()
    {
        return $this->hasOne(Marker::className(), ['id' => 'id_marker']);
    }
}
