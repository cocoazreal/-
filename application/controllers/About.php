<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('about_model');
    }

    /**
     * 意见反馈
     */
    public function getSuggestion()
    {
        $account = $this->input->post('account');
        $content = $this->input->post('content');
        $req = $this->about_model->getSuggestion($account, $content);
        echo $req;
    }

    public function getPrivacy()
    {
        $this->load->view('privacy');
    }
}