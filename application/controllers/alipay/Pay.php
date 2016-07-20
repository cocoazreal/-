<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: text/plain');

class Pay extends CI_Controller {

    public function payMoney()
    {
        // 获取支付金额
        $data = $this->input->post();

        $amount = $data['money'];
        $account = $data['account'];

        $total = floatval($amount);
        if(!$total){
            $total = 1;
        }

        // 支付宝合作者身份ID，以2088开头的16位纯数字
        $partner = "2088221697200153";
        // 支付宝账号
        $seller_id = 'shbb2015@126.com';
        // 商品网址
        $base_path = urlencode('http://115.28.73.97/');
        // 异步通知地址
        $notify_url = 'http://115.28.73.97/alipay/pay/notify_url';
        // 订单标题
        $subject = '预见麻麻豆充值';
        // 订单详情
        $body = '预见充值服务';
        // 订单号，示例代码使用时间值作为唯一的订单ID号
        $out_trade_no = date('YmdHis', time());

        $parameter = array(
            'service'        => 'mobile.securitypay.pay',   // 必填，接口名称，固定值
            'partner'        => $partner,                   // 必填，合作商户号
            '_input_charset' => 'UTF-8',                    // 必填，参数编码字符集
            'out_trade_no'   => $out_trade_no,              // 必填，商户网站唯一订单号
            'subject'        => $subject,                   // 必填，商品名称
            'payment_type'   => '1',                        // 必填，支付类型
            'seller_id'      => $seller_id,                 // 必填，卖家支付宝账号
            'total_fee'      => $total,                     // 必填，总金额，取值范围为[0.01,100000000.00]
            'body'           => $body,                      // 必填，商品详情
            'it_b_pay'       => '1d',                       // 可选，未付款交易的超时时间
            'notify_url'     => $notify_url,                // 可选，服务器异步通知页面路径
            'show_url'       => $base_path                  // 可选，商品展示网站
        );
        //生成需要签名的订单
        $orderInfo = $this->createLinkstring($parameter);
        //签名
        $sign = $this->rsaSign($orderInfo);
        //生成订单
        echo $orderInfo.'&sign="'.$sign.'"&sign_type="RSA"';
    }

    // 对签名字符串转义
    function createLinkstring($para)
    {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key.'="'.$val.'"&';
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        return $arg;
    }

    // 签名生成订单信息
    function rsaSign($data)
    {
        $priKey = "-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDtitUssAYa63b2NxBIf9TSxsY0rvcJdhB3tdzMd7zYv/ZztE7G
OnRlzaNEp7v5vlx8Nipcr2Tiuu9W+e0GwXGqyPHN+IyouHH0i1M9lEgRUJ+Bh1y4
PLwkLGW4fSAU9qtwW1+nUvwVurVJ5qlamAtyDy942ojy+6XklD4CsHy2FQIDAQAB
AoGAewOBn/HMR5aW9azQVus0OK9Np6zQK+sjXgslPBHqD0liBMtkXA2bh7j1izFe
DFiDpqOaToNaOHxcQ+uLzwrQpISSpswoQP4joOV/DUMceCD3YXRS62vOsUZq6u8/
/W8gUcFN6AFMb5VwGRe17wYuwh3Z1wqQVSTFWAqBlE7568ECQQD7sgfuIfyx2XoE
ZW/OYXvrdDoaLSe9CcaYvDAxkvYGkjB+msRD3dJUFV7rK9UocKs6PDEcqn0i0Wfm
spPylAlRAkEA8ZrWNtYyDaqNHpZ3QwR/QNxsASU3qujPVMSIkqybhCsielgAkMVD
1O9i6LsWuECZOPw0UMe0mn++U0YIBn4vhQJBANt7MtkggBeiunks72N7tLDsqGH9
MGLzLGAx8qC4M5wlTO6KDU3VGD1EaYIPthBOt7HPnVAPB5IeI0mUPdlR6BECQC/5
E7HdxCCMjerV66Zl/TKO0e9ESZJpGcn2IDwng9Wxju4GDU6xrK9aPSSDCZbaVNJI
2cZ0cPsAhHUagbtwUNUCQCqBUbVuLFP7EqGX2YkEEf1wPUO5oL89f1D0e9Vnl6Zd
8cPxbj8BSONznbEuvHb4AnOH5I0By7Z7XJu+CKd0a+4=
-----END RSA PRIVATE KEY-----";
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        $sign = urlencode($sign);
        return $sign;
    }

    public function notify()
    {
        $data = $this->input->post();
        $money = $data['money'] * 10;
        $account = $data['account'];
        $result = $this->db->select('useableMoney')->where('account', $account)->get('user')->row_array();
        $useableMoney = $money + $result['useableMoney'];
        $statu = $this->db->set('useableMoney', $useableMoney)->where('account', $account)->update('user');

        if($statu == '1')
        {
            echo "success";
        }
        else
        {
            echo "failed";
        }
    }

    public function notify_url()
    {
        $this->load->view('notify');
    }

}