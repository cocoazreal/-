<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * app端观点类
 * Class Opinion
 */
class Opinion extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('opinion_model');
    }

    public function index()
    {
        echo "<h1>霸霸app观点</h1>";
    }

    public function writeOpinion()
    {
        $data = $this->input->post();
        $req = $this->opinion_model->writeOpinion($data);
        echo $req;
    }

    public function getOpinions()
    {
        $req = $this->opinion_model->getOpinions();
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    public function getMoreOpinions()
    {
        $opinionId = $this->input->post('id');
        $req = $this->opinion_model->getMoreOpinions($opinionId);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    public function getOpinionDetail()
    {
        $opinionId = $this->input->post('id');
        $req = $this->opinion_model->getOpinionDetail($opinionId);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 关注人的动态
     */
    public function getLikePeopleTrade()
    {
        $account = $this->input->post('account');
        $req = $this->opinion_model->getLikePeopleTrade($account);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获得更多动态
     */
    public function getMoreLikePeopleTrade()
    {
        $account = $this->input->post('account');
        $time = $this->input->post('time');
        $req = $this->opinion_model->getMoreLikePeopleTrade($account, $time);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 排行
     */
    public function getAllPeopleByMoney()
    {
        $req = $this->opinion_model->getAllPeopleByMoney();
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获得观点
     */
    public function getMyOpinions()
    {
        $account = $this->input->post('account');
        $req = $this->opinion_model->getMyOpinions($account);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获得观点
     */
    public function getMoreMyOpinions()
    {
        $account = $this->input->post('account');
        $id = $this->input->post('id');
        $req = $this->opinion_model->getMoreMyOpinions($account, $id);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }
}