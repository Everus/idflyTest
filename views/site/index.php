<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
<div class="site-index container">
    <div class="row">
        <div class="col-md-3">
            <nav>
                <ul class="nav nav-pills nav-stacked" role="complementary">
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href=""><?= $category->caption ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>

        <div class="col-md-9" role="main">
            <?php foreach($news as $item): ?>
                <h2><a href="<?= Url::toRoute(['news/view', 'id' => $item->id]) ?>"><?= $item->headline ?></a></h2>
                <?php if(!empty($item->image)): ?>
                    <img src="<?= $item->image ?>">
                <?php endif; ?>
                <p><?= $item->description ?></p>
                <p>
                    <small>
                        <?= $item->date ?>
                    </small>
                </p>
                <p><a class="btn btn-default" href="<?= Url::toRoute(['news/view', 'id' => $item->id]) ?>">Подробнее &raquo;</a></p>
            <?php endforeach; ?>
        </div>
    </div>
</div>
