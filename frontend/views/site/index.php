<?php

/** @var yii\web\View $this */

use frontend\assets\IndexAsset;
use yii\helpers\Url;
use yii\bootstrap5\Html;
use common\models\Tournament;

IndexAsset::register($this);

$this->title = 'Главная';
?>
<section class="themes align-content-center justify-content-between row mb-5">
    <div class="w-100 col-sm-5 active_themes">
        <h2 class="title_theme pb-4 text">Активные Турниры</h2>
        <?= Html::ul($tournament, ['item' => function ($item, $index) {
            return '<li class="list-group-item border-primary border-top border-bottom text">' .
                Html::a(Html::encode($item->name), Url::to(['site/tournament', 'id' => $item->id])) .
                '</li>';
        }, 'class' => 'list-group list-group-flush pt-3']) ?>
    </div>
    <div class="w-100 col-sm-5 active_themes">
        <h2 class="title_theme pb-4 text">Топ игроков по рейтингу</h2>
        <?= Html::ul($user, ['item' => function ($item, $index) {
            return '<li class="list-group-item border-primary border-top border-bottom text d-flex justify-content-between">' .
                Html::a(Html::encode($item->username)) . Html::a(Html::encode($item->rank)) .
                '</li>';
        }, 'class' => 'list-group list-group-flush pt-3']) ?>
    </div>
</section>