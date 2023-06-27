<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $re_pass;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Это имя пользователя уже занято.'],
            ['username', 'string', 'min' => 5, 'max' => 25],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 40],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот эмейл уже занят.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['password', 'checkPass'],

            ['re_pass', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Почта',
            'password' => 'Пароль',
            're_pass' => 'Повторите пароль',
            'about' => 'О себе'
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->image = "@web/images/avatar_ex.png";
        $user->status = 10;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        /* $user->generateEmailVerificationToken(); */

        return $user->save() && $this->sendEmail($user);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }

    public function checkPass($attributes)
    {
        if ($this->password == $this->re_pass) {
            return true;
        }
        return $this->addError($attributes, 'Пароли не совпадают');
    }
}
