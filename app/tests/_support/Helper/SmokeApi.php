<?php

namespace Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Yii;

class SmokeApi extends Module
{

    public function _before(TestInterface $test)
    {
        parent::_before($test);

        /** @var Yii2 $yii2 */
        $yii2 = $this->getModule('Yii2');
        
        $yii2->client->setServerParameters([
            'REMOTE_ADDR' => '46.146.71.33',
            'SCRIPT_FILENAME' => '/index.php',
            'SCRIPT_NAME' => '/index.php'
        ]);
    }
}
