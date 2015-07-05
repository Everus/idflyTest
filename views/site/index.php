<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
if(isset($cur_category)) {
    $this->title = 'idfly News app ->'.$cur_category->caption;
} else {
    $this->title = 'idfly News app';
}
?>
<div class="site-index container">
        <?php if(!empty($categories)): ?>
            <div class="row">
                <div class="col-md-3">
                    <nav>
                        <ul class="nav nav-pills nav-stacked" role="complementary">
                            <?php foreach ($categories as $category): ?>
                                <li
                                    <?php if (isset($cur_category))
                                        if ($cur_category->id === $category->id):
                                            ?>
                                            class="active"
                                        <?php endif;?>
                                    >
                                    <a href="<?= Url::toRoute(['site/category', 'id' => $category->id]) ?>"><?= $category->caption ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
                <?php if(!empty($news)): ?>
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
                        <?php
                        echo LinkPager::widget([
                            'pagination' => $pagination,
                        ]);
                        ?>
                    </div>
                <?php else: ?>
                    <?php if(isset($cur_category)): ?>
                        <div class="col-md-9">
                            <h2>No news in category <?= $cur_category->caption ?>.</h2>
                            <p>It's no new here. Return later.</p>
                        </div>
                    <?php else: ?>
                        <div class="col-md-9">
                            <h2>No news.</h2>
                            <p>It's no new here. Return later.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="jumbotron">
                <h1>Welcome</h1>
                <p class="lead">Welcome to our new site! It is empty now, but soon you will find here most exiting news on the web.</p>
            </div>
        <?php endif; ?>


</div>
