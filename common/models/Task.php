<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $title Название задачи
 *
 * @property ObjectTask[] $objectTasks
 * @property TaskList[] $taskLists
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название задачи',
        ];
    }

    /**
     * Gets query for [[ObjectTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjectTasks()
    {
        return $this->hasMany(ObjectTask::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskLists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskLists()
    {
        return $this->hasMany(TaskList::className(), ['task_id' => 'id']);
    }
}
