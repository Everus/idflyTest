<?php
/* @var $this yii\web\View */
$this->title = $post->headline;
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