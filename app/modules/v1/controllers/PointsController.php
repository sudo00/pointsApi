<?php

namespace app\modules\v1\controllers;

use app\controllers\BaseApiController;
use app\modules\v1\models\PointsRequest\GetCityPoints;
use app\modules\v1\models\PointsRequest\GetNearestPoints;
use app\modules\v1\models\PointsRequest\PointsManager;
use yii\filters\VerbFilter;

/**
 * Class CreateRouterRequestController
 * @package app\modules\v1\controllers
 */
class PointsController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return array_merge($behaviors, [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-all' => ['get'],
                    'get-nearest' => ['get'],
                    'index' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/points/",
     *     tags={"Points"},
     *     security={{"Bearer":{}}},
     *     summary="Создаем, изменяем точки интереса",
     *     description="Для того чтобы создать новую точку, нужно указать все параметры, кроме id. Для обновления записи, указываем все параметры и id записи, которую нужно обновиьть.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "description", "type", "lon", "lat"},
     *                 @OA\Property(
     *                     property="id",
     *                     description="Id записи точки интереса",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     description="Название точки интереса",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     description="Описание точки интереса",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     description="Тип точки интереса",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="lon",
     *                     description="Долгота точки интереса",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="lat",
     *                     description="Широта точки интереса",
     *                     type="number"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200",
     *         description="Данные успешно получены",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="type",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="lon",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="lan",
     *                          type="integer"
     *                      ),
     *                  ),
     *            )
     *        ),
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Точка интереса не найдена",
     *     ),
     * )
     */
    public function actionIndex()
    {
        $point = new PointsManager();
        return $point->execute();
    }

    /**
     * @OA\Get(
     *     path="/points/get-all",
     *     tags={"Points"},
     *     summary="Получаем точки интереса в городе",
     *     description="Получение точек интереса в городе",
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         in="query",
     *         name="limit",
     *         required=false,
     *         description="Лимит вывода точек интереса",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="pagination",
     *         description="Смещение точек интереса",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="city",
     *         required=false,
     *         description="Город, в котором ищем точки интереса",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="200",
     *         description="Данные успешно получены",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="type",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="lon",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="lan",
     *                          type="integer"
     *                      ),
     *                  ),
     *            )
     *        ),
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Не найдены координаты города Пермь",
     *     ),
     * )
     */
    public function actionGetAll()
    {
        $logic = new GetCityPoints();
        return $logic->execute();
    }

    /**
     * @OA\Get(
     *     path="/points/get-nearest",
     *     tags={"Points"},
     *     security={{"Bearer":{}}},
     *     summary="Получаем точки интереса рядом с пользователем",
     *     description="Получение точек интереса рядом с пользователем",
     *     @OA\Parameter(
     *         in="query",
     *         name="ip",
     *         required=false,
     *         description="IP пользователя",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="radius",
     *         required=false,
     *         description="Радиус поиска точек интереса от пользователя",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200",
     *         description="Данные успешно получены",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="type",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="lon",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="lan",
     *                          type="integer"
     *                      ),
     *                  ),
     *            )
     *        ),
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Не найдены координаты пользователя по ip 45.321.12.1",
     *     ),
     * )
     */
    public function actionGetNearest()
    {
        $logic = new GetNearestPoints();
        return $logic->execute();
    }
}
