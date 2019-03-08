<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "imageway".
 *
 * @property int $id
 * @property string $image
 * @property int $is_main
 * @property int $id_pathway
 *
 * @property Pathway $pathway
 */
class Imageway extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'imageway';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image', 'id_pathway'], 'required'],
            [['is_main', 'id_pathway'], 'integer'],
            [['image'], 'string', 'max' => 100],
            [['id_pathway'], 'exist', 'skipOnError' => true, 'targetClass' => Pathway::className(), 'targetAttribute' => ['id_pathway' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Image',
            'is_main' => 'Is Main',
            'id_pathway' => 'Id Pathway',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPathway()
    {
        return $this->hasOne(Pathway::className(), ['id' => 'id_pathway']);
    }
}
