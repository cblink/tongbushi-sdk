<?php


namespace Cblink\Tongbushi;

use Cblink\Tongbushi\Exceptions\InvalidConfigException;
use Exception;
use GuzzleHttp\Middleware;
use Hanson\Foundation\AbstractAPI;
use Psr\Http\Message\RequestInterface;

class Api extends AbstractAPI
{
    /** @var Tongbushi */
    protected $app;

    protected $prefix;

    protected $host;

    protected $timestamp;

    /**
     * @var array
     */
    private $data;

    public function __construct($app)
    {
        $this->app = $app;
        $this->host = $this->app->getConfig('debug') ? 'https://syncopen.test.meituan.com':'';
    }

    /**
     * 请求
     *
     * @param $url
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function request($url, $data)
    {
        $this->setData($data);

        $url = $this->getRequestUrl($url);

        $http =$this->getHttp();

        $http->addMiddleware($this->headerMiddleware([
            'Content-Type' => 'application/json',
            'X-Timestamp' => $this->getTimestamp(),
            'X-Partner' => $this->app->getConfig('partner'),
            'X-Sign' => $this->generateSign(),
        ]));

        $response = $http->json($url, $data);

        $response = json_decode($response->getBody()->getContents(), true);

        return $response;
    }

    /**
     * 设置 url
     *
     * @param $url
     * @return string
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function getRequestUrl($url)
    {
        if (empty($this->prefix)) {
            throw new Exception('未定义模块');
        }

        return sprintf('%s/%s/%s', $this->host, $this->prefix, $url);
    }

    /**
     * 签名
     *
     * @return string
     */
    public function generateSign()
    {
        $data = json_encode($this->getData());

        return md5($data.$this->getTimestamp(). $this->app->getConfig('secret'));
    }

    /**
     * 时间戳
     *
     * @return int
     */
    public function getTimestamp()
    {
        if (empty($this->timestamp)) {
            $this->timestamp = time();
        }

        return $this->timestamp;
    }

    /**
     * 格式化数据
     *
     * @param $data
     */
    public function setData($data)
    {
        $this->data= array_merge([
            'consumerKey' => $this->app->getConfig('consumer_key'),
            'companyOuid' => $this->app->getConfig('company_ouid'),
        ], ['body' => $data]);
    }

    /**
     * 获取参数
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 请求接口模块
     *
     * @param $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }
}
