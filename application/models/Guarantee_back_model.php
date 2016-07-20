<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Guarantee_back_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function insertGuar($data)
    {
        $result = $this->db->insert('guarantee', array(
            'title' => $data['title'],
            'price' => $data['price'],
            'content' => $data['content']
        ));

        return $result;
    }

    public function getGuarMaxId()
    {
        $result = $this->db->select_max('id')->get('guarantee')->row_array();
        return $result['id'] + 1;
    }
}