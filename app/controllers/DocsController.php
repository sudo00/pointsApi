<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="Point Api",
 *   description="Описание работы с API точек интереса"
 * ),
 * 
 * @OA\Server(
 *   url="http://localhost/v1",
 *   description="Production server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="Bearer",
 *     type="http",
 *     scheme="bearer"
 * )
 * 
 * @SWG\SecurityScheme(
 *   securityDefinition="Bearer",
 *   type="apiKey",
 *   in="header",
 *   name="Authorization"
 * )
 */

class DocsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => 'genxoft\swagger\ViewAction',
                'apiJsonUrl' => Url::to(['docs/json']),
            ],
            'json' => [
                'class' => 'genxoft\swagger\JsonAction',
                'dirs' => [
                    Yii::getAlias('@app/controllers'),
                    Yii::getAlias('@app/modules/v1/controllers'),
                    Yii::getAlias('@app/modules/v1/models'),
                ],
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!YII_ENV_DEV) {
            throw new NotFoundHttpException();
        }
        return parent::beforeAction($action);
    }
}
