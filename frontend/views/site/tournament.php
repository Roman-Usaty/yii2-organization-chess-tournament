<?php

/** @var yii\web\View $this */

use common\models\Tournament;
use common\models\User;
use yii\bootstrap5\Html;
use yii\helpers\BaseUrl;
use yii\bootstrap5\ActiveForm;

use frontend\assets\IndexAsset;
use frontend\assets\TournamentAsset;

IndexAsset::register($this);
TournamentAsset::register($this);

$this->title = $tournament->name;
?>
<script>

</script>

<section>
    <h1><?= $tournament->name ?></h1>

    <p><?= $tournament->description ?></p>

    <p>Минмальный рейтинг для участия: <?= $tournament->minRank ?></p>
    <p>Максимальный рейтинг для участия: <?= $tournament->maxRank ?></p>

    <div class="row">
        <div class="col-lg-5">

            <?php !Yii::$app->user->isGuest ? $form = ActiveForm::begin(['id' => 'form-join']) : ""; ?>

            <?= !Yii::$app->user->isGuest ? $form->field($model, 'username')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false) : "" ?>

            <?= !Yii::$app->user->isGuest ? $form->field($model, 'rank')->hiddenInput(['value' => Yii::$app->user->identity->rank])->label(false) : "" ?>
            <?= !Yii::$app->user->isGuest ? (isset($error) ? '<div class="invalid-feedback mb-2" style="display:block;">' . $error[Yii::$app->user->identity->username][0] . '</div>' : '') : "" ?>

            <div class="form-group">
                <?= !Yii::$app->user->isGuest ? Html::submitButton('Участвовать', ['class' => 'btn btn-primary', 'name' => 'signup-button']) : "" ?>
            </div>
            <?php !Yii::$app->user->isGuest ? ActiveForm::end() : "" ?>

        </div>
    </div>
</section>
<section id="bracket">
    <div class="container">
        <div class="split split-one">
            <div class="round round-one current">
                <div class="round-details">Раунд 1<br /><span class="date"></span></div>
                <?= Html::ul($listOfPlayer, ['item' => function ($item, $index) {
                    return '<li class="team team-top">' . User::findIdentity($item->player)->username . '<span class="score">0</span> </li>';
                }, 'class' => 'matchup'])
                ?>

                <!-- <ul class="">
                    <li class="team team-top">test 2<span class="score">0</span></li>
                    <li class="team team-bottom"><span class="score">0</span></li>
                </ul> -->
                <!-- <ul class="matchup">
                    <li class="team team-top">North Carolina<span class="score">0</span></li>
                    <li class="team team-bottom">Florida State<span class="score">0</span></li>
                </ul>
                <ul class="matchup">
                    <li class="team team-top">NC State<span class="score">0</span></li>
                    <li class="team team-bottom">Maryland<span class="score">0</span></li>
                </ul>
                <ul class="matchup">
                    <li class="team team-top">Georgia Tech<span class="score">0</span></li>
                    <li class="team team-bottom">Georgia<span class="score">0</span></li>
                </ul>
                <ul class="matchup">
                    <li class="team team-top">Auburn<span class="score">0</span></li>
                    <li class="team team-bottom">Florida<span class="score">0</span></li>
                </ul>
                <ul class="matchup">
                    <li class="team team-top">Kentucky<span class="score">0</span></li>
                    <li class="team team-bottom">Alabama<span class="score">0</span></li>
                </ul>
                <ul class="matchup">
                    <li class="team team-top">Vanderbilt<span class="score">0</span></li>
                    <li class="team team-bottom">Gonzaga<span class="score">0</span></li>
                </ul> -->
            </div> <!-- END ROUND ONE -->

            <!-- <div class="round round-two current">
                <div class="round-details">Раунд 2<br /><span class="date"></span></div>
                <ul class="matchup">
                    <li class="team team-top">&nbsp;<span class="score">&nbsp;</span></li>
                    <li class="team team-bottom">&nbsp;<span class="score">&nbsp;</span></li>
                </ul>
                <ul class="matchup">
                    <li class="team team-top">&nbsp;<span class="score">&nbsp;</span></li>
                    <li class="team team-bottom">&nbsp;<span class="score">&nbsp;</span></li>
                </ul>
                <ul class="matchup">
                    <li class="team team-top">&nbsp;<span class="score">&nbsp;</span></li>
                    <li class="team team-bottom">&nbsp;<span class="score">&nbsp;</span></li>
                </ul>
                <ul class="matchup">
                    <li class="team team-top">&nbsp;<span class="score">&nbsp;</span></li>
                    <li class="team team-bottom">&nbsp;<span class="score">&nbsp;</span></li>
                </ul>
            </div> <!/-- END ROUND TWO --/>

            <div class="round round-three current">
                <div class="round-details">Раунд 3<br /><span class="date"></span></div>
                <ul class="matchup">
                    <li class="team team-top">&nbsp;<span class="score">&nbsp;</span></li>
                    <li class="team team-bottom">&nbsp;<span class="score">&nbsp;</span></li>
                </ul>
                <ul class="matchup">
                    <li class="team team-top">&nbsp;<span class="score">&nbsp;</span></li>
                    <li class="team team-bottom">&nbsp;<span class="score">&nbsp;</span></li>
                </ul>
            </div> <!/-- END ROUND THREE --/>
        </div>

        <div class="champion">
            <div class="semis-l current">
                <div class="round-details">Финал <br /><span class="date"></span></div>
                <ul class="matchup championship">
                    <li class="team team-top">&nbsp;<span class="vote-count">&nbsp;</span></li>
                    <li class="team team-bottom">&nbsp;<span class="vote-count">&nbsp;</span></li>
                </ul>
            </div>
            <div class="final current">
                <i class="fa fa-trophy"></i>
                <div class="round-details">Полуфинал <br /><span class="date"></span></div>
                <ul class="matchup championship">
                    <li class="team team-top">&nbsp;<span class="vote-count">&nbsp;</span></li>
                    <li class="team team-bottom">&nbsp;<span class="vote-count">&nbsp;</span></li>
                </ul>
            </div>
            <div class="semis-r current">
                <div class="round-details">3-4 место <br /><span class="date"></span></div>
                <ul class="matchup championship">
                    <li class="team team-top">&nbsp;<span class="vote-count">&nbsp;</span></li>
                    <li class="team team-bottom">&nbsp;<span class="vote-count">&nbsp;</span></li>
                </ul>
            </div>
        </div> -->
        </div>
</section>