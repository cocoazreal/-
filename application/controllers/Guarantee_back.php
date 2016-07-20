<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Guarantee_back extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('guarantee_back_model');
    }

    public function index()
    {
        $this->load->view('guarantee');
    }

    /**
     * 插入保障
     */
    public function insertGuar()
    {
        $data = $this->input->post();
        $req = $this->guarantee_back_model->insertGuar($data);
        echo $req;
    }

    /**
     * 获取保障的图片
     */
    public function getGuarImg()
    {
        $error = $_FILES["upload"]['error'];

        $guaranteeId = $this->input->post('uploadId');

        if($error == UPLOAD_ERR_OK)
        {
            $name = $_FILES['upload']['name'];
            if(strpos($name,"jpg") != false )
            {
                move_uploaded_file($_FILES['upload']["tmp_name"], FCPATH."guaranteeImages/".$guaranteeId.".jpg");
                $this->db->where('id',$guaranteeId)->update('guarantee', array('imgUrl' => 'http://115.28.73.97/guaranteeImages/'.$guaranteeId.".jpg"));
            }
            else if(strpos($name,"png") != false)
            {
                move_uploaded_file($_FILES['upload']["tmp_name"], FCPATH."guaranteeImages/".$guaranteeId.".png");
                $this->db->where('id',$guaranteeId)->update('guarantee', array('imgUrl' => 'http://115.28.73.97/guaranteeImages/'.$guaranteeId.".png"));
            }
            else if(strpos($name,"bmp") != false)
            {
                move_uploaded_file($_FILES['upload']["tmp_name"], FCPATH."guaranteeImages/".$guaranteeId.".bmp");
                $this->db->where('id',$guaranteeId)->update('guarantee', array('imgUrl' => 'http://115.28.73.97/guaranteeImages/'.$guaranteeId.".bmp"));
            }
        }

        echo $guaranteeId;
    }

    /**
     * 获得保障的id
     */
    public function getGuarMaxId()
    {
        $req = $this->guarantee_back_model->getGuarMaxId();
        echo $req;
    }

    public function guaranteeTrade($data)
    {
        //获得用户信息

    }
}