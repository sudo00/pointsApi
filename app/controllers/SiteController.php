<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actionFlush($key)
    {

        if ($key === 'adsadsadsad' && Yii::$app->dataCache->flush()) {
            $result = 'Cache flushed';
        } else {
            $result = 'Error flushing cache';
        }

        return $result;
    }
}
