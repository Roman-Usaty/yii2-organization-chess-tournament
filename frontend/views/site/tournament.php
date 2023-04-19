<?php

/** @var yii\web\View $this */

use common\models\Tournament;

use yii\bootstrap5\Html;
use yii\helpers\BaseUrl;
use yii\bootstrap5\ActiveForm;

use frontend\assets\IndexAsset;

IndexAsset::register($this);

$this->title = $tournament->name;
?>

<section>
    <h1><?= $tournament->name ?></h1>

    <p><?= $tournament->description ?></p>

    <p>Минмальный рейтинг для участия: <?= $tournament->minRank ?></p>
    <p>Максимальный рейтинг для участия: <?= $tournament->maxRank ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-join']); ?>

            <?= $form->field($model, 'username')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false) ?>

            <?= $form->field($model, 'rank')->hiddenInput(['value' => Yii::$app->user->identity->rank])->label(false) ?>

            <?= isset($error) ? '<div class="invalid-feedback mb-2" style="display:block;">' . $error['Noobile'][0] . '</div>' : ''
            ?>

            <div class="form-group">
                <?= Html::submitButton('Участвовать', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>