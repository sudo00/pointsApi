<?php
namespace app\components;

use yii\base\Model;
use yii\httpclient\Client;

class Request extends Model
{

    /**
     * Адрес сервера
     *
     * @var string
     */
    public $resource;

    /**
     * URI для API сервера
     *
     * @var string
     */
    private $uri;

    /**
     * Ответ на запрос
     *
     * @var string
     */
    private $answer;

    /**
     * Формат ответа в виде массива
     *
     * @var string
     */
    private $answerArrayType = false;

    /**
     * Метод запроса
     *
     * @var string
     */
    private $method = 'GET';

    /**
     * Массив данных запроса
     */
    private $content = [];

    /**
     * Массив параметров запроса
     */
    private $data = [];

    /**
     * Заголовки запроса
     */
    private $headers = YII_DEBUG ? ['Content-type' => 'application/x-www-form-urlencoded'] : [];

    /**
     * Заполнить данные
     */
    public function headers($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Использовать GET
     */
    public function useGET()
    {
        $this->method = 'GET';
        return $this;
    }

    /**
     * Использовать POST
     */
    public function usePOST()
    {
        $this->method = 'POST';
        return $this;
    }

    /**
     * Использовать PUT
     */
    public function usePUT()
    {
        $this->method = 'PUT';
        return $this;
    }

    /**
     * Использовать формат ответа в виде массива
     */
    public function useAnswerArrayType()
    {
        $this->answerArrayType = true;
        return $this;
    }

    /**
     * Заполнить URI
     */
    public function uri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Заполнить данные
     */
    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Заполнить данные
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    protected function connectClient($url, $content, $data, $headers, $method = 'GET')
    {
        $result = false;

        $client = new Client();

        $request = $client->createRequest()
            ->setMethod($method)
            ->setUrl($url)
            ->setContent(json_encode($content))
            ->setData($data)
            ->setHeaders($headers);

        $response = $request->send();

        if ($response->isOk) {
            $result = json_decode($response->content, $this->answerArrayType);
        }

        return $result;
    }

    /**
     * Совершает запрос
     * @return mixed
     */
    public function fire()
    {
        // генерация запроса для curl
        $url = $this->resource . $this->uri;
        $this->answer = $this->connectClient($url, $this->content, $this->data, $this->headers, $this->method);

        return $this->answer;
    }
}
