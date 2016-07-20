<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 增加用户
     * @param $data 请求数据
     * @return boolean 是否注册
     */
    public function addUser($data)
    {
        $query = $this->db->get_where('user', array('phone' => $data['phone']));
        if($query->num_rows() > 0)
        {
            return false;
        }
        else
        {
            $userId = $this->createUserId();
            $this->db->insert('user', array(
                'account' => $data['account'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'userId' => $userId,
                'imageUrl' => 'http://115.28.73.97/userImages/10000000.jpg'
            ));
            return true;
        }
    }


    /**
     * 检查登陆信息是否正确
     * @param $data 登陆数据
     * @return boolean 是否登陆成功
     */
    public function checkLoginInfo($data)
    {
        $query = $this->db->select('password,account,state,uuid')->get_where('user', array('phone' => $data['phone']));
        $req = "";
        if($data['phone'] == "00000000000")
        {
            $req['state'] = '0';
            $number = mt_rand(1, 10000);
            $req['account'] = "游客".$number;
            return $req;
        }
        if($query->num_rows() > 0)
        {
            $get_data = $query->row_array();
            if($get_data['uuid'] == "" || ($get_data['uuid'] == $data['uuid']))
            {
                if($get_data['password'] == $data['password'])
                {
                    // 登录成功
                    $req['account'] = $get_data['account'];
                    $req['state'] = '0';
                    $this->db->set("uuid", $data['uuid'])->where('phone', $data['phone'])->update('user');
                }
                else
                {
                    // 密码不对
                    $req['state'] = '1';
                }
            }
            else
            {
                $req['state'] = '3';
            }
        }
        else
        {
            // 无此用户
            $req['state'] = '2';
        }
        return $req;
    }


    /**
     * 设置登录凭证
     * @param $account 用户名
     * @param $credit 状态
     * @return bool
     */
    public function setCredit($account, $credit)
    {
        $this->db->set('credit', $credit)->where('account', $account)->update('user');
        $this->db->set('state', 1)->where('account', $account)->update('user');
    }


    /**
     * 自动登陆
     */
    public function checkAutoLogin($account, $token, $uuid)
    {
        $query = $this->db->get_where('user', array('account' => $account));
        if($query->num_rows() > 0)
        {
            $result = $query->row_array();
            if($result['uuid'] == "" || $result['uuid'] == $uuid)
            {
                $credit = $result['credit'];
                if($credit == $token)
                {
                    $this->db->set("uuid", $uuid)->where('account', $account)->update('user');
                    return 1;

                }
                else return 0;
            }
            else
            {
                return 2;
            }

        }
        else return 0;
    }


    /**
     * 修改密码
     * @param $data
     * @return int
     */
    public function changePasswd($data)
    {
        $result = $this->db->select('password')->get_where('user', array('account' => $data['account']))->row_array();
        $password = $result['password'];
        if($password == $data['oldpasswd'])
        {
            $this->db->set('password', $data['newpasswd'])->where('account', $data['account'])->update('user');
            return 1;
        }
        else
        {
            return 0;
        }
    }

    /**
     * 用户退出
     */
    public function userQuit($account)
    {
        $this->db->set('state', 0)->where('account', $account)->update('user');
        $this->db->set('uuid', "")->where('account', $account)->update('user');
    }

    /**
     *取得个人信息
     * @param $account 用户账户名
     * @return mixed
     */
    public function getUserMoney($account)
    {
        $result = $this->db->select('playMoney,useableMoney,bond,imageUrl')->get_where('user', array('account' => $account));
        if($result->num_rows() > 0)
        {
            $get_data = $result->row_array();
            $get_data['allMoney'] = $get_data['playMoney'] + $get_data['useableMoney'];
            unset($get_data['playMoney']);
        }
        else
        {
            $get_data['allMoney'] = $get_data['bond'] = $get_data['useableMoney'] = 0;
        }

        return $get_data;
    }


    /**
     * 创建用户唯一id
     * @return int|string
     */
    public function createUserId()
    {
        $result = $this->db->select_max('userId')->get('user')->row_array();
        $userId = $result['userId'];
        if($userId == '0')
        {
            $userId = '10000000';
        }
        else
        {
            $userId += 1;
        }

        return $userId;
    }


    /**
     * 修改账户名
     * @param $data
     * @return array
     */
    public function changeAccount($data)
    {
        $result = $this->db->select('account')->get('user')->result_array();
        $userData = $this->db->select('userId')->where('account', $data['oldAccount'])->get('user')->row_array();
        $req = array();
        foreach($result as $row)
        {
            if ($row['account'] == $data['newAccount'])
            {
                $req['state'] = 2;
                return $req;
            }
        }

        $success = $this->db->where('userId', $userData['userId'])->set('account', $data['newAccount'])->update('user');
        $req['state'] = $success;
        $req['account'] = $data['newAccount'];

        return $req;
    }

    /**
     * 用户之间的关注
     * @param $data 所需信息
     * @return int|string 是否关注
     */
    public function attachPeople($data)
    {
        $username = $data['username']; // 需要关注的人
        $account = $data['account']; // 自己


        // 需要关注的人的信息
        $likedData = $this->db->select('userId,likedPeople')->get_where('user', array('account' => $username))->row_array();
        // 自己的信息
        $likeData = $this->db->select('userId,likePeople')->get_where('user', array('account' => $account))->row_array();

        // 查看是否关注
        if(strpos($likeData['likePeople'], $likedData['userId']) === false)
        {
            $likeData['likePeople'] = $likeData['likePeople'].'-'.$likedData['userId'];
            $likedData['likedPeople'] = $likedData['likedPeople'].'-'.$likeData['userId'];
            $isAttach = 1;
        }
        else
        {
            $likeData['likePeople'] = str_replace('-'.$likedData['userId'], "", $likeData['likePeople']);
            $likedData['likedPeople'] = str_replace('-'.$likeData['userId'], "", $likedData['likedPeople']);
            $isAttach = 0;
        }

        $this->db->where('userId', $likeData['userId'])->update('user', ['likePeople' => $likeData['likePeople']]);
        $this->db->where('userId', $likedData['userId'])->update('user', ['likedPeople' => $likedData['likedPeople']]);

        return $isAttach;
    }

    /**
     * 关注事件
     * @param $data 所需信息
     * @return int  是否关注
     */
    public function attachEvent($data)
    {
        $account = $data['account'];
        $eventId = $data['eventId'];

        $userData = $this->db->select('userId,likeEvent')->get_where('user', ['account' => $account])->row_array();
        $eventData = $this->db->select('likePeople,likePeopleNum')->get_where('event', ['id' => $eventId])->row_array();
        $likePeople = $eventData['likePeople'];
        $likePeopleNum = $eventData['likePeopleNum'];

        $likeEvent = $userData['likeEvent'];

        if(strpos($likeEvent,'-'.$eventId.'-') === false)
        {
            $likeEvent = $likeEvent."-".$eventId."-";
            $likePeople = $likePeople."-".$userData['userId'];
            $isAttach = 1;
            $likePeopleNum += 1;
        }
        else
        {
            $likeEvent = str_replace("-".$eventId."-", "", $likeEvent);
            $likePeople = str_replace("-".$userData['userId'], "", $likePeople);
            $isAttach = 0;
            $likePeopleNum -= 1;
        }

        $this->db->where('account', $account)->update('user',['likeEvent' => $likeEvent]);
        $this->db->where('id', $eventId)->update('event',['likePeople' => $likePeople, 'likePeopleNum' => $likePeopleNum]);
        return $isAttach;
    }

    /**
     * 进入他人主页时获得他人的信息
     */
    public function getOtherUserInfo($data)
    {
        $account = $data['account']; // my acc
        $username = $data['username'];// other acc

        $myData = $this->db->select('userId,likePeople')->where('account', $account)->get('user')->row_array();
        $otherData = $this->db->select('userId,likedPeople,imageUrl')->where('account', $username)->get('user')->row_array();

        $myUserId = $myData['userId'];
        $otherUserId = $otherData['userId'];
        $result = array();

        $result['imageUrl'] = $otherData['imageUrl'];

        if(strpos($myData['likePeople'], $otherUserId) === false)
        {
            $result['isAttached'] = 0;
        }
        else $result['isAttached'] = 1;

        return $result;
    }

    public function getAllTrade($account)
    {
        $userData = $this->db->select('userId')->where('account', $account)->get('user')->row_array();
        $userId = $userData['userId'];

        $data = $this->db->select('eventId,money,look,time')->order_by('time', 'DESC')->where('userId', $userId)->get('earn', 0, 10)->result_array();
        $earn = array();
        foreach($data as $row)
        {
            $eventData = $this->db->select('title')->where('id', $row['eventId'])->get('event')->row_array();
            if (mb_strlen($eventData['title']) > 30)
            {
                $row['title'] = mb_substr($eventData['title'], 0, 30);
            }
            else $row['title'] = $eventData['title'];
            if($row['money'] >= 0)
            {
                $row['flag'] = 1;
            }
            else $row['flag'] = 0;
            array_push($earn, $row);
        }

        $guarantee = $this->db->select('guaranteeId,money,time')->order_by('time', 'DESC')->where('userId', $userId)->get('guaranteeTrade', 0, 10)->result_array();
        foreach($guarantee as $row)
        {
            $guaranteeData = $this->db->select('title')->where('id', $row['guaranteeId'])->get('guarantee')->row_array();
            $row['title'] = $guaranteeData['title'];
            $row['flag'] = 0;
            $row['money'] = 0 - $row['money'];
            array_push($earn, $row);
        }

        // 根据时间排序
        $time = array();
        foreach($earn as $row)
        {
           $time[] = $row['time'];
        }
        array_multisort($time, SORT_DESC, $earn);

        return array_slice($earn, 0, 10);;
    }

    public function getMilitaryExploits($account)
    {
        $userData = $this->db->select('userId')->where('account', $account)->get('user')->row_array();
        $userId = $userData['userId'];

        $all  = array('money' => 0, 'upNum' => 0, 'downNum' => 0, 'percent' => 0, 'average' => 0); //
        $sports = array('money' => 0, 'upNum' => 0, 'downNum' => 0, 'percent' => 0, 'average' => 0);
        $finance = array('money' => 0, 'upNum' => 0, 'downNum' => 0, 'percent' => 0, 'average' => 0);
        $livelihood = array('money' => 0, 'upNum' => 0, 'downNum' => 0, 'percent' => 0, 'average' => 0);
        $entertainment = array('money' => 0, 'upNum' => 0, 'downNum' => 0, 'percent' => 0, 'average' => 0);
        $science = array('money' => 0, 'upNum' => 0, 'downNum' => 0, 'percent' => 0, 'average' => 0);

        $earn = $this->db->select('eventId,money')->where('userId', $userId)->get('earn')->result_array();

        foreach($earn as $row)
        {
            $eventData = $this->db->select('eventType')->where('id', $row['eventId'])->get('event')->row_array();
            $type = $eventData['eventType'];
            switch($type)
            {
                case 'sports':
                    $sports['money'] += $row['money'];
                    if($row['money'] >= 0) $sports['upNum'] += 1;
                    else $sports['downNum'] += 1;
                    $sports['percent'] = round((($sports['upNum']) / ($sports['upNum'] + $sports['downNum'])) * 100, 1);
                    $sports['average'] = round($sports['money'] / ($sports['upNum'] + $sports['downNum']));
                    break;
                case 'finance':
                    $finance['money'] += $row['money'];
                    if($row['money'] >= 0) $finance['upNum'] += 1;
                    else $finance['downNum'] += 1;
                    $finance['percent'] = round((($finance['upNum']) / ($finance['upNum'] + $finance['downNum'])) * 100, 1);
                    $finance['average'] = round($finance['money'] / ($finance['upNum'] + $finance['downNum']), 1);
                    break;
                case 'livelihood':
                    $livelihood['money'] += $row['money'];
                    if($row['money'] >= 0) $livelihood['upNum'] += 1;
                    else $livelihood['downNum'] += 1;
                    $livelihood['percent'] = round((($livelihood['upNum']) / ($livelihood['upNum'] + $livelihood['downNum'])) * 100, 1);
                    $livelihood['average'] = round($livelihood['money'] / ($livelihood['upNum'] + $livelihood['downNum']), 1);
                    break;
                case 'entertainment':
                    $entertainment['money'] += $row['money'];
                    if($row['money'] >= 0) $entertainment['upNum'] += 1;
                    else $entertainment['downNum'] += 1;
                    $entertainment['percent'] = round((($entertainment['upNum']) / ($entertainment['upNum'] + $entertainment['downNum'])) * 100, 1);
                    $entertainment['average'] = round($entertainment['money'] / ($entertainment['upNum'] + $entertainment['downNum']), 1);
                    break;
                case 'science':
                    $science['money'] += $row['money'];
                    if($row['money'] >= 0) $science['upNum'] += 1;
                    else $science['downNum'] += 1;
                    $science['percent'] = round((($science['upNum']) / ($science['upNum'] + $science['downNum'])) * 100, 1);
                    $science['average'] = round($science['money'] / ($science['upNum'] + $science['downNum']), 1);
                    break;
            }

            $all['money'] += $row['money'];
            if($row['money'] >= 0) $all['upNum'] += 1;
            else $all['downNum'] += 1;
            $all['percent'] = round((($all['upNum']) / ($all['upNum'] + $all['downNum'])) * 100, 1);
            $all['average'] = round($all['money'] / ($all['upNum'] + $all['downNum']), 1);
        }

        $result = array(
            'all' => $all,
            'science' => $science,
            'entertainment' => $entertainment,
            'livelihood' => $livelihood,
            'finance' => $finance,
            'sports' => $sports
        );

        return $result;
    }
}