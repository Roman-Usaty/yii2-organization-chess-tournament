<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\BaseUrl;
use frontend\assets\ProfileAsset;


/* @var $this yii\web\View */
/* @var $model app\models\Users*/
/* @var $form ActiveForm */

$this->title = 'Настройки профиля';
ProfileAsset::register($this);
?>
<div class="site-settings">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>
    <div class="profile d-flex flex-column align-items-center m-auto">
        <?= Html::img(Yii::$app->user->identity->image, ['alt' => 'Аватар', 'class' => 'profile__image mb-5']) ?>


        <?= $form->field($model, 'imageFile')->fileInput() ?>

        <div class="profile__name d-flex flex-column mt-5 mb-5">
            <?= $form->field($model, 'username')->textInput() ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-outline-primary btn-save']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>