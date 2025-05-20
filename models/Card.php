<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "card".
 *
 * @property int $id
 * @property string $name
 * @property string $author
 * @property string $publication
 * @property string $publisher
 * @property string $year_publication
 * @property string $publication_status
 * @property int $condition_id
 * @property int $status_id
 * @property int $binding_id
 * @property string|null $cancellation_reason
 * @property int $user_id
 * @property int $isDelete
 *
 * @property Binding $binding
 * @property Condition $condition
 * @property Status $status
 * @property User $user
 */
class Card extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const PUBLICATION_STATUS_PUBLISH = 'publish';
    const PUBLICATION_STATUS_LIBRARY = 'library';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        if (!Yii::$app->user->identity->isAdmin()){
            return [
                [['cancellation_reason'], 'default', 'value' => null],
                [['isDelete'], 'default', 'value' => 0],
                [['name', 'author', 'publication', 'publisher', 'year_publication', 'publication_status', 'condition_id', 'binding_id'], 'required'],
                [['year_publication'], 'safe'],
                [['publication_status'], 'string'],
                [['condition_id', 'status_id', 'binding_id', 'user_id', 'isDelete'], 'integer'],
                [['name', 'author', 'publication', 'publisher', 'cancellation_reason'], 'string', 'max' => 255],
                ['publication_status', 'in', 'range' => array_keys(self::optsPublicationStatus())],
                [['condition_id'], 'exist', 'skipOnError' => true, 'targetClass' => Condition::class, 'targetAttribute' => ['condition_id' => 'id']],
                [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
                [['binding_id'], 'exist', 'skipOnError' => true, 'targetClass' => Binding::class, 'targetAttribute' => ['binding_id' => 'id']],
                [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
                ['user_id', 'default', 'value' => Yii::$app->user->identity->id]
            ];
        } else {
            return [
                [['status_id'], 'required'],
                [['cancellation_reason'], 'required',
                    'when' => function($model) {
                        return $model->status_id == 3;
                    },
                    'whenClient' => "function(attribute, value){
                        return $('select[id*=`status_id`]').val() === 3;
                    }",
                    'message' => 'Заполните причину отмены'
                ],
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'author' => 'Author',
            'publication' => 'Publication',
            'publisher' => 'Publisher',
            'year_publication' => 'Year Publication',
            'publication_status' => 'Publication Status',
            'condition_id' => 'Condition ID',
            'status_id' => 'Status ID',
            'binding_id' => 'Binding ID',
            'cancellation_reason' => 'Cancellation Reason',
            'user_id' => 'User ID',
            'isDelete' => 'Is Delete',
        ];
    }

    /**
     * Gets query for [[Binding]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBinding()
    {
        return $this->hasOne(Binding::class, ['id' => 'binding_id']);
    }

    /**
     * Gets query for [[Condition]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCondition()
    {
        return $this->hasOne(Condition::class, ['id' => 'condition_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * column publication_status ENUM value labels
     * @return string[]
     */
    public static function optsPublicationStatus()
    {
        return [
            self::PUBLICATION_STATUS_PUBLISH => 'publish',
            self::PUBLICATION_STATUS_LIBRARY => 'library',
        ];
    }

    /**
     * @return string
     */
    public function displayPublicationStatus()
    {
        return self::optsPublicationStatus()[$this->publication_status];
    }

    /**
     * @return bool
     */
    public function isPublicationStatusPublish()
    {
        return $this->publication_status === self::PUBLICATION_STATUS_PUBLISH;
    }

    public function setPublicationStatusToPublish()
    {
        $this->publication_status = self::PUBLICATION_STATUS_PUBLISH;
    }

    /**
     * @return bool
     */
    public function isPublicationStatusLibrary()
    {
        return $this->publication_status === self::PUBLICATION_STATUS_LIBRARY;
    }

    public function setPublicationStatusToLibrary()
    {
        $this->publication_status = self::PUBLICATION_STATUS_LIBRARY;
    }

    public function beforeSave($insert)
    {
        if($this->status_id != 3)
        {
            $this->cancellation_reason = null;
        }
        return parent::beforeSave($insert);
    }
}
