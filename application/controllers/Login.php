<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Login 登录类
 */
class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index()
    {
        echo "<h1>LOGIN</h1>";
    }

    /**
     * 检查登陆信息
     */
    public function checkLogin()
    {
        $data = $this->input->post();
        $data = array_filter($data,"trim"); // 去除空格

        $req = $this->user_model->checkLoginInfo($data);

        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }


    /**
     * 创建登陆状态
     */
    public function createCredit()
    {
        $account = $this->input->post('account');
        if(empty($account))
        {
            echo 'error';
            exit(0);
        }
        else
        {
            $credit = $this->create_password(8);
            $this->user_model->setCredit($account, $credit);
            echo $credit;
        }

    }


    /**
     *自动登录
     */
    public function autoLogin()
    {
        $data = $this->input->post();
        if(empty($data))
        {
            exit(0);
        }
        else
        {
            $req = $this->user_model->checkAutoLogin($data['account'], $data['token'], $data['uuid']);
            echo $req;
        }
    }

    /**
     * 退出登录
     */
    public function logOut()
    {
        $account = $this->input->post('account');
        $this->user_model->userQuit($account);
    }


    /**
     * 生成随机字符串
     */
    function create_password($pw_length)
    {
        $randPwd = "";
        for ($i = 0; $i < $pw_length; $i++)
        {
            $randPwd .= chr(mt_rand(33, 126));
        }
        return $randPwd;
    }
}