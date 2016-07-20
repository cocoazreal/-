<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 交易类
class Trade extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('trade_model');
    }

    /**
     * 插入事件的交易信息
     */
    public function eventTrade()
    {
        $getData = $this->input->post();
        $data = array_map('trim', $getData);
        $req = $this->trade_model->eventTrade($data);
        echo $req;
    }

    /**
     * 获得账号所预测的事件
     */
    public function myEventTrade()
    {
        $account = $this->input->post('account');
        if(empty($account)) exit(0);
        $req = $this->trade_model->myEventTrade($account);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    public function getTradeMoney()
    {
    	$this->load->view("charge");
    }


}