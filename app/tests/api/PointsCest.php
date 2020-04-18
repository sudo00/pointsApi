<?php
use Codeception\Util\HttpCode;
use app\modules\v1\components\enum\BillingError;

class PointsCest
{
    private function getToken()
    {
        return md5('secret' . date('Ymd'));
    }

    public function testSuccessGetAll(ApiTester $I)
    {
        $token = $this->getToken();
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
        $I->sendGET('points/get-all/');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'name' => 'string',
            'description' => 'string',
            'type' => 'string',
            'lon' => 'float',
            'lat' => 'float',
        ]);
    }

    public function testErrorCityGetAll(ApiTester $I)
    {
        $token = $this->getToken();
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
        $I->sendGET('points/get-all/', [
            'city' => 'dasdsad2321321',
        ]);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    public function testUnauthorizedGetAll(ApiTester $I)
    {
        $I->sendGet('points/get-all/');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    public function testSuccessGetNearest(ApiTester $I)
    {
        $token = $this->getToken();
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
        $I->sendGet('/points/get-nearest');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'name' => 'string',
            'description' => 'string',
            'type' => 'string',
            'lon' => 'float',
            'lat' => 'float',
        ]);
    }

    public function testErrorIpGetNearest(ApiTester $I)
    {
        $token = $this->getToken();
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
        $I->sendGet('/points/get-nearest', [
            'ip' => 'dasdsad2321321',
        ]);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    public function testUnauthorizedGetNearest(ApiTester $I)
    {
        $I->sendGet('/points/get-nearest');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    public function testSuccessIndexCreate(ApiTester $I)
    {
        $token = $this->getToken();
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
        $I->sendPost('/points/', [
            'name' => 'Новая точка',
            'description' => 'Новая точка',
            'type' => 'Тип точки',
            'lon' => '56.2123',
            'lat' => '55.234',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'name' => 'string',
            'description' => 'string',
            'type' => 'string',
            'lon' => 'float',
            'lat' => 'float',
        ]);
    }

    public function testIncorrectCoordiantesIndexCreate(ApiTester $I)
    {
        $token = $this->getToken();
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
        $I->sendPost('/points/', [
            'name' => 'Новая точка',
            'description' => 'Новая точка',
            'type' => 'Тип точки',
            'lon' => 'dsadsa',
            'lat' => 'dasdsd',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(422);
        $I->seeResponseMatchesJsonType([
            'field' => 'string',
            'message' => 'string'
        ]);
    }

    public function testSuccessIndexUpdate(ApiTester $I)
    {
        $token = $this->getToken();
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
        $I->sendPost('/points/', [
            'id' => '2',
            'name' => 'Новая точка',
            'description' => 'Новая точка',
            'type' => 'Тип точки',
            'lon' => '56.2123',
            'lat' => '55.2344',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'name' => 'string',
            'description' => 'string',
            'type' => 'string',
            'lon' => 'float',
            'lat' => 'float',
        ]);
    }

    public function testNotFoundIndexUpdate(ApiTester $I)
    {
        $token = $this->getToken();
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
        $I->sendPost('/points/', [
            'id' => 'dsadsad',
            'name' => 'Новая точка',
            'description' => 'Новая точка',
            'type' => 'Тип точки',
            'lon' => '56.2123',
            'lat' => '55.234',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function testIncorrectCoordiantesIndexUpdate(ApiTester $I)
    {
        $token = $this->getToken();
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
        $I->sendPost('/points/', [
            'id' => '2',
            'name' => 'Новая точка',
            'description' => 'Новая точка',
            'type' => 'Тип точки',
            'lon' => 'dsadsa',
            'lat' => 'dasdsd',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(422);
        $I->seeResponseMatchesJsonType(
            [
                'field' => 'string',
                'message' => 'string'
            ]
        );
    }

    public function testUnauthorizedIndex(ApiTester $I)
    {
        $I->sendPost('/points/');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }
}
