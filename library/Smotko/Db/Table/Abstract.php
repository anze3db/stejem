<?php

class Smotko_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
    protected $user;

    public function init(){
       
        $auth = Zend_Auth::getInstance();
        $this->user = $auth->getStorage()->read();
    }
    public function delete($id){


        $data = array(
            'deleted' => 1,
        );
        $where =  'id = '. (int)$id;
        if((int)$this->user->admin != 1)
                $where .= ' AND id_user = '.$this->user->id;

        $n = $this->update($data, $where);

        if($n < 1){
            throw new Exception("Can't delete", 41);
        }
    }
}
