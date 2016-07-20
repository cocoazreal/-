<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 保障事件处理 针对于app
 * Class Guarantee
 */
class Guarantee extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('guarantee_model');
    }

    public function index()
    {
        echo "<h1>霸霸保障部分</h1>";
    }

    /**
     * 获得保障
     * @return mixed
     */
    public function getGuarantee()
    {
        $req = $this->guarantee_model->getGuarantee();
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 保障页面下的用户麻麻币
     */
    public function getUserMoney()
    {
        $account = $this->input->post('account');
        $req = $this->guarantee_model->getUserMoney($account);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    public function getMoreGuarantee()
    {
        $guaranteeId = $this->input->post('id');
        $req = $this->guarantee_model->getMoreGuarantee($guaranteeId);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获得保障的详细信息
     */
    public function getGuarDetail()
    {
        $guaranteeId = $this->input->post('id');
        $req = $this->guarantee_model->getGuarDetail($guaranteeId);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

}