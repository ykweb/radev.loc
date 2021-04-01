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

    public static function getDropdownListAll(){
        return self::find()->select(['title', 'id'])->indexBy('id')->orderBy(['title'=>SORT_ASC])->column();
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) { return false; }

        // Before delete Task need delete link to Object
        ObjectTask::deleteAll(['task_id'=>$this->id]);

        // Before delete Task need delete link to TaskList
        TaskList::deleteAll(['task_id'=>$this->id]);

        return true;
    }

    /**
     * @param $insert
     * @param $changedAttributes array
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::debug('Task save');
        if (method_exists(Yii::$app,'getSession')){
            Yii::$app->getSession()->setFlash('Task_save_'.mt_rand(), ['message' => 'Task save','type'=>'success']);
        }
    }

    /**
     *
     */
    public function afterDelete()
    {
        Yii::debug('Task delete');
        if (method_exists(Yii::$app,'getSession')){
            Yii::$app->getSession()->setFlash('Task_delete_'.mt_rand(), ['message' => 'Task deleted','type'=>'info']);
        }
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
