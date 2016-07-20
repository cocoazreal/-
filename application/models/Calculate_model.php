<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calculate_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function calculateEvent($eventId, $look)
    {
        // 获得对应看好和不看好的人的信息
        $upInfo = $this->db->select('userId,money,playMoney,useableMoney,eventId,look')->get_where('trade', array(
            'eventId' => $eventId,
            'look' => '1',
            'isValid' => '1',
        ))->result_array();
        $downInfo = $this->db->select('userId,money,playMoney,useableMoney,eventId,look')->get_where('trade', array(
            'eventId' => $eventId,
            'look' => '0',
            'isValid' => '1',
        ))->result_array();

        // 结算后所有的交易数据无效
        $this->db->where('eventId', $eventId)->update('trade', ['isValid' => '0']);

        // 把事件置为无效
        $this->db->where('id', $eventId)->update('event', ['isValid' => '0']);

        // 最终是看好获胜
        if($look == '1')
        {
            $this->updateUserInfo($upInfo, $downInfo);
        }
        else $this->updateUserInfo($downInfo, $upInfo);

        // 将事件置为已清算状态
        $this->db->set('isCalcu', 1)->where('id', $eventId)->update('event');

        return "1";

    }


    /**
     * 根据输赢更新用户信息
     * @param $winInfo 赢的人的信息
     * @param $loseInfo 输的人的信息
     */
    public function updateUserInfo($winInfo, $loseInfo)
    {
        // 获得看好和不看好的总金额
        $upMoney = 0;
        $downMoney = 0;
        foreach($winInfo as $upRow)
        {
            $upMoney += $upRow['money'];
        }
        foreach($loseInfo as $downRow)
        {
            $downMoney += $downRow['money'];
        }

        foreach($winInfo as $winRow)
        {
            // 得到获胜的钱
            $winMoney = round(($winRow['money'] / $upMoney) * $downMoney, 1);
            $userData = $this->db->select('useableMoney,playMoney,bond')->get_where('user', array(
                'userId' => $winRow['userId']
            ))->row_array();
            // 更新用户的金钱
            // 保障金
            $bond = $userData['bond'] - $winRow['money'];
            // 可用的钱
            $useableMoney = $winRow['useableMoney'] + $winMoney + $userData['useableMoney'];
            $playMoney = $winRow['playMoney'] + $userData['playMoney'];

            $this->db->insert('earn', array(
                'userId' => $winRow['userId'],
                'eventId' => $winRow['eventId'],
                'money' => $winMoney,
                'look' => $winRow['look']
            ));

            $this->db->where('userId', $winRow['userId'])->update('user', array(
                'useableMoney' => $useableMoney,
                'playMoney' => $playMoney,
                'bond' => $bond
            ));

        }
        foreach($loseInfo as $loseRow)
        {
            $userData = $this->db->select('bond')->get_where('user', array(
                'userId' => $loseRow['userId']
            ))->row_array();
            $bond = $userData['bond'] - $loseRow['money'];

            $this->db->where('userId', $loseRow['userId'])->update('user', array(
                'bond' => $bond
            ));

            $money = 0 - $loseRow['money'];
            $this->db->insert('earn', array(
                'userId' => $loseRow['userId'],
                'eventId' => $loseRow['eventId'],
                'money' => $money,
                'look' => $loseRow['look']
            ));
        }
    }
}