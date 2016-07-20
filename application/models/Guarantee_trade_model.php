<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guarantee_trade_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }


    public function guaranteeTrade($data)
    {
        // 获得用户信息
        $userData = $this->db->select('userId,useableMoney,alipayid,address')->get_where('user', array('account' => $data['account']))->row_array();

        // 支付宝或者地址要有
        if($userData['alipayid'] == "" or $userData['address'] == "")
        {
            return 2;
        }

        // 判断金额是否足够
        if($data['price'] <= $userData['useableMoney'])
        {
            // 修改可用余额
            $restMoney = $userData['useableMoney'] - $data['price'];
            $this->db->where('userId', $userData['userId'])->update('user', array('useableMoney' => $restMoney));

            // 插入交易信息
            $this->db->insert('guaranteeTrade', array(
                'userId' => $userData['userId'],
                'guaranteeId' => $data['id'],
                'money' => $data['price']
            ));
            return 1;
        }
        else return 0;
    }
}