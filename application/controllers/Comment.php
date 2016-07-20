<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('comment_model');
    }

    /**
     * 添加评论
     */
    public function addComment()
    {
        $data = $this->input->post();
        $req = $this->comment_model->addcomment($data);
        echo $req;
    }

    /**
     * 获得评论
     */
    public function getComments()
    {
        $eventId = $this->input->post('eventId');
        $account = $this->input->post('account');
        $req = $this->comment_model->getComments($eventId, $account);
        $comment = json_encode($req, JSON_UNESCAPED_UNICODE);
        echo $comment;
    }

    /**
     * 上拉加载更多评论
     */
    public function getMoreComments()
    {
        $data = $this->input->post();
        $req = $this->comment_model->getMoreComments($data);
        $comment = json_encode($req, JSON_UNESCAPED_UNICODE);
        echo $comment;
    }

    /**
     * 点赞
     */
    public function loveComment()
    {
        $data = $this->input->post();
        $req = $this->comment_model->loveComment($data);
        echo $req;
    }

    /**
     * 在事件详情页获得评论
     */
    public function getMostLikedComment()
    {
        $eventId = $this->input->post('eventId');
        $account = $this->input->post('account');
        $req = $this->comment_model->getMostLikedComment($eventId, $account);
        $comment = json_encode($req, JSON_UNESCAPED_UNICODE);
        echo $comment;
    }

    /**
     * 删除评论
     */
    public function deleteComment()
    {
        $commentId = $this->input->post('id');
        $req = $this->comment_model->deleteComment($commentId);
        echo $req;
    }
}