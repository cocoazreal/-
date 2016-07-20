<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加评论
     * @param $data
     * @return mixed
     */
    public function addComment($data)
    {
        $userData = $this->db->select('userId')->where('account', $data['account'])->get('user')->row_array();
        $userId = $userData['userId'];
        $parentUserData = $this->db->select('userId')->where('account', $data['parentAccount'])->get('user')->row_array();
        $parentUserId = $parentUserData['userId'];

        $result = $this->db->insert('comment', array(
            'eventId' => $data['eventId'],
            'userId' => $userId,
            'content' => $data['content'],
            'parentId' => $data['parentId'],
            'parentUserId' => $parentUserId
        ));
        return $result;
    }

    /**
     * 获得评论
     * @param $eventId
     * @return mixed
     */
    public function getComments($eventId, $account)
    {
        $userData = $this->db->select('userId')->where('account', $account)->get('user')->row_array();
        $userId = $userData['userId'];
        $result = $this->db->where('eventId', $eventId)->order_by('timestamp', "DESC")->get('comment', 10, 0)->result_array();
        $comment = $this->userIdTOAccount($result, $userId);
        return $comment;
    }

    /**
     * 上拉加载更多评论
     * @param $data
     * @return mixed
     */
    public function getMoreComments($data)
    {
        $userData = $this->db->select('userId')->where('account', $data['account'])->get('user')->row_array();
        $userId = $userData['userId'];
        $result = $this->db->where(array('eventId' => $data['eventId'], 'id<' => $data['id']))->order_by('timestamp', "DESC")->get('comment', 10, 0)->result_array();
        $comment = $this->userIdTOAccount($result, $userId);
        return $comment;
    }

    /**
     * 评论点赞
     * @param $data
     * @return mixed
     */
    public function loveComment($data)
    {
        $result = $this->db->select('likeNum,likeUserId')->where('id', $data['id'])->get('comment')->row_array();
        $userData = $this->db->select('userId')->where('account', $data['account'])->get('user')->row_array();
        $likeId = $userData['userId'];
        $likeUserId = $result['likeUserId'];
        $isLike = strpos($likeUserId, $likeId);



        // 查看是否点赞
        if($isLike === false)
        {
            $likeNum = $result['likeNum'] + 1;
            $likeUserId = $likeId.'-'.$result['likeUserId'];
        }
        else
        {
            $likeNum = $result['likeNum'] - 1;
            $likeUserId = str_replace($likeId.'-', "", $likeUserId);
        }

        $newData = array(
            'likeNum' => $likeNum,
            'likeUserId' => $likeUserId
        );
        $this->db->where('id', $data['id'])->update('comment', $newData);

        return $likeId;
    }


    public function getMostLikedComment($eventId, $account)
    {
        $userData = $this->db->select('userId')->where('account', $account)->get('user')->row_array();
        $userId = $userData['userId'];
        $result = $this->db->where('eventId', $eventId)->order_by('likeNum', "DESC")->get('comment',2, 0)->result_array();
        $comment = $this->userIdTOAccount($result, $userId);
        return $comment;
    }

    /**
     * 将评论中的account换成userId,并判断此人是否点赞
     * @param array $result
     * @param string $userId
     * @return array $comment
     */
    public function userIdTOAccount($result, $userId)
    {
        $comment = array();
        foreach($result as $row)
        {
            // 获得用户名
            $query = $this->db->select('account,imageUrl')->where('userId', $row['userId'])->get('user');
            if($query->num_rows() > 0)
            {
                $userData = $query->row_array();
                $userAccount = $userData['account'];
                $row['account'] = $userAccount;
                $row['imageUrl'] = $userData['imageUrl'];
            }
            else $row['account'] = "";

            // 获得父评论用户名
            $parentQuery = $this->db->select('account')->where('userId', $row['parentUserId'])->get('user');
            if($parentQuery->num_rows() > 0)
            {
                $parentUserData = $parentQuery->row_array();
                $parentUserAccount = $parentUserData['account'];
                $row['parentAccount'] = $parentUserAccount;
            }
            else $row['parentAccount'] = "";

            // 是否点赞
            // strpos 返回字符串在另一个字符串中首次出现的位置 false表示没找到
            $isLike = strpos($row['likeUserId'], $userId);
            if($isLike === false)
            {
                $row['isLike'] = 0;
            }
            else
            {
                $row['isLike'] = 1;
            }

            // 转换时间
            $datetime = strtotime($row['timestamp']);
            $row['time'] = date('m-j', $datetime);
            unset($row['timestamp']);
            // 删除无用数据
            unset($row['userId']);
            unset($row['parentUserId']);
            unset($row['likeUserId']);
            unset($row['parentId']);

            array_push($comment, $row);
        }
        return $comment;
    }

    /**
     * 删除评论
     * @param $commentId
     * @return mixed
     */
    public function deleteComment($commentId)
    {
        return $this->db->where('id', $commentId)->delete('comment');
    }
}