<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 意见反馈
     * @param $account
     * @param $content
     * @return mixed
     */
    public function getSuggestion($account, $content)
    {
        $userData = $this->db->select('userId')->where('account', $account)->get('user')->row_array();
        $userId = $userData['userId'];

        $req = $this->db->insert('suggestion', array(
           'userId' => $userId,
            'content' => $content
        ));
        return $req;
    }
}