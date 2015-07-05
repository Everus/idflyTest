<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = $post->headline;
?>
<div class="site-index container">
    <div class="row">
        <div class="col-md-3">
            <nav>
                <ul class="nav nav-pills nav-stacked">
                    <?php foreach ($categories as $category): ?>
                        <li
                            <?php if($category->id === $post->category->id): ?>
                                class="active"
                            <?php endif; ?>>
                            <a href="<?= Url::toRoute(['site/category', 'id' => $category->id]) ?>"><?= $category->caption ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>

        <div class="col-md-9">
            <h2><?= $post->headline ?></h2>
            <?php if(!empty($post->image)): ?>
                <img src="<?= $post->image ?>">
            <?php endif; ?>
            <p><?= $post->content ?></p>
            <p>
                <small>
                    <?= $post->date ?>
                </small>
            </p>
        </div>
    </div>
</div>