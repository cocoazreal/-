<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index()
    {
        echo "<h1>用户信息</h1>";
    }

    /**
     * 根据账号获得个人信息
     * 主要是账号 总金额 保障金 可用金额
     */
    public function getUserMoney()
    {
        $account = $this->input->post('account');
        $req = $this->user_model->getUserMoney($account);
        $json_req = json_encode($req, JSON_UNESCAPED_UNICODE);
        echo $json_req;
    }


    /**
     * 修改密码
     */
    public function changePassword()
    {
        $data = $this->input->post();
        if(empty($data))
        {
            exit(0);
        }
        else
        {
            $req = $this->user_model->changePasswd($data);
            echo $req;
        }
    }

    /**
     * 修改账号
     */
    public function changeAccount()
    {
        $getData = $this->input->post();
        $req = $this->user_model->changeAccount($getData);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 关注其他用户
     */
    public function attachPeople()
    {
        $data = $this->input->post();
        $req = $this->user_model->attachPeople($data);
        echo $req;
    }

    /**
     * 关注事件
     */
    public function attachEvent()
    {
        $data = $this->input->post();
        $req = $this->user_model->attachEvent($data);
        echo $req;
    }

    /**
     * 进入他人主页时获得他人的信息
     */
    public function getOtherUserInfo()
    {
        $data = $this->input->post();
        $req = $this->user_model->getOtherUserInfo($data);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 用户头像
     */
    public function getUserImage()
    {
        $getData = $this->input->post('img');
        $account = $this->input->post('account');
        $orientation = $this->input->post('orientation');

        $userData = $this->db->select('userId')->where('account', $account)->get('user')->row_array();
        $userId = $userData['userId'];

        $data = substr($getData, 22);
        $tmpData = base64_decode($data);
        $path = FCPATH . 'userImages/' . $userId . '.jpg';
        file_put_contents($path, $tmpData);

        $image = imagecreatefromstring($tmpData);

        if($orientation)
        {
            switch ($orientation)
            {
                case '8':
                    $image = imagerotate($image, 90, 0);
                    break;
                case '3':
                    $image = imagerotate($image, 180, 0);
                    break;
                case '6':
                    $image = imagerotate($image, -90, 0);
                    break;
            }
        }
        imagejpeg($image, $path);
        $imageNum = rand(1,10000);
        $imageUrl = 'http://115.28.73.97/userImages/'.$userId.'.jpg'.'?num='.$imageNum;
        $this->db->where('account', $account)->update('user', ['imageUrl' => $imageUrl]);
        echo $imageUrl;
    }

    /**
     * 对账单
     */
    public function getAllTrade()
    {
        $account = $this->input->post('account');
        $req = $this->user_model->getAllTrade($account);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 战绩
     */
    public function getMilitaryExploits()
    {
        $account = $this->input->post('account');
        $req = $this->user_model->getMilitaryExploits($account);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 设置支付宝账号
     */
    public function setAliPay()
    {
        $alipay = $this->input->post("alipay");
        $account = $this->input->post('account');
        if(!$account or !$alipay)
        {
            exit(0);
        }
        $req = $this->db->set("alipayid", $alipay)->where("account", $account)->update('user');
        echo $req;
    }

    /**
     * 获得支付宝账号
     */
    public function getAlipay()
    {
        $account = $this->input->post("account");
        if(!$account)
        {
            exit(0);
        }
        $req = $this->db->select("alipayid")->where("account", $account)->get('user')->row_array();
        echo $req['alipayid'];
    }

    /**
     * 设置地址
     */
    public function setAddress()
    {
        $address = $this->input->post("address");
        $account = $this->input->post("account");
        if(!$account or !$address)
        {
            exit(0);
        }
        $req = $this->db->set("address", $address)->where("account", $account)->update('user');
        echo $req;
    }

    /**
     * 获得地址
     */
    public function getAddress()
    {
        $account = $this->input->post("account");
        if(!$account)
        {
            exit(0);
        }
        $req = $this->db->select("address")->where("account", $account)->get('user')->row_array();
        echo $req['address'];
    }
}
