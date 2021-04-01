<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "object".
 *
 * @property int $id
 * @property int $object_id Parent object
 * @property string $title
 *
 * @property Objects $object
 * @property Objects[] $objects
 * @property ObjectTask[] $objectTasks
 */
class Objects extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'object';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['object_id', 'title'], 'required'],
            [['object_id'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['object_id'], 'exist', 'skipOnError' => true, 'targetClass' => Objects::className(), 'targetAttribute' => ['object_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_id' => 'Parent object',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[Object]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObject()
    {
        return $this->hasOne(Objects::className(), ['id' => 'object_id']);
    }

    /**
     * Gets query for [[Objects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjects()
    {
        return $this->hasMany(Objects::className(), ['object_id' => 'id']);
    }

    /**
     * Gets query for [[ObjectTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjectTasks()
    {
        return $this->hasMany(ObjectTask::className(), ['object_id' => 'id']);
    }
}
