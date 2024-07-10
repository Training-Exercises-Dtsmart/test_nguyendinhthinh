<?php

namespace app\models;

use app\models\base\User as BaseUser;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "user".
 */
class User extends BaseUser
{
    const ROLE_STAFF = 1;
    const ROLE_HR = 0;

    public function formName()
    {
        return "";
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRole(){
        return $this->role;
    }

    public function findIdWithToken($token){
        return self::find()->where(['auth_key' => $token])->one();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * @throws Exception
     */
    public function setPassword($password){
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
