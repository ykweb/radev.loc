<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "object".
 *
 * @property int $id
 * @property int $object_id Parent object
 * @property string $title
 * @property array $task_ids
 *
 * @property Objects $parentObject
 * @property Objects[] $subObjects
 * @property ObjectTask[] $objectTasks
 *
 * @property ObjectTask[] $listOtherObject
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
            [['title'], 'required'],
            [['object_id'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['object_id'], 'exist', 'skipOnError' => true, 'targetClass' => Objects::className(), 'targetAttribute' => ['object_id' => 'id']],
            ['task_ids', 'each', 'rule' => ['integer']]
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

    public function behaviors()
    {
        return [
            [
                'class' => \voskobovich\behaviors\ManyToManyBehavior::class,
                'relations' => [
                    'task_ids' => 'tasks',
                ],
            ],
        ];
    }


    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) { return false; }

        if ($this->subObjects) {
            Yii::$app->getSession()->setFlash('Object_delete_'.mt_rand(), ['message' => 'Unable to delete, i.e. there are child objects','type'=>'danger']);
            $this->addError('id', 'Unable to delete, i.e. there are child objects');
            return false;
        }
        // Before delete Object need delete link to Task
        ObjectTask::deleteAll(['object_id'=>$this->id]);

        return true;
    }

    /**
     * @param $insert
     * @param $changedAttributes array
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::debug('Object save');
        if (method_exists(Yii::$app,'getSession')){
            Yii::$app->getSession()->setFlash('Object_save_'.mt_rand(), ['message' => 'Object save','type'=>'success']);
        }
    }


    /**
     *
     */
    public function afterDelete()
    {
        Yii::debug('Object delete');
        if (method_exists(Yii::$app,'getSession')){
            Yii::$app->getSession()->setFlash('Object_delete_'.mt_rand(), ['message' => 'Object deleted','type'=>'info']);
        }
    }

    /**
     * List of other objects, excluding the current one
     * Список других объектов, за исключением текущего
     */
    public function getListOtherObjects(){
        if ($this->isNewRecord){
            // If the model is new, return all
            return self::find()->select(['title', 'id'])->indexBy('id')->column();
        }
        // If the model is old, return excluding the current one
        return self::find()->select(['title', 'id'])->where(['<>','id',$this->id])->indexBy('id')->column();
    }

    /**
     * Gets query for [[Object]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParentObject()
    {
        return $this->hasOne(Objects::className(), ['id' => 'object_id']);
    }

    /**
     * Gets query for [[Object]].
     *
     * @return string
     */
    public function getParentObjectTitle()
    {
        if ($this->getParentObject()){
            return $this->parentObject->title;
        }
        return 'Not parent object';
    }

    /**
     * Gets query for [[Objects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubObjects()
    {
        return $this->hasMany(Objects::className(), ['object_id' => 'id']);
    }

    /**
     * Gets query for [[ObjectTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id' => 'task_id'])->viaTable('object_task', ['object_id' => 'id']);
    }
}
