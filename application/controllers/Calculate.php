<?php
header("Access-Control-Allow-Origin: *");
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 后台事件清算
 * Class Calculate
 */
class Calculate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('calculate_model');
    }

    public function index()
    {
        echo "<h1>后台清算</h1>";
    }

    /**
     * 清算事件
     */
    public function calculateEvent()
    {
        $eventId = $this->input->post('id');
        $look = $this->input->post('look');
        $req = $this->calculate_model->calculateEvent($eventId, $look);
        echo $req;
    }
}