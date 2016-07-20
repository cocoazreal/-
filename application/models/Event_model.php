<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        include "Comment_model.php";
    }

    /**
     * 下拉刷新 和 侧滑显示
     * @param $type  需要返回信息的类型
     * @param $account
     * @return array 需要的信息
     */
    public function getEvent($type, $account)
    {
        if($type == "recommend")
        {
            $result = $this->db->select('id, eventType, datetime ,preTitle,imgUrl,likePeopleNum')->order_by('id', 'DESC')->get_where('event', array(
                'recommend' => '1',
                'isValid' => '1'
            ), 5, 0)->result_array();
        }
        elseif($type == "concern")
        {
            $userData = $this->db->select('likeEvent')->where('account', $account)->get('user')->row_array();
            $likeEvent = $userData['likeEvent'];
            $event = explode('-', $likeEvent);
            $result = array();
            for($i = 0; $i < count($event); $i++)
            {
                if($event[$i] != "")
                {
                    $eventData = $this->db->select('id, eventType, datetime ,preTitle,imgUrl,likePeopleNum')->get_where('event', array(
                        'id' => $event[$i],
                        'isValid' => '1'
                    ))->row_array();
                    array_push($result, $eventData);
                }
            }
        }
        else
        {
            $result = $this->db->select('id, eventType, datetime ,preTitle,imgUrl,likePeopleNum')->order_by('id', 'DESC')->get_where('event', array(
                'eventType' => $type,
                'isValid' => '1'
            ), 5, 0)->result_array();
        }

        $event = array();
        foreach($result as $row)
        {
            $datetime = strtotime($row['datetime']);
            $now = time();
            if($datetime > $now)
            {
                // 计算剩余时间
                $validTime = $datetime - $now;
                $validTime_day = round($validTime/(60*60*24),0);
                $validTime_hour = round(($validTime - 60*60*24*$validTime_day)/(60*60), 0);
                $row['restTime'] = $validTime_day."天".$validTime_hour."小时";
                unset($row['datetime']);

                // 计算看好与不看好的比例
                $row['percent'] = $this->calcPercent($row['id']);

                // 将事件类型由英文转化为中文
                $row['eventType'] = $this->convertType($row['eventType']);

                //评论总数
                $commentNum = $this->db->select('eventId')->where('eventId', $row['id'])->get('comment')->num_rows();
                $row['commentNum'] = $commentNum;

                // 获得comment
                $comment = $this->getMostLikeComment($row['id'], $account);
                $row['comment'] = array();

                foreach ($comment as $eachComment)
                {
                    $eachComment['content'] = mb_substr($eachComment['content'], 0 , 50)."...";
                    array_push($row['comment'], $eachComment);
                }

                array_push($event, $row);
            }
            else
            {
                $this->db->set('isValid', 0)->where('id', $row['id'])->update('event');
            }
        }
        return $event;
    }


    /**
     * 上拉加载
     * @param $id 事件id
     * @param $type 事件类型
     * @param $account 事件类型
     * @return array 返回需要加载事件的信息
     */
    public function getMoreEvent($id, $type, $account)
    {
        if($type == "recommend")
        {
            $result = $this->db->select('id, eventType, datetime, preTitle,imgUrl')->where(array(
                'id<' =>$id,
                'recommend'=> "1",
                'isValid' => '1'
            ))->order_by('id', 'DESC')->get('event', 5, 0)->result_array();
        }
        else {
            $result = $this->db->select('id, eventType, datetime, preTitle,imgUrl')->where(array(
                'id<' => $id,
                'eventType' => $type,
                'isValid' => '1'
            ))->order_by('id', 'DESC')->get('event', 5, 0)->result_array();
        }

        $event = array();
        foreach($result as $row)
        {
            $datetime = strtotime($row['datetime']);
            $now = time();
            if($datetime > $now)
            {
                $validTime = $datetime - $now;
                $validTime_day = round($validTime/(60*60*24),0);
                $validTime_hour = round(($validTime - 60*60*24*$validTime_day)/(60*60), 0);
                $row['restTime'] = $validTime_day."天".$validTime_hour."小时";
                unset($row['datetime']);

                // 计算看好与不看好的比例
                $row['percent'] = $this->calcPercent($row['id']);

                // 将事件类型由英文转化为中文
                $row['eventType'] = $this->convertType($row['eventType']);

                //评论总数
                $commentNum = $this->db->select('eventId')->where('eventId', $row['id'])->get('comment')->num_rows();
                $row['commentNum'] = $commentNum;

                // 获得comment
                $comment = $this->getMostLikeComment($row['id'], $account);
                $row['comment'] = array();

                foreach ($comment as $eachComment)
                {
                    $eachComment['content'] = mb_substr($eachComment['content'], 0 , 50)."...";
                    array_push($row['comment'], $eachComment);
                }

                array_push($event, $row);
            }
            else
            {
                $this->db->set('isValid', 0)->where('id', $row['id'])->update('event');
            }
        }
        return $event;
    }


    /**
     * 获得事件详细信息
     * @param $id 事件的id
     * @return string 返回事件的详细信息
     */
    public function getEventDetail($id, $account)
    {
        $result = $this->db->select('id,datetime,title,detail,imgUrl,tradeBasis,likePeople')->where('id', $id)->get('event')->row_array();
        $userData = $this->db->select('userId')->where('account', $account)->get('user')->row_array();
        $userId = $userData['userId'];
        if(strpos($result['likePeople'], "-".$userId) === false)
        {
            $result['isAttachEvent'] = 0;
        }
        else $result['isAttachEvent'] = 1;
        $datetime = strtotime($result['datetime']);
        $now = time();
        if($datetime > $now)
        {
            $validTime = $datetime - $now;
            $validTime_day = round($validTime/(60*60*24),0);
            $validTime_hour = round(($validTime - 60*60*24*$validTime_day)/(60*60), 0);
            $result['restTime'] = $validTime_day."天".$validTime_hour."小时";
            unset($result['datetime']);
        }
        else
        {
            return "0";
        }
        //评论总数
        $commentNum = $this->db->select('eventId')->where('eventId', $id)->get('comment')->num_rows();
        $result['commentNum'] = $commentNum;
        //计算看好与不看好比例
        $result['percent'] = $this->calcPercent($id);
        return $result;
    }


    /**
     * 计算看好与不看好的比例
     * @param $id 事件id
     * @return int|string 返回百分比
     */
    public function calcPercent($id)
    {
        $lookUp = $this->db->get_where('trade', array('eventId' => $id, 'look' => '1'))->num_rows();
        $lookDown = $this->db->get_where('trade', array('eventId' => $id, 'look' => '0'))->num_rows();
        if($lookDown == 0 && $lookUp == 0) //还没有人投票
        {
            $percent = 0;
        }
        else
        {
            $percent = number_format(($lookUp / ($lookUp + $lookDown)) * 100, 2);
        }
        return $percent;
    }

    /**
     * 事件名称转换
     * @param $type 对应事件类型
     * @return string $eventType 对应英文
     */
    public function convertType($type)
    {
        $eventType = "";
        if($type == "sports")
        {
            $eventType = "体育";
        }
        elseif($type == "finance")
        {
            $eventType = "财经";
        }
        elseif($type == "livelihood")
        {
            $eventType = "民生";
        }
        elseif($type == "entertainment")
        {
            $eventType = "娱乐";
        }
        elseif($type == "science")
        {
            $eventType = "科技";
        }
        elseif($type == "concern")
        {
            $eventType = "关注";
        }
        return $eventType;
    }

    /**
     * 主页评论
     * @param $eventId
     * @param $account
     * @return array
     */
    public function getMostLikeComment($eventId, $account)
    {
        $comment = new Comment_model();
        return $comment->getMostLikedComment($eventId, $account);
    }

}