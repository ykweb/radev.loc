<?php

namespace common\models;

use voskobovich\behaviors\ManyToManyBehavior;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "object".
 *
 * @property int $id
 * @property int $object_id Parent object
 * @property string $title
 * @property string $image
 * @property string $img  image position or message
 * @property array $task_ids
 *
 * @property Objects $parentObject
 * @property Objects[] $subObjects
 * @property ObjectTask[] $objectTasks
 * @property Task[] $tasks
 *
 * @property ObjectTask[] $listOtherObject
 */
class Objects extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $_imageFile;
    // Directory for storing uploaded images. Use aliases
    private $directory_image='@frontend/web/uploads/';
    //The relative address of the folder location on the web server
    private $url_image='/uploads/';

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
            [['image'], 'string', 'max' => 120],
            [['object_id'], 'exist', 'skipOnError' => true, 'targetClass' => Objects::class, 'targetAttribute' => ['object_id' => 'id']],
            ['task_ids', 'each', 'rule' => ['integer']],
            [['_imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 1],
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
            'parentObjectTitle' => 'Parent title',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => ManyToManyBehavior::class,
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
     * Return image URL or false
     * @return bool|string
     */
    public function getImg(){
        if (!$this->image){
            return false;
        }
        return $this->url_image.$this->image;
    }

    /**
     * Upload image to directory image
     * Name file = project->id
     * @return bool
     */
    public function uploadImage()
    {
        if (!$this->_imageFile){
            return false;
        }
        if ($this->validate()) {
            $this->_imageFile->saveAs(Yii::getAlias($this->directory_image.$this->id . '.' . $this->_imageFile->extension));
            $this->image=$this->id. '.' . $this->_imageFile->extension;
            $this->save(false);

            Yii::debug('Image save');
            if (method_exists(Yii::$app,'getSession')){
                Yii::$app->getSession()->setFlash('image_save_'.mt_rand(), ['message' => 'Image save','type'=>'success']);
            }
            return true;
        } else {
            Yii::debug('Error load image');
            if (method_exists(Yii::$app,'getSession')){
                Yii::$app->getSession()->setFlash('image_save_'.mt_rand(), ['message' => 'Error load image','type'=>'danger']);
            }
            return false;
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
     * @return ActiveQuery
     */
    public function getParentObject()
    {
        return $this->hasOne(Objects::class, ['id' => 'object_id']);
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
     * @return ActiveQuery
     */
    public function getSubObjects()
    {
        return $this->hasMany(Objects::class, ['object_id' => 'id']);
    }

    /**
     * Gets query for [[ObjectTasks]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['id' => 'task_id'])->viaTable('object_task', ['object_id' => 'id']);
    }
}
