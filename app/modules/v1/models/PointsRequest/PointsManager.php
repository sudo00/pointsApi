<?php

namespace app\modules\v1\models\PointsRequest;

use app\models\Points;
use Yii;

class PointsManager
{
    /**
     *  Манипулируемая точка интереса
     */
    private $point;

    /**
     * PointsManager конструктор
     */
    public function __construct()
    {
        $id = Yii::$app->request->post('id', null);
        if (empty($id)) {
            $this->point = new Points();
        } else {
            $this->point = Points::findOne($id);
        }
    }

    /**
     * GetPoints конструктор
     *
     * @return array Точки интереса.
     */
    public function execute()
    {
        $this->point->name = Yii::$app->request->post('name', null);
        $this->point->description = Yii::$app->request->post('description', null);
        $this->point->type = Yii::$app->request->post('type', null);
        $this->point->lat = Yii::$app->request->post('lat', null);
        $this->point->lon = Yii::$app->request->post('lon', null);
        $this->point->save();
        return $this->point;
    }
}
