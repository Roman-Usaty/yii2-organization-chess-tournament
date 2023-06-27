<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\BaseUrl;

$this->title = 'Создать турнир';
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста заполните поля для регистрации турнира:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'description')->textarea() ?>

            <?= $form->field($model, 'minRank')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'maxRank')->textInput(['type' => 'number'])  ?>

            <?= $form->field($model, 'isActive')->textInput(['type' => 'number'])  ?>

            <div class="form-group">
                <?= Html::submitButton('Зарегистрировать турнир', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>