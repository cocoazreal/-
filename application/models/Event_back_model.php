<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_back_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获得数据库中事件的最大id
     * @return int
     */
    public function getEventID()
    {
        $EventID = $this->db->select_max('id')->get('event')->row_array();
        if($EventID['id'] == null)
        {
            return 1;
        }
        else
        {
            return $EventID['id']+1;
        }
    }

    /**
     * 插入事件数据
     * @param $data
     * @return mixed
     */
    public function insertEvent($data)
    {
        if($data['recommend'] == 'true')
        {
            $data['recommend'] = 1;
        }
        else $data['recommend'] = 0;

        $num = $this->db->where(['title'=>$data['title'], 'datetime'=>$data['datetime']])->get('event')->num_rows();
        if($num > 0)
        {
            $this->db->set(
                array(
                    'eventType' => $data['eventType'],
                    'recommend' => $data['recommend'],
                    'preTitle' => $data['preTitle'],
                    'title' => $data['title'],
                    'detail' => $data['detail'],
                    'tradeBasis' => $data['tradeBasis'],
                    'datetime' => $data['datetime']
                )
            )->where(['title'=>$data['title'], 'datetime'=>$data['datetime']])->update('event');
            $result = 2;
        }
        else
        {
            $result = $this->db->insert('event',array(
                'eventType' => $data['eventType'],
                'recommend' => $data['recommend'],
                'preTitle' => $data['preTitle'],
                'title' => $data['title'],
                'detail' => $data['detail'],
                'tradeBasis' => $data['tradeBasis'],
                'datetime' => $data['datetime']
            ));
        }
        return $result;
    }

    /**
     * 更新数据
     */
    public function updateEvent($data)
    {
        $num = $this->db->where('id', $data['id'])->get('event')->num_rows();

        if ($num > 0)
        {
            $this->db->set(
                array(
                    'eventType' => $data['eventType'],
                    'recommend' => $data['recommend'],
                    'preTitle' => $data['preTitle'],
                    'title' => $data['title'],
                    'detail' => $data['detail'],
                    'tradeBasis' => $data['tradeBasis'],
                    'datetime' => $data['datetime']
                )
            )->where('id', $data['id'])->update('event');

            $result = 1;
        }

        return $result;
    }

    /**
     * 后台获得所有的事件
     * @param $data
     * @return string
     */
    public function getAllEvent($data)
    {
        // 获得分页设置
        $config = require(dirname(__FILE__)."/../config/pagination.php");

        $config['base_url'] = 'http://115.28.73.97/event_back/getallevent';
        $config['total_rows'] = $this->db->count_all('event');
        $config['per_page'] = '10';
        $page = $this->uri->segment(3);
        if($page != 0) $page = ($page - 1) * 10;
        $result = $this->db->select('id,eventType,recommend,preTitle,title,detail,datetime,isValid,tradeBasis')->order_by('id', 'DESC')->get('event',10,$page);
        $event = $result->result_array();

        $this->pagination->initialize($config);
        $this->table->set_heading('事件id', '事件类型', '是否推荐', '预览标题', '正文标题', '事件内容', '有效期至', '是否有效','清算依据','操作');
        return $event;
    }

    public function deleteEvent($eventId)
    {
        $this->db->where("id", $eventId)->delete('event');
    }

    public function getEvent($data)
    {
        $isValid = $data['isValid'];
        $type = $data['type'];
        $page = $data['page'];
        $recommend = $data['recommended'];

        if ($isValid == "" and $type == "" and $recommend =="")
        {
            $req = $this->db->select("id,eventType,preTitle,title,recommend,detail,datetime,isValid,isCalcu,tradeBasis")
                ->order_by("id", "DESC")->get('event', 10, ($page - 1)*10);
            $total = $this->db->get('event')->num_rows();
        }
        elseif ($isValid == "" and $type == "")
        {
            $req = $this->db->select("id,eventType,preTitle,title,recommend,detail,datetime,isValid,isCalcu,tradeBasis")
                ->where('recommend', $recommend)->order_by("id", "DESC")->get('event', 10, ($page - 1)*10);
            $total = $this->db->where('recommend', $recommend)->get('event')->num_rows();
        }
        elseif ($isValid == "" and $recommend == "")
        {
            $req = $this->db->select("id,eventType,preTitle,title,recommend,detail,datetime,isValid,isCalcu,tradeBasis")
                ->where('eventType', $type)->order_by("id", "DESC")->get('event', 10, ($page - 1)*10);
            $total = $this->db->where('eventType', $type)->get('event')->num_rows();
        }
        elseif ($type == "" and $recommend == "")
        {
            $req = $this->db->select("id,eventType,preTitle,title,recommend,detail,datetime,isValid,isCalcu,tradeBasis")
                ->where('isValid', $isValid)->order_by("id", "DESC")->get('event',  10, ($page - 1)*10);
            $total = $this->db->where('isValid', $isValid)->get('event')->num_rows();
        }
        elseif ($type == "")
        {
            $req = $this->db->select("id,eventType,preTitle,title,recommend,detail,datetime,isValid,isCalcu,tradeBasis")
                ->where(['recommend'=>$recommend,'isValid'=>$isValid])
                ->order_by("id", "DESC")->get('event', 10, ($page - 1)*10);
            $total = $this->db->where(['recommend'=>$recommend,'isValid'=>$isValid])->get('event')->num_rows();
        }
        elseif ($recommend == "")
        {
            $req = $this->db->select("id,eventType,preTitle,title,recommend,detail,datetime,isValid,isCalcu,tradeBasis")
                ->where(['eventType'=>$type,'isValid'=>$isValid])
                ->order_by("id", "DESC")->get('event', 10, ($page - 1)*10);
            $total = $this->db->where(['eventType'=>$type,'isValid'=>$isValid])->get('event')->num_rows();
        }
        elseif ($isValid == "")
        {
            $req = $this->db->select("id,eventType,preTitle,title,recommend,detail,datetime,isValid,isCalcu,tradeBasis")
                ->where(['eventType'=>$type,'recommend'=>$recommend])
                ->order_by("id", "DESC")->get('event', 10, ($page - 1)*10);
            $total = $this->db->where(['eventType'=>$type,'recommend'=>$recommend])->get('event')->num_rows();
        }
        else
        {
            $req = $this->db->select("id,eventType,preTitle,title,recommend,detail,datetime,isValid,isCalcu,tradeBasis")
                ->where(['eventType'=>$type,'recommend'=>$recommend,'isValid'=>$isValid])
                ->order_by("id", "DESC")->get('event', 10, ($page - 1)*10);
            $total = $this->db->where(['eventType'=>$type,'recommend'=>$recommend,'isValid'=>$isValid])->get('event')->num_rows();
        }
        $result['event'] = $req->result_array();
        $result['total'] = ceil($total / 10);
        return $result;


    }
}