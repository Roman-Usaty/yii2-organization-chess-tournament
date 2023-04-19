<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\web\UploadedFile;
use Error;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Signup form
 */
class SettingsForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $username;
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['imageFile', 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }


    public function upload()
    {
        if ($this->validate()) {

            $user = $this->getUser(Yii::$app->user->identity->username);

            $fileName = hash('sha256', $user->id);

            $user->image = '@web/uploads/' . $fileName . $this->imageFile->extension;

            $this->deleteCopyImage($fileName);
            $this->imageFile->saveAs($user->image);

            $user->save();
            return true;
        } else {
            return false;
        }
    }

    public function deleteCopyImage($nameWithoutExtensions)
    {
        $obj = $this;
        $files = \yii\helpers\FileHelper::findFiles('uploads/', [
            'filter' => array($obj, 'findFileByName'),
        ]);
        foreach ($files as $file) {
            unlink($file);
        }
    }

    public function findFileByName($path)
    {
        $path = str_replace('\\', '', $path);
        $path = stristr($path, '/');
        $extensions = stristr($path, '.');
        $extensions = str_replace('.', '', $extensions);
        $path = stristr($path, '.', true);
        $path = str_replace('/', '', $path);
        if ($path == hash('sha256', $this->id) && $extensions !== $this->imageFile->extension) {
            return true;
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
