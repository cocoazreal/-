<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Event 事件类
 */
class Event extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('event_model');
    }

    public function index()
    {
        echo "<h1>事件处理</h1>";
    }


    /**
     * 获得事件 包括下拉刷新
     */
    public function getEvent()
    {
        $type = $this->input->post('type');
        $account = $this->input->post('account');
        if(!isset($type))
        {
            exit(0);
        }
        $req = $this->event_model->getEvent($type, $account);
        $req = json_encode($req, JSON_UNESCAPED_UNICODE);
        echo $req;
    }

    /**
     * 上拉加载
     * $param $id 用户页面中显示事件的最新id
     */
    public function getMoreEvent()
    {
        $data = $this->input->post();
        $data = array_map('trim', $data);
        if(!isset($data['type']))
        {
            exit(0);
        }
        $id = $data['id'];
        $type = $data['type'];
        $account = $data['account'];
        $req = $this->event_model->getMoreEvent($id, $type, $account);
        $req = json_encode($req, JSON_UNESCAPED_UNICODE);
        echo $req;
    }

    /**
     * 获得事件的详情
     */
    public function getEventDetail()
    {
        $id = $this->input->post('id');
        $account = $this->input->post('account');
        if(!isset($id))
        {
            exit(0);
        }
        $req = $this->event_model->getEventDetail($id, $account);
        $req = json_encode($req, JSON_UNESCAPED_UNICODE);
        echo $req;
    }

    //
}

