<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opinion_back_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllOpinion()
    {
        // 获得分页设置
        $config = require(dirname(__FILE__)."/../config/pagination.php");

        $config['base_url'] = 'http://115.28.73.97/opinion_back/getallopinion';
        $config['total_rows'] = $this->db->count_all('opinion');
        $config['per_page'] = '10';
        $page = $this->uri->segment(3);
        if($page != 0) $page = ($page - 1) * 10;
        $result = $this->db->select('*')->order_by('id', 'DESC')->get('opinion',10,$page);
        $opinion = $result->result_array();

        $this->pagination->initialize($config);
        $this->table->set_heading('观点id', '用户id', '事件id', '标题', '内容', '插入时间', '更新时间','操作');
        return $opinion;
    }

    public function deleteOpinion($opinionId)
    {
        $this->db->where("id", $opinionId)->delete("opinion");
    }

    public function getOpinion($page)
    {
        $result = $this->db->select("id,userId,eventId,title,content,publishedTime")->order_by("id", "DESC")
            ->get('opinion', 10, ($page - 1) * 10)->result_array();
        $req = array();
        $req['opinion'] = array();

        foreach ($result as $row)
        {
            $eventData = $this->db->select('title')->where('id', $row['eventId'])->get('event')->row_array();
            $row['eventTitle'] = $eventData['title'];
            array_push($req['opinion'], $row);
        }

        $total = $this->db->get('opinion')->num_rows();
        $req['total'] = ceil($total / 10);
        return $req;
    }

}