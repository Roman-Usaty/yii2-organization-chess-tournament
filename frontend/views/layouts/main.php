<?php

/** @var yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset;
use frontend\assets\MainAsset;
use common\models\User;
use yii\helpers\BaseUrl;
use yii\bootstrap5\Html;

AppAsset::register($this);
MainAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php $path; ?>
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/Icon.png']); ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <header class=" mb-5">
        <h1 class="d-none text">Главная Страница</h1>
        <div class="container-xl d-flex flex-column flex-xl-row  align-items-center justify-content-between pt-3 pb-3">
            <div class="logo mb-xl-0 mb-5">
                <a height="100%" href="<?= Yii::$app->homeUrl ?>">
                    <?= Html::img('@web/images/logo.png', ['alt' => 'Logo']) ?>
                </a>
            </div>
            <div class="account d-flex align-items-center mb-xl-0 mb-3">
                <?=
                Yii::$app->user->isGuest ? (Html::a('Войти', BaseUrl::to(['/site/login'], true), ['class' => 'login btn btn-outline-primary me-4 ms-4 text'])
                    . Html::a('Регистриция', BaseUrl::to(['/site/signup'], true), ['class' => 'register  btn btn-warning text'])
                ) : (' <div class="dropdown">'
                    . '<a class="text btn btn-primary dropdown-toggle profile" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false">'
                    . Html::img(Yii::$app->user->identity->image, ['alt' => 'svg', 'class' => 'ellipse'])
                    . Yii::$app->user->identity->username
                    . '</a>'
                    . Html::beginTag('ul', ['class' => 'dropdown-menu', 'aria-labelledby' => 'dropdownMenuLink'])
                    . '<li>' . Html::a('Профиль', BaseUrl::to(['/site/profile'], true), ['class' => 'dropdown-item']) . '</li>'
                    . '<li>' . Html::a('Создать турнир', BaseUrl::to(['/site/create-tournament'], true), ['class' => 'dropdown-item']) . '</li>'
                    . '<li>' . Html::a('Настройки', BaseUrl::to(['/site/settings'], true), ['class' => 'dropdown-item']) . '</li>'
                    . '<li>' . Html::beginForm(['/site/logout'], 'post', ['class' => 'drpdown-item logout-form']) . '</li>'
                    . '<li>' . Html::submitButton(
                        'Выйти',
                        ['class' => 'text logout']
                    ) . '</li>'
                    . Html::endForm()
                    . Html::endTag('ul')
                    . '</div>'
                )
                ?>


            </div>
        </div>
    </header>

    <main class="container-xl">
        <?= $content ?>
    </main>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>