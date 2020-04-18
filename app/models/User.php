<?php

namespace app\models;

use yii\base\BaseObject;
use yii\web\IdentityInterface;

class User extends BaseObject implements IdentityInterface
{
    private $token;

    public function __construct($token, $config = [])
    {
        parent::__construct($config);
        $this->token = $token;
    }

    /**
     * @inheritdoc
     */

    public static function findIdentity($id)
    {
        return null;
    }

    /**
     * @inheritdoc
     */

    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($token == md5('secret' . date('Ymd'))) {
            return new self($token);
        }
    }

    /**
     * @inheritdoc
     */

    public function getId()
    {
        return '';
    }

    /**
     * @inheritdoc
     */

    public function getAuthKey()
    {
        return '';
    }

    /**
     * @inheritdoc
     */

    public function validateAuthKey($authKey)
    {
        return '12345';
    }

    public function getToken()
    {
        return $this->token;
    }
}
