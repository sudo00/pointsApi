<?php

namespace app\controllers;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\rest\OptionsAction;

class BaseApiController extends Controller
{
    protected $collectionOptions = ['GET', 'OPTIONS'];
    protected $resourceOptions = ['GET', 'OPTIONS'];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
            'except' => ['options'],
        ];
        return array_merge($behaviors, [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => 'json',
                ],
            ],
            'corsFilter' => [
                'class' => Cors::className(),
                'cors' => [
                    'Access-Control-Allow-Origin' => [
                        '*',
                    ],
                    'Access-Control-Allow-Methods' => [
                        'POST',
                        'GET',
                        'OPTIONS',
                    ],
                    'Origin' => [
                        '*',
                    ],
                    'Access-Control-Request-Method' => [
                        'POST',
                        'GET',
                        'OPTIONS',
                    ],
                    'Access-Control-Request-Headers' => [
                        'Origin',
                        'Content-Type',
                    ],
                    'Access-Control-Allow-Headers' => [
                        'Authorization',
                        'Origin',
                        'Content-Type',
                    ],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return [
            'options' => [
                'class' => OptionsAction::className(),
                'collectionOptions' => $this->collectionOptions,
                'resourceOptions' => $this->resourceOptions,
            ],
        ];
    }
}
