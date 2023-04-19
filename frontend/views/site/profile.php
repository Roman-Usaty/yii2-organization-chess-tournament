<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use frontend\assets\ProfileAsset;

ProfileAsset::register($this);
$this->title = "Профиль";
?>

<div class="d-flex justify-content-around">
    <div class="profile__image d-flex flex-column">
        <?= Html::img(Yii::$app->user->identity->image, ['alt' => 'Аватар', 'class' => 'mb-4']) ?>
        <span><?= Html::encode(Yii::$app->user->identity->username) ?></span>
    </div>
</div>