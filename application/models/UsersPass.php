<?php

class Model_UsersPass extends Zend_Db_Table_Abstract{

    protected $_name="users_pass";
    protected $_primary = "id";

    public function insertToken($id){
        $token = md5($id.time()."h3!Iw0W#");

        $data = array('id_user' => $id,
                      'token'   => $token,
                      'time'    => date('Y-m-d H:i:s'),
                     );
        $this->insert($data);
        return $token;

    }
    public function removeToken($id){

        $data = array(
	    'active' => 0,
	);
        $this->update($data, 'id = '. (int)$id);
    }
    public function checkToken($id_user, $token){

        $select = $this->select();
        $select->where("id_user = " . $id_user . " AND  token = '" . $token . "'");
        $row = $this->fetchAll($select);

        $arr = $row->toArray();
        
        if(count($arr)==0){
            throw new Exception("Invalid token", 14);
        }

        return $arr[0]['id'];
    }

    

}

