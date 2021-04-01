<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "object_task".
 *
 * @property int $id
 * @property int $object_id
 * @property int $task_id
 *
 * @property Object $object
 * @property Task $task
 */
class ObjectTask extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'object_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['object_id', 'task_id'], 'required'],
            [['object_id', 'task_id'], 'integer'],
            [['object_id'], 'exist', 'skipOnError' => true, 'targetClass' => Object::class, 'targetAttribute' => ['object_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_id' => 'Object ID',
            'task_id' => 'Task ID',
        ];
    }

    /**
     * Gets query for [[Object]].
     *
     * @return ActiveQuery
     */
    public function getObject()
    {
        return $this->hasOne(Object::class, ['id' => 'object_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
