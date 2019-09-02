<?php


namespace Cblink\Tongbushi;


use Cblink\Tongbushi\Exceptions\InvalidConfigException;
use Couchbase\Exception;
use GuzzleHttp\Middleware;
use Hanson\Foundation\AbstractAPI;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;

class Api extends AbstractAPI
{
    protected $app;

    protected $prefix;

    protected $host = '';

    protected $timestamp;

    public function __construct(Container $app)
    {
        $this->app = $app;
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

        $response = $this->getHttp()->json($url , $data);

        $response = json_decode($response->getBody()->getContents(), true);

        $this->checkErrorAndThrow($response);

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
        if(empty($this->prefix)){
            throw new Exception('未定义模块');
        }
        $consumerKey = $this->app->getConfig('consumer_key');

        if(empty($consumerKey)){
            throw new InvalidConfigException('未定义 consumer_key');
        }
        return sprintf('%s/%s/%s/%s', $this->host, $this->prefix, $consumerKey, $url);
    }

    /**
     * 头部设置
     *
     * @return mixed|void
     */
    public function middlewares()
    {
        // 1. Content-Type:
        $this->getHttp()->addMiddleware(Middleware::mapRequest(function(RequestInterface $r){
            return $r->withHeader('Content-Type', 'application/json');
        }));

        // 2. X-Timestamp
        $this->getHttp()->addMiddleware(Middleware::mapRequest(function(RequestInterface $r){
            return $r->withHeader('X-Timestamp', $this->getTimestamp());
        }));

        // 3. X-Partner
        $this->getHttp()->addMiddleware(Middleware::mapRequest(function(RequestInterface $r){
            if(!$this->app->getConfig('partner')){
                throw new InvalidConfigException('partner 无配置');
            }
            return $r->withHeader('X-Partner', $this->app->getConfig('partner'));
        }));
        // 4. X-Sign
        $this->getHttp()->addMiddleware(Middleware::mapRequest(function(RequestInterface $r){

            return $r->withHeader('X-Sign', $this->generateSign());
        }));
    }

    /**
     * 签名
     *
     * @return string
     */
    public function generateSign()
    {
        $data = json_encode($this->getData());
        $md5Str = $data.$this->getTimestamp(). $this->app->getConfig('secret');var_dump($md5Str);exit;
        return md5($md5Str);
    }

    /**
     * 时间戳
     *
     * @return int
     */
    public function getTimestamp()
    {
        if(empty($this->timestamp)){
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
        ],['body' => $data]);
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

    /**
     * 验证请求返回
     *
     * @param $result
     * @throws \Exception
     */
    private function checkErrorAndThrow($result)
    {
        if(!$result || $result['code'] != 0){
            throw new \Exception('请求失败请稍后重试');
        }
    }



}