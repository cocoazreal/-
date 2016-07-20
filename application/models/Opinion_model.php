<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opinion_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 写观点
     * @param $data
     * @return mixed
     */
    public function writeOpinion($data)
    {
        $userData = $this->db->select('userId')->get_where('user', array('account' => $data['account']))->row_array();
        $result = $this->db->insert('opinion',array(
            'userId' => $userData['userId'],
            'eventId' => $data['eventId'],
            'title' => $data['title'],
            'content' => $data['content'],
            'publishedTime' => date('y-m-d H:i:s', time()),
            'updateTime' => date('y-m-d H:i:s', time())
        ));
        return $result;
    }

    /**
     * 获得观点
     * @return array
     */
    public function getOpinions()
    {
        $result = $this->db->order_by('publishedTime', 'DESC')->get('opinion', 20, 0)->result_array();
        $opinion = array();
        foreach($result as $row)
        {
            $eventData = $this->db->select('isValid')->get_where('event', array('id' => $row['eventId']))->row_array();
            // 过期事件的观点无效
            if($eventData['isValid'] == '1')
            {
                $userData = $this->db->select('account,imageUrl')->get_where('user', array('userId' => $row['userId']))->row_array();
                $row['account'] = $userData['account'];
                $row['imageUrl'] = $userData['imageUrl'];
                unset($row['userId']);

                $content = $row['content'];
                $row['content'] = substr($content,0,140);
                array_push($opinion, $row);
            }
        }
        return $opinion;
    }

    /**
     * 获得更多观点
     * @param $opinionId
     * @return array
     */
    public function getMoreOpinions($opinionId)
    {
        $result = $this->db->order_by('publishedTime', 'DESC')->where('id<', $opinionId)->get('opinion',5, 0)->result_array();
        $opinion = array();
        foreach($result as $row)
        {
            $eventData = $this->db->select('isValid')->get_where('event', array('id' => $row['eventId']))->row_array();
            // 过期事件的观点无效
            if($eventData['isValid'] == '1')
            {
                $userData = $this->db->select('account,imageUrl')->get_where('user', array('userId' => $row['userId']))->row_array();
                $row['account'] = $userData['account'];
                $row['imageUrl'] = $userData['imageUrl'];
                unset($row['userId']);

                $content = $row['content'];
                $row['content'] = substr($content,0,140);
                array_push($opinion, $row);
            }
        }
        return $opinion;
    }

    /**
     * 观点详细
     * @param $opinionId
     * @return mixed
     */
    public function getOpinionDetail($opinionId)
    {
        $result = $this->db->get_where('opinion',array('id' => $opinionId))->row_array();
        // 用户标题
        $userId = $result['userId'];
        $userData = $this->db->select('account,imageUrl')->get_where('user', array('userId' =>$userId))->row_array();
        $result['account'] = $userData['account'];
        $result['imageUrl'] = $userData['imageUrl'];
        unset($result['userId']);
        return $result;
    }

    /**
     * 关注人的动态
     * @param $account
     * @return array
     */
    public function getLikePeopleTrade($account)
    {
        $userData = $this->db->select('likePeople')->where('account', $account)->get('user')->row_array();
        $likePeople = $userData['likePeople'];
        $likePerson = explode('-', $likePeople);
        // 关注人所交易的事件
        $trade = array();

        for($i = 0; $i < count($likePerson); $i++)
        {
            if($likePerson[$i] != "")
            {
                // 关注的人的账号
                $user = $this->db->select('account')->where('userId', $likePerson[$i])->get('user')->row_array();
                // 关注的人交易的数据
                $tradeData = $this->db->select('eventId,id,look,tradeTime')->where(array(
                    'userId' => $likePerson[$i],
                    'isValid' => '1'
                ))->order_by('tradeTime', "DESC")->get('trade', 10, 0)->result_array();

                foreach($tradeData as $row)
                {
                    $singleData = $this->db->select('title,detail')->where('id', $row['eventId'])->get('event')->row_array();
                    $row['title'] = $singleData['title'];
                    $row['detail'] = substr($singleData['detail'], 0, 140);
                    $row['account'] = $user['account'];
                    array_push($trade, $row);
                }
            }
        }

        // 根据时间排序
        $time = array();
        foreach($trade as $row)
        {
            $time[] = $row['tradeTime'];
        }
        array_multisort($time, SORT_DESC, $trade);


        return array_slice($trade, 0, 10);
    }

    /**
     * 根据时间获得更多动态
     * @param $account
     * @param $time
     * @return array
     */
    public function getMoreLikePeopleTrade($account, $time)
    {
        $userData = $this->db->select('likePeople')->where('account', $account)->get('user')->row_array();
        $likePeople = $userData['likePeople'];
        $likePerson = explode('-', $likePeople);
        // 关注人所交易的事件
        $trade = array();

        for($i = 0; $i < count($likePerson); $i++)
        {
            if($likePerson[$i] != "")
            {
                // 关注的人的账号
                $user = $this->db->select('account')->where('userId', $likePerson[$i])->get('user')->row_array();
                // 关注的人交易的数据
                $tradeData = $this->db->select('eventId,id,look,tradeTime')->where(array(
                    'userId' => $likePerson[$i],
                    'tradeTime<' => $time,
                    'isValid' => '1'
                ))->order_by('tradeTime', "DESC")->get('trade', 5, 0)->result_array();

                foreach($tradeData as $row)
                {
                    $singleData = $this->db->select('title,detail')->where('id', $row['eventId'])->get('event')->row_array();
                    $row['title'] = $singleData['title'];
                    $row['detail'] = substr($singleData['detail'], 0, 140);
                    $row['account'] = $user['account'];
                    array_push($trade, $row);
                }
            }
        }

        // 根据时间排序
        $time = array();
        foreach($trade as $row)
        {
            $time[] = $row['tradeTime'];
        }
        array_multisort($time, SORT_DESC, $trade);


        return array_slice($trade, 0, 5);
    }

    /**
     * 排行榜
     * @return array
     */
    public function getAllPeopleByMoney()
    {
        $result = $this->db->select('account,useableMoney,imageUrl')->order_by('useableMoney', 'DESC')->get('user', 50, 0)->result_array();
        $user = array();
        for($i = 0; $i < count($result); $i++)
        {
            $result[$i]['rank'] = $i + 1;
            array_push($user, $result[$i]);
        }
        return $user;
    }

    public function getMyOpinions($account)
    {
        $userData = $this->db->select('userId,imageUrl')->where('account', $account)->get('user')->row_array();
        $opinion = $this->db->select('id,eventId,title,content,publishedTime')->order_by('publishedTime', "DESC")->where('userId', $userData['userId'])
            ->get('opinion', 10, 0)->result_array();
        $result = array();
        $time = array();
        foreach($opinion as $row)
        {
            $eventData = $this->db->select('isValid')->get_where('event', array('id' => $row['eventId']))->row_array();
            // 过期事件的观点无效
            if($eventData['isValid'] == '1')
            {
                $row['imageUrl'] = $userData['imageUrl'];
                $content = $row['content'];
                $row['content'] = substr($content,0,140);
                // 按时间排序
                $time[] = $row['publishedTime'];
                array_push($result, $row);
            }
        }
        array_multisort($time, SORT_DESC, $result);
        return $result;
    }

    public function getMoreMyOpinions($account, $id)
    {
        $userData = $this->db->select('userId,imageUrl')->where('account', $account)->get('user')->row_array();
        $opinion = $this->db->select('id,eventId,title,content,publishedTime')->order_by('publishedTime', "DESC")->where(array(
            'userId' => $userData['userId'],
            'id<' => $id
        ))->get('opinion', 10, 0)->result_array();
        $result = array();
        $time = array();
        foreach($opinion as $row)
        {
            $eventData = $this->db->select('isValid')->get_where('event', array('id' => $row['eventId']))->row_array();
            // 过期事件的观点无效
            if($eventData['isValid'] == '1')
            {
                $row['imageUrl'] = $userData['imageUrl'];
                $content = $row['content'];
                $row['content'] = substr($content,0,140);
                // 按时间排序
                $time[] = $row['publishedTime'];
                array_push($result, $row);
            }
        }
        array_multisort($time, SORT_DESC, $result);
        return $result;
    }
}