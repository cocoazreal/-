<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include "sendMessage/TopSdk.php";

/**
 * Class Register 注册类
 */
class Register extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index()
    {
        echo "<h1>REGISTER</h1>";
    }

    /**
     * 获取App上用户的注册手机号，发送短信验证码
     * @internal param $telephone
     */
    public function getRegTel()
    {
        $phone = $this->input->post('phone');
        $telephone = 'p'.$phone;
        if(empty($phone))
        {
            echo "error";
            exit(0);
        }
        // 阿里大鱼SDK
        date_default_timezone_set('Asia/Shanghai');
        $httpdns = new HttpdnsGetRequest;
        $client = new ClusterTopClient("4272","0ebbcccfee18d7ad1aebc5b135ffa906");
        $client->gatewayUrl = "http://api.daily.taobao.net/router/rest";
//        var_dump($client->execute($httpdns,"6100e23657fb0b2d0c78568e55a3031134be9a3a5d4b3a365753805"));

        $c = new TopClient;
        $c->appkey = '23293105';
        $c->secretKey = 'c8e46af46c375ceaf9cf264ace4733eb';
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("注册验证");

        $code = rand(100000,999999); // 6位验证码
        $code_string = "{'code':"."'".$code."'".",'product':'霸霸'}";
        $req->setSmsParam($code_string);
        $req->setRecNum($phone);
        $req->setSmsTemplateCode("SMS_4035562");
        $resp = $c->execute($req);

        $this->session->set_userdata($telephone, $code);
        $this->session->mark_as_temp($telephone, 60);
        echo "success";
    }

    /**
     *
     */
    public function getRegInfo()
    {
        $data = $this->input->post();
        $telephone = 'p'.$data['phone'];
        $code = $this->session->tempdata($telephone);
        if($code == $data['code'])
        {
            $is_reg = $this->user_model->addUser($data);
            if ($is_reg)
            {
                echo "success";
            }
            else
            {
                echo "the account exists";
            }
        }

        else
        {
            echo "error";
        }
    }
}