<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_back_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllUser()
    {
        // 获得分页设置
        $config = require(dirname(__FILE__)."/../config/pagination.php");

        $config['base_url'] = 'http://115.28.73.97/user_back/getalluser';
        $config['total_rows'] = $this->db->count_all('user');
        $config['per_page'] = '10';
        $page = $this->uri->segment(3);
        if($page != 0) $page = ($page - 1) * 10;
        $result = $this->db->select('userId,account,password,phone,useableMoney,likePeople,likedPeople,likeEvent')->order_by('id', 'DESC')->get('user',10,$page);
        $opinion = $result->result_array();

        $this->pagination->initialize($config);
        $this->table->set_heading('用户id', '用户名', '密码', '手机号', '可用麻麻币', '关注的人的id','关注他的人的id', '关注的事件', "操作");
        return $opinion;
    }

    public function deleteUser($userId)
    {
        $this->db->where('userId', $userId)->delete('user');
    }

    public function getUser($page)
    {
        $user = $this->db->select('userId,account,password,phone,playMoney,useableMoney,likePeople,likedPeople,likeEvent')
            ->order_by('id', 'DESC')->get('user', 20, ($page - 1 )*20);
        $result['user'] = $user->result_array();
        $total = $this->db->get('user')->num_rows();
        $result['total'] = ceil($total / 20 );
        return $result;
    }

    public function fixMoney($data)
    {
        return $this->db->set(
            array(
                'playMoney' => $data['playMoney'],
                'useableMoney' => $data['useableMoney'],
            )
        )->where('userId', $data['userId'])->update('user');
    }
}