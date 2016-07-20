<?php
header("Access-Control-Allow-Origin: *");
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_back extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // 设置表格模板
        $template = require(dirname(__FILE__).'/../libraries/table.php');
        $this->table->set_template($template);

        $this->load->model('event_back_model');
    }

    public function index()
    {
        $data = $this->input->get();
        $result = $this->event_back_model->getEvent($data);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 添加事件
     */
    public function insertEvent()
    {
        $data = $this->input->post();
        if(empty($data))
        {
            $this->load->view('insEvent');
        }else
        {
            $req = $this->event_back_model->insertEvent($data);
            echo json_encode(array("msg" => $req));
        }
    }

    public function updateEvent()
    {
        $data = $this->input->post();
        if(!$data)
        {
            exit(0);
        }
        $req = $this->event_back_model->updateEvent($data);
        echo $req;
    }

    /**
     * 添加事件图片
     */
    public function addEventImage()
    {
        $error = $_FILES["upload"]['error'];

        $eventID = $this->input->post('uploadId');

        if($error == UPLOAD_ERR_OK)
        {
            $name = $_FILES['upload']['name'];
            if(strpos($name,"jpg") != false )
            {
                move_uploaded_file($_FILES['upload']["tmp_name"], FCPATH."eventimages/".$eventID.".jpg");
                $this->db->where('id',$eventID)->update('event', array('imgUrl' => 'http://115.28.73.97/eventimages/'.$eventID.".jpg"));
            }
            else if(strpos($name,"png") != false)
            {
                move_uploaded_file($_FILES['upload']["tmp_name"], FCPATH."eventimages/".$eventID.".png");
                $this->db->where('id',$eventID)->update('event', array('imgUrl' => 'http://115.28.73.97/eventimages/'.$eventID.".png"));
            }
            else if(strpos($name,"bmp") != false)
            {
                move_uploaded_file($_FILES['upload']["tmp_name"], FCPATH."eventimages/".$eventID.".bmp");
                $this->db->where('id',$eventID)->update('event', array('imgUrl' => 'http://115.28.73.97/eventimages/'.$eventID.".bmp"));
            }
        }

        echo $eventID;
    }

    /**
     * 返回数据库中的事件最大ID
     */
    public function getEventID()
    {
        $eventId = $this->event_back_model->getEventID();
        echo $eventId;
    }


    public function getAllEvent()
    {
        $this->load->view('back');
    }

    public function deleteEvent()
    {
        $eventId = $this->input->post("eventId");
        $this->event_back_model->deleteEvent($eventId);
    }

}