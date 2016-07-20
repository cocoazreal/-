<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trade_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $data 交易数据
     * @return boolean 是否交易成功
     */
    public function eventTrade($data)
    {
        // 得到用户的麻麻币
        $userData = $this->db->select('playMoney,useableMoney,bond,userId,likeEvent')->where('account', $data['account'])
            ->get('user')->row_array();

        // 事件信息
        $eventData = $this->db->select("likePeople,likePeopleNum")->where('id', $data['eventId'])->get('event')->row_array();

        $userData['allMoney'] = $userData['playMoney'] + $userData['useableMoney'];

        // 如果用户的麻麻币大于交易数额即可交易
        if($userData['allMoney'] >= $data['money'])
        {
            // 扣除交易金额
            if($userData['playMoney'] >= $data['money'])
            {
                $userData['playMoney'] = $userData['playMoney'] - $data['money'];
                $play_money = $data['money']; // 交易额中赠送的钱
                $useable_money = 0; // 交易额中可用的钱
            }
            else
            {
                $play_money = $userData['playMoney'];
                $userData['useableMoney'] = $userData['useableMoney'] + $userData['playMoney'] - $data['money'];
                $userData['playMoney'] = 0;
                $useable_money = $data['money'] - $play_money;
            }

            $userData['bond'] = $userData['bond'] + $data['money'];//添加保障金

            // 更新用户金钱数据
            $this->db->where('account', $data['account'])->update('user',array(
                'playMoney' => $userData['playMoney'],
                'bond' => $userData['bond'],
                'useableMoney' => $userData['useableMoney']
            ));

            // 交易后自动关注事件
            if(strpos($userData['likeEvent'], '-'.$data['eventId'].'-') === false)
            {
                $userData['likeEvent'] = $userData['likeEvent'].'-'.$data['eventId'].'-';
                $this->db->where('account', $data['account'])->update('user', ['likeEvent' => $userData['likeEvent']]);
            }

            if(strpos($eventData['likePeople'], "-".$userData['userId']) === false)
            {
                $eventData['likePeopleNum'] = $eventData['likePeopleNum'] + 1;
                $eventData['likePeople'] = $eventData['likePeople']."-".$userData['userId'];
                $this->db->where('id', $data['eventId'])->update('event', array(
                    'likePeople' => $eventData['likePeople'],
                    'likePeopleNum' => $eventData['likePeopleNum']
                ));
            }

            // 存储交易记录
            $this->db->insert('trade', array(
                    'eventId' => $data['eventId'],
                    'userId' => $userData['userId'],
                    'money'  => $data['money'],
                    'playMoney' => $play_money,
                    'useableMoney' => $useable_money,
                    'look' => $data['look'])
            );

            return "1";
        }
        else return 0;
    }

    /**
     * 获得预测中的事件
     * @param $account
     * @return array
     */
    public function myEventTrade($account)
    {
        // 得到用户id
        $userData = $this->db->select('userId')->get_where('user', array('account' => $account))->row_array();
        $userId = $userData['userId'];

        // 得到交易数据
        $tradeData = $this->db->select('id,eventId,money,look')->order_by('tradeTime', 'DESC')->get_where('trade', array(
            'userId' => $userId,
            'isValid' => '1'
        ))->result_array();

        $finalData = array();
        foreach($tradeData as $row)
        {
            // 获得事件标题和事件的有效期
            $eventData = $this->db->select('title,datetime')->where('id', $row['eventId'])->get('event')->row_array();
            if (mb_strlen($eventData['title']) > 30)
            {
                $row['eventTitle'] = mb_substr($eventData['title'], 0, 30)."...";
            }
            else $row['eventTitle'] = $eventData['title'];

            $row['datetime'] = $eventData['datetime'];

            // 获得此人应该会获得的金钱
            $row['winMoney'] = $this->getWinMoney($row['money'], $row['look'], $row['eventId']);

            array_push($finalData, $row);
        }
        return $finalData;
    }

    public function getWinMoney($money, $look, $eventId)
    {
        // 获得对应看好和不看好的人的信息
        $upInfo = $this->db->select('money')->get_where('trade', array(
            'eventId' => $eventId,
            'look' => '1'
        ))->result_array();
        $downInfo = $this->db->select('money')->get_where('trade', array(
            'eventId' => $eventId,
            'look' => '0'
        ))->result_array();

        // 获得看好和不看好的总金额
        $upMoney = 0;
        $downMoney = 0;
        foreach($upInfo as $upRow)
        {
            $upMoney += $upRow['money'];
        }
        foreach($downInfo as $downRow)
        {
            $downMoney += $downRow['money'];
        }

        if($look == '1')
        {
            $winMoney = round(($money / $upMoney) * $downMoney, 1);
        }
        else $winMoney = round(($money / $downMoney) * $upMoney, 1);

        return $winMoney;

    }
}
