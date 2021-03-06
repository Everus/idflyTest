<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $caption
 * @property string $description
 * @property integer $status
 */
class Category extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['caption', 'status'], 'required'],
            [['caption', 'description'], 'string'],
            [['status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'caption' => 'Caption',
            'description' => 'Description',
            'status' => 'Status',
            'statusText' => 'Status',
        ];
    }

    public function getNews()
    {
        return $this->hasMany(News::className(), ['category_id' => 'id'])->inverseOf('category');
    }

    public function getStatusText()
    {
        if($this->status) {
            return 'ACTIVE';
        }
        else {
            return 'INACTIVE';
        }
    }

    public function setStatusText($statusText)
    {
        if($statusText === 'ACTIVE') {
            $this->status = 1;
        } else {
            $this->status = 0;
        }
    }
}
