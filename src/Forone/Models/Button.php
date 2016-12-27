<?php
namespace Forone\Models;

/**
 * 用作全局按钮数据对象
 * User: Mani Wang
 * Date: 12/27/16
 * Time: 3:50 PM
 * Email: mani@forone.co
 */
class Button
{
    /**
     * 用作POST传参的数据
     */
    public $data;
    /**
     * 其它的配置信息
     */
    public $config;

    public function __construct($name, $uri, $id = '', $method = 'GET', $data = [], $extraConfig = [])
    {
        $config = [
            'name'   => $name,
            'uri'    => $uri,
            'method' => $method,
            'id'     => $id
        ];
        if (($id && is_array($id)) || ($method && is_array($method))) {
            $extraConfig = is_array($id) ? $id : $method;
        }
        if (!empty($extraConfig)) {
            $config = array_merge($config, $extraConfig);
        }
        $this->config = $config;
    }
}