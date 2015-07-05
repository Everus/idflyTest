<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $headline
 * @property string $description
 * @property string $date
 * @property string $content
 * @property string $image
 * @property integer $status
 * @property integer $category_id
 */
class News extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['headline', 'description', 'date', 'content', 'category_id'], 'required'],
            [['headline', 'description', 'content', 'image'], 'string'],
            [['date'], 'safe'],
            [['date'], 'required'],
            [['date'], 'date', 'format' => 'yyyy-mm-dd'],
            [['status', 'category_id'], 'integer'],
            [['imageFile'], 'file','skipOnEmpty' => true, 'extensions' => 'png, jpg']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'headline' => 'Headline',
            'description' => 'Description',
            'date' => 'Date',
            'content' => 'Content',
            'image' => 'Image',
            'status' => 'Status',
            'category_id' => 'Category',
            'categoryName' => 'Category',
            'statusText' => 'Status',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->image = 'uploads/news/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs(Yii::getAlias('@webroot').'/'.$this->image);
            $this->image = Yii::getAlias('@web').'/'.$this->image;
            $this->imageFile = NULL;
            return true;
        } else {
            return false;
        }
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

    public function getCategoryName()
    {
        return $this->category->caption;
    }
}
