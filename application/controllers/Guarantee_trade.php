<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * app端保障交易类
 * Class Guarantee_trade
 */

class Guarantee_trade extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('guarantee_trade_model');
    }

    public function index()
    {
        echo "<h1>保障的交易</h1>";
    }

    /**
     * 交易保障
     */
    public function guaranteeTrade()
    {
        $data = $this->input->post();
        $req = $this->guarantee_trade_model->guaranteeTrade($data);
        echo $req;
    }
}