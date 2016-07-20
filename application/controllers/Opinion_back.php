<?php
header("Access-Control-Allow-Origin: *");
defined('BASEPATH') OR exit('No direct script access allowed');

class Opinion_back extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('opinion_back_model');

        // 设置表格模板
        $template = require(dirname(__FILE__).'/../libraries/table.php');
        $this->table->set_template($template);
    }

    /**
     * 得到所有的观点 进行审核
     */
    public function getAllOpinion()
    {
        $result['opinion'] = $this->opinion_back_model->getAllOpinion();
        $this->load->view('showAllOpinion', $result);
    }

    public function deleteOpinion()
    {
        $opinionId = $this->input->post("opinionId");
        $this->opinion_back_model->deleteOpinion($opinionId);
    }

    public function getOpinion()
    {
        $page = $this->input->get('page');
        $req = $this->opinion_back_model->getOpinion($page);
        echo json_encode($req, JSON_UNESCAPED_UNICODE);
    }

}