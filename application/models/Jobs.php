<?php

class Model_Jobs extends Zend_Db_Table_Abstract{

    protected $_name="jobs";
    protected $_primary = "id";

    protected $user;

    public function init(){
        
        $auth = Zend_Auth::getInstance();
        $this->user = $auth->getStorage()->read();
    }

    public function getDefault(){
        $select = $this->select();
        $select->where("deleted = 0 AND active = 1 AND id_user=?", $this->user->id)
                ->order("primary DESC");
        $row = $this->fetchRow($select);

        return $row->toArray();

    }
    public function getById($id){
        $select = $this->select();
        $select->where("deleted = 0 AND active = 1 AND id_user=?", $this->user->id)
               ->where("id = ?", $id);
        $row = $this->fetchRow($select);

        return $row->toArray();

    }
    public function edit($vals){
        $data = array(
            'name' => $vals['name'],
            'wage' => str_replace(",", ".", $vals['job_wage']),
            'start_time' => $vals['job_start_time'],
            'end_time' => $vals['job_end_time'],

        );
        $this->update($data, 'id = '. (int)$vals['id']. ' AND id_user =' . $this->user->id);
        if($vals['job_primary']){
            $this->primary($vals['id']);
        }

    }
    public function add($vals){

        
        
        $data = array(
            'id_user' => $this->user->id,
            'name' =>  $vals['name'],
            'wage' =>  str_replace(",", ".", $vals['job_wage']),
            'start_time' => $vals['job_start_time'],
            'end_time' => $vals['job_end_time'],
            
        );
        $id = $this->insert($data);
        if($vals['job_primary']){
            $this->primary($id);
        }

    }
    public function getNames(){

        

        $select = $this->select();
        $select->where("deleted = 0 AND active = 1 AND id_user=?", $this->user->id)
                ->order("primary DESC");


        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("Napaka pri pobiranju podatkov");
        }
        $result = array();
        $rows = $row->toArray();
        foreach ($rows as $row){
             $result[$row['id']]= $row['name'] ;
        }
        return $result;

    }

    public function get(){
        $select = $this->select();
        $select->where("deleted = 0 AND id_user=?", $this->user->id);


        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("Napaka pri pobiranju podatkov");
        }

        return $row->toArray();
    }

    public function hasJob(){
        if(count($this->get())>0)
            return true;
        return false;

    }
    public function delete($id){
        
         $data = array(
            'deleted' => 1,
        );
        $n = $this->update($data, 'id = '. (int)$id . ' AND id_user = '.$this->user->id);
        if($n < 1){
            throw new Exception("Can't delete", 41);
        }
    }
    public function primary($id){
        $this->update(array('primary' => 0), 'id_user = '.$this->user->id);        

        $n = $this->update(array('primary' => 1), 'id = '. (int)$id . ' AND id_user = '.$this->user->id);

        if($n < 1){
            throw new Exception("Can't make primary", 43);
        }
    }

}

