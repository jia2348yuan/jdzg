<?php
/**
 * 应用公共文件 2017/07/20
 */

/**
 * 返回对象
 * @author zxd
 * @param $array 响应数据
 */
function resultArray($array)
{
    allowCrossDomain();
   //print_r($array);exit;
    if(isset($array['data'])) {
        $array['error'] = '';
        $code = 200;
    } elseif (isset($array['error'])) {
        $code = 400;
        $array['data'] = '';
    }
    return [
        'code'  => $code,
        'data'  => $array['data'],
        'error' => $array['error']
    ];


}


/**
 * 允许跨域 部署环境必须注释，防止跨域攻击
 * @author zxd
 */
function allowCrossDomain() {
    // 指定允许其他域名访问
    header('Access-Control-Allow-Origin:*');
    // 响应类型
    header('Access-Control-Allow-Methods:*');
    // 响应头设置
    header('Access-Control-Allow-Headers:x-requested-with,content-type');
}

/**
 * 调试方法
 * @author zxd
 * @param  array   $data  [description]
 */
function p($data,$die=1)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if ($die) die;
}

/**
 * @return int  返回当前时间戳
 */
function getTime()
{
    //return date('Y-m-d H:i:s');
    return time();
}

/**
 * 用户密码加密方法
 * @author zxd
 * @param  string $str      加密的字符串
 * @param  [type] $auth_key 加密符
 * @return string           加密后长度为32的字符串
 */
function user_md5($str, $auth_key = '')
{
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

/**
 * 写入日志
 * @author zxd
 * @param $log_content 日志内容
 */
function logger($log_content)
{
    $max_size = 100000;   //声明日志的最大尺寸

    $log_filename = "log.xml";  //日志名称

    //如果文件存在并且大于了规定的最大尺寸就删除了
    if(file_exists($log_filename) && (abs(filesize($log_filename)) > $max_size)){
        unlink($log_filename);
    }

    //写入日志，内容前加上时间， 后面加上换行， 以追加的方式写入
    file_put_contents($log_filename, date('Y-m-d H:i:s')." ".$log_content."\n", FILE_APPEND);

}