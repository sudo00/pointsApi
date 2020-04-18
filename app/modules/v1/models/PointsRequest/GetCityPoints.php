<?php

namespace app\modules\v1\models\PointsRequest;

use app\models\Points;
use Yii;

/**
 * @OA\Schema(
 *     description="Получение точек интереса в городе"
 * )
 */
class GetCityPoints
{
    /**
     * 24 часа, время хранения кэша
     */
    const CACHE_TIME = 86400;

    /**
     *  Стандартный лимит точек
     */
    const DEFAULT_LIMIT = 50;

    /**
     *  Стандартное смещение точек
     */
    const DEFAULT_PAGINATION = 0;

    /**
     *  Стандартное смещение точек
     */
    const DEFAULT_CITY = null;

    /**
     * GetCityPoints конструктор
     */
    public function __construct()
    {
        $this->limit = Yii::$app->request->get('limit', self::DEFAULT_LIMIT);
        $this->pagination = Yii::$app->request->get('pagination', self::DEFAULT_PAGINATION);
        $this->city = Yii::$app->request->get('city', $this->getCityByIp());
    }

    /**
     *  Основной метод
     *
     * @return array Точки интереса.
     */
    public function execute()
    {
        $coordinates = $this->getCoordinates();
        return Points::performNearestPoints($coordinates, null, $this->limit, $this->pagination);
    }

    /**
     *  Получение города по IP
     *
     * @return string
     * @throws HttpException
     */
    private function getCityByIp()
    {
        $ip = Yii::$app->request->userIP;
        $result = Yii::$app->ipApiRequest
            ->uri(Yii::$app->request->userIP)
            ->useGET()
            ->fire();

        if (isset($result->status) && $result->status == 'fail') {
            throw new \yii\web\HttpException(404, 'Не найден город пользователя по ip ' . $ip);
        }

        return $result->city;
    }

    /**
     *  Получение координат пользователя по городу
     *
     * @return array
     * @throws HttpException
     */
    private function getCoordinates()
    {

        $coordinates = Yii::$app->dataCache->get('coordinates_' . $this->city);
        if (!$coordinates) {
            $result = Yii::$app->locationIQRequest
                ->uri("search.php?key=320dde8aa98ec7&city=$this->city&format=json")
                ->useGET()
                ->fire();

            if (!isset($result[0]->boundingbox)) {
                throw new \yii\web\HttpException(404, 'Город ' . $this->city . ' не найден.');
            }
            $result = $result[0]->boundingbox;
            $coordinates = [
                'minLat' => $result[0],
                'maxLat' => $result[1],
                'minLon' => $result[2],
                'maxLon' => $result[3],
            ];
            Yii::$app->dataCache->set('coordinates_' . $this->city, $coordinates, self::CACHE_TIME);
        }
        return $coordinates;
    }
}
