<?php

namespace frontend\controllers;

use common\models\ListOfPlayer;
use Yii;

use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

use common\models\LoginForm;
use common\models\User;
use common\models\Tournament;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\SettingsForm;
use frontend\models\JoinTournamentForm;
use frontend\models\TournamentForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $tournament;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $tournament = Tournament::find()->orderBy('id desc')->limit(10)->where(['isActive' => 1])->all();
        $user = User::find()->orderBy('rank ASC')->limit(10)->where(['status' => 10])->all();
        return $this->render('index', [
            'tournament' => $tournament,
            'user' => $user
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionProfile()
    {
        $profile = User::findOne(['username' => \Yii::$app->user->identity->username]);

        return $this->render('profile', [
            'model' => $profile,
        ]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Благодарим вас за регистрацию. Пожалуйста, проверьте свой почтовый ящик на наличие подтверждающего письма.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    /**
     * Displays settings page 
     * 
     * @return string
     */

    public function actionSettings()
    {
        $settings = new SettingsForm();
        $settings->username = Yii::$app->user->identity->username;
        $user = User::findOne(['username' => \Yii::$app->user->identity->username]);

        if (\Yii::$app->request->isPost) {
            $settings->username = Yii::$app->request->post()['SettingsForm']['username'];
            $settings->imageFile = UploadedFile::getInstance($settings, "imageFile");

            if ($settings->upload()) {
                Yii::$app->user->identity->username = $settings->username;
                return $this->render('profile', [
                    'model' => $user,
                ]);
            }
        }

        return $this->render('settings', [
            'model' => $settings,
        ]);
    }

    public function actionTournament()
    {
        $tournamentId = Yii::$app->request->get('id');
        if ($tournamentId == null || $tournamentId == '') {
            return $this->render('index', [
                'tournament' => $this->tournament
            ]);
        }



        $tournament = Tournament::findOne(['id' => $tournamentId]);
        $listOfPlayer = ListOfPlayer::find()->where(['tournament' => $tournament->id])->all();
        $joinTournamentForm = new JoinTournamentForm();


        if (Yii::$app->user->isGuest) {
            return $this->render('tournament', [
                'tournament' => $tournament,
                'model' => $joinTournamentForm,
                'error' => [],
            ]);
        }

        if (ListOfPlayer::findBySql("SELECT * FROM `listOfPlayer` WHERE `tournament` = :tournamentId AND `player` = :playerId", [
            ':tournamentId' => $tournament->id,
            ':playerId' => Yii::$app->user->identity->id,
        ])->one() != null) {

            return $this->render('tournament', [
                'tournament' => $tournament,
                'model' => $joinTournamentForm,
                'error' => [
                    Yii::$app->user->identity->username => [
                        "Вы уже зарегитрированы в турнире"
                    ]
                ],
                'listOfPlayer' => $listOfPlayer
            ]);
        }
        if ($joinTournamentForm->load(Yii::$app->request->post())) {
            $joinTournamentForm->join(Yii::$app->user->identity->id, $tournamentId);

            return $this->render('tournament', [
                'tournament' => $tournament,
                'model' => $joinTournamentForm,
                'error' => $joinTournamentForm->errors != [] ? $joinTournamentForm->errors : null,
                'listOfPlayer' => $listOfPlayer
            ]);
        }


        return $this->render('tournament', [
            'tournament' => $tournament,
            'model' => $joinTournamentForm,
            'listOfPlayer' => $listOfPlayer
        ]);
    }

    public function actionCreateTournament()
    {
        $tournamentForm = new TournamentForm();

        if (\Yii::$app->request->isPost && $tournamentForm->load(Yii::$app->request->post())) {
            $tournamentForm->author = Yii::$app->user->identity->id;

            if ($tournamentForm->createTournament()) {
                $tournament = Tournament::find()->orderBy('id desc')->limit(10)->where(['isActive' => 1])->all();
                $user = User::find()->orderBy('rank ASC')->limit(10)->where(['status' => 10])->all();
                return $this->render('index', [
                    'tournament' => $tournament,
                    'user' => $user
                ]);
            }
            return $this->render('createTournament', [
                'model' => $tournamentForm,
                'error' => $tournamentForm->errors != [] ? $tournamentForm->errors : null
            ]);
        }

        return $this->render('createTournament', [
            'model' => $tournamentForm
        ]);
    }
}
