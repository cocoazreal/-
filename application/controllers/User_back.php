<?php
header("Access-Control-Allow-Origin: *");
defined('BASEPATH') OR exit('No direct script access allowed');

class User_back extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_back_model');

        // 设置表格模板
        $template = require(dirname(__FILE__).'/../libraries/table.php');
        $this->table->set_template($template);
    }

    public function getAllUser()
    {
        $req['user'] = $this->user_back_model->getAllUser();
        $this->load->view('showAllUser', $req);
    }

    public function deleteUser()
    {
        $userId = $this->input->post('userId');
        $this->user_back_model->deleteUser($userId);
        echo $userId;
    }

    public function getUser()
    {
        $page = $this->input->get('page');
        $req = $this->user_back_model->getUser($page);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

    public function fixMoney()
    {
        $data = $this->input->post();
        $req = $this->user_back_model->fixMoney($data);
        echo $req;
    }

}