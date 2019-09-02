<?php


namespace Cblink\Tongbushi;


class Client extends Api
{

    /**
     * 订单相关
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function getOrder($url, $data)
    {
        return $this->setPrefix('openapi/trade')->request($url, $data);
    }

    /**
     * 门店商品
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function getProduct($url, $data)
    {
        return $this->setPrefix('openapi/base')->request($url, $data);
    }
}