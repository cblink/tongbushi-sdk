<?php


namespace Cblink\Tongbushi;

class Client extends Api
{
    /**
     * 订单相关
     *
     * @param $url
     * @param $data
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws Exceptions\InvalidConfigException
     */
    public function getOrder($url, $data)
    {
        return $this->setPrefix('openapi/trade')->request($url, $data);
    }

    /**
     * 门店商品
     *
     * @param $urle
     * @param $data
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws Exceptions\InvalidConfigException
     */
    public function getProduct($url, $data)
    {
        return $this->setPrefix('openapi/base')->request($url, $data);
    }
}
