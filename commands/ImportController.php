<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Json;
use yii\base\InvalidParamException;
use app\models\News;
use app\models\Category;
use Yii;

class ImportController extends Controller
{
    /**
     * This command import news and categories from json formatted file
     * @param string $file path to json file.
     */
    public function actionJson($file)
    {
        $json = file_get_contents(getcwd().'\\'.$file);
        try {
            $importData = Json::decode($json, true);
        } catch (InvalidParamException $e) {
            echo 'Incorrect file format';
            return 1;
        }
        if($importData === null) {
            echo 'Incorrect file format or empty file';
            return 1;
        }
        $this->ImportCategory($importData);
        return 0;
    }

    private function CategoryObjToString($model)
    {
        return
            'id = '.$model->id.PHP_EOL.
            'name = '.$model->caption.PHP_EOL.
            'active = '.(($model->status === Category::STATUS_ACTIVE) ? 'true': 'false').PHP_EOL.
            'description = '.$model->description.PHP_EOL;
    }

    private function CategoryArrToString($item)
    {
        return
            'id = '.$item['id'].PHP_EOL.
            'name = '.$item['name'].PHP_EOL.
            'active = '.($item['active'] ? 'true': 'false').PHP_EOL;
    }

    private function ImportCategory($array)
    {
        foreach($array as $category) {

            // Skip inactive categories
            if (!$category['active']) {
                continue;
            }

            $model = Category::findOne($category['id']);

            $save = false;
            // Checking is this ID free for use
            if($model !== null) {
                // Asking user what he wants
                $question = 'Category with the same id alredy exists in data base:'.PHP_EOL.
                    'Data from database:'.PHP_EOL.
                    $this->CategoryObjToString($model).
                    'New data from file:'.PHP_EOL.
                    $this->CategoryArrToString($category);
                $this->stdout($question);
                $answer = $this->select(
                    'What you want to do?'.PHP_EOL.'(a - assign new id and save data, s - keep database data, d - keep id and save data)'.PHP_EOL,
                    [
                        'a'=>'assign new id and save data',
                        's'=>'keep database data',
                        'd'=>'keep id and save data'
                    ]);
                switch ($answer) {
                    case 'a':
                        $model = new Category();
                    case 'd':
                        $save = true;
                        break;
                    case 's':
                        break;
                }
            } else {
                $model = new Category();
                $model->id = $category['id'];
                $save = true;
            }
            // Saving data if it is needed
            if ($save) {
                $model->caption = $category['name'];
                $model->status = $category['active'] ? Category::STATUS_ACTIVE : Category::STATUS_INACTIVE;
                if ($model->save()) {
                    $this->stdout('Category: '.PHP_EOL.$this->CategoryObjToString($model).' saved sucesfully'.PHP_EOL);
                }
            }

            $this->ImportNews($category['news'], $model);

            if(isset($category['subcategories'])) {
                $this->ImportCategory($category['subcategories']);
            }
        }
    }
    private function NewsObjToSting($model)
    {
        return
            'id = '.$model->id.PHP_EOL.
            'title = '.$model->headline.PHP_EOL.
            'date = '.$model->date.PHP_EOL.
            'description = '.$model->description.PHP_EOL.
            'text = '.$model->content.PHP_EOL.
            'image = '.$model->image.PHP_EOL.
            'category_id = '.$model->category_id.PHP_EOL.
            'active = '.(($model->status === News::STATUS_ACTIVE) ? 'true': 'false').PHP_EOL;
    }

    private function NewsArrToString($item)
    {
        return
            'id = '.$item['id'].PHP_EOL.
            'title = '.$item['title'].PHP_EOL.
            'date = '.$item['date'].PHP_EOL.
            'image = '.$item['image'].PHP_EOL.
            'description = '.$item['description'].PHP_EOL.
            'text = '.$item['text'].PHP_EOL.
            'active = '.($item['active'] ? 'true': 'false').PHP_EOL;
    }
    private function ImportNews($array, $category)
    {
        foreach($array as $item) {

            if(!$item['active']) {
                continue;
            }
            $save = false;

            $model = News::findOne($item['id']);

            // Checking is this ID free for use
            if($model === null) {
                $save = true;
                $model = new News();
                $model->id = $item['id'];
            } else {
                // Asking user what he wants
                $question = 'News with the same id alredy exists in data base:'.PHP_EOL.
                    'Data from database:'.PHP_EOL.
                    $this->NewsObjToSting($model).
                    'New data from file:'.PHP_EOL.
                    $this->NewsArrToString($item).
                    'category_id = '.$category->id.PHP_EOL;
                $this->stdout($question);
                $answer = $this->select(
                    'What you want to do?'.PHP_EOL.'(a - assign new id and save data, s - keep database data, d - keep id and save data)'.PHP_EOL,
                    [
                        'a'=>'assign new id and save data',
                        's'=>'keep database data',
                        'd'=>'keep id and save data'
                    ]);
                switch ($answer) {
                    case 'a':
                        $model = new News();
                    case 'd':
                        $save = true;
                        break;
                    case 's':
                        break;
                }
            }

            // Saving data if it is needed
            if ($save) {
                $model->headline = $item['title'];
                $model->description = $item['description'];
                $model->date = $item['date'];
                $model->image = $item['image'];
                $model->content = $item['text'];
                $model->link('category', $category);
                $model->status = $item['active'] ? News::STATUS_ACTIVE : News::STATUS_INACTIVE;
                if($model->save()) {
                    $this->stdout($this->NewsObjToSting($model).'saved sucesfully'.PHP_EOL);
                }
            }

        }
    }
}