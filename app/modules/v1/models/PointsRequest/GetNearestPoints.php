<?php

namespace app\modules\v1\models\PointsRequest;

use app\models\Points;
use Yii;

class GetNearestPoints
{
    /**
     *  Стандартное радиус обнаружения точек
     */
    const DEFAULT_RADIUS = 1;

    /**
     *  IP пользователя
     */
    private $ip;

    /**
     *  Радиус в киллометрах от пользователя
     */
    private $radius;

    /**
     * GetNearestPoints конструктор
     */
    public function __construct()
    {
        $this->ip = Yii::$app->request->get('ip', null) ?? Yii::$app->request->userIP;
        $this->radius = Yii::$app->request->get('radius', self::DEFAULT_RADIUS);
    }

    /**
     *  Основной метод
     *
     * @return array Точки интереса.
     */
    public function execute()
    {
        $coordinates = $this->getCoordinates();
        return Points::performNearestPoints($coordinates, $this->radius);
    }

    /**
     *  Получение координат пользователя по IP
     *
     * @return array
     * @throws HttpException
     */
    private function getCoordinates()
    {
        $result = Yii::$app->ipApiRequest
            ->uri($this->ip)
            ->useGET()
            ->fire();

        if (isset($result->status) && $result->status == 'fail') {
            throw new \yii\web\HttpException(404, 'Не найдены координаты пользователя по ip ' . $this->ip);
        }

        return [
            'minLat' => $result->lat,
            'maxLat' => $result->lat,
            'minLon' => $result->lon,
            'maxLon' => $result->lon,
        ];
    }
}
