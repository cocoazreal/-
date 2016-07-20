<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 保障表
 * Class Guarantee_model
 */
class Guarantee_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获得所有的保障
     * @return array
     */
    public function getGuarantee()
    {
        $result = $this->db->select('id,title,price,imgUrl')->order_by('insertTime', 'DESC')->get('guarantee', 8, 0)->result_array();
        return $result;
    }

    public function getMoreGuarantee($guaranteeId)
    {
        $result = $this->db->select('id,title,price,imgUrl')->where('id<', $guaranteeId)
            ->order_by('insertTime', 'DESC')->get('guarantee', 8, 0)->result_array();
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getGuarDetail($id)
    {
        $result = $this->db->get_where('guarantee', array('id' => $id))->row_array();
        return $result;
    }

    /**
     * 获得用户的麻麻币
     * @param $account
     * @return mixed
     */
    public function getUserMoney($account)
    {
        $userData = $this->db->select('useableMoney')->where('account', $account)->get('user')->row_array();
        return  $userData;
    }
}