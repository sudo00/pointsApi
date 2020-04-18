<?php

namespace app\models;

use Yii;

class Points extends \yii\db\ActiveRecord
{

    /**
     *  Километров в 1 градусе широты или долготы
     */
    const DEGREE = 111;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inf_points';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'type', 'lon', 'lat'], 'required'],
            [['name', 'description', 'type'], 'string', 'max' => 255],
            [['lon', 'lat'], 'double'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'name' => 'Название',
            'description' => 'Описание',
            'type' => 'Тип',
            'lon' => 'Долгота',
            'lat' => 'Широта',
        ];
    }

    public static function findOne($id)
    {
        $point = parent::findOne($id);
        if (empty($point)) {
            throw new \yii\web\HttpException(404, 'Точка интереса не найдена');
        }
        return $point;
    }


    /**
     *  Получение точек интереса в рамках диапазона координат
     * 
     * @param array $coordinates
     * @param int $distance
     * @param int $limit
     * @param int $pagination
     * 
     * @return array
     */
    public static function performNearestPoints($coordinates, $distance = null, $limit = null, $pagination = null)
    {
        $distance = $distance ?  $distance / self::DEGREE : null;
        $query = self::find()
            ->where(['>', 'lat', $coordinates['minLat'] - ($distance ?? 0)])
            ->andWhere(['<', 'lat', $coordinates['maxLat'] + ($distance ?? 0)])
            ->andWhere(['>', 'lon', $coordinates['minLon'] - ($distance ?? 0)])
            ->andWhere(['<', 'lon', $coordinates['maxLon'] + ($distance ?? 0)])
            ->orderBy('name');
        
        if (isset($limit)) {
            $query->limit($limit);
        }
        if (isset($pagination)) {
            $query->offset($pagination);
        }
        return $query->all();
    }
}
