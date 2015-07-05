<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
$this->title = 'idfly News app Admin Panel';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Admin Panel</h1>

        <p class="lead">Welcome to idfly Test News App admin panel!</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Category CRUD</h2>

                <p>Here you can create, read,update and update your categories.</p>

                <p><a class="btn btn-default" href="<?= Url::toRoute(['admin/category']) ?>">Category CRUD &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>News CRUD</h2>

                <p>Here you can create, read,update and update your news.</p>

                <p><a class="btn btn-default" href="<?= Url::toRoute(['admin/news']) ?>">News CRUD &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
