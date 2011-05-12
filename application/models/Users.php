<?php

class Model_Users extends Zend_Db_Table_Abstract{

    private $salt = ":;)(!_pIkA33##312";
    protected $_name="users";
    protected $_primary = "id";
    

    public function auth($vals){
        
        $mail = $vals['user_login'];
        $pass = $vals['pass_login'];
        
        if(empty($mail) || empty($pass))            
            throw new Exception("Empty login", 10);
        if($vals['remember_login'] == "1")
            Zend_Session::rememberMe();
        
            
        $auth = Zend_Auth::getInstance();
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->_db);

        $authAdapter
        ->setTableName('users')
        ->setIdentityColumn('mail')
        ->setCredentialColumn('pass')
        ->setCredentialTreatment('SHA(?)')
        ;

        $authAdapter
        ->setIdentity($mail)
        ->setCredential($pass.$this->salt)
        ;

        $result = $auth->authenticate($authAdapter);
        
        if (!$result->isValid()) {
            throw new Exception('Login failed', 11);
        }
        else{
            $data = $authAdapter->getResultRowObject(null);
            $auth->getStorage()->write($data);
        }
        
    }
    public function reg($vals){
        $mail = $vals['email'];
        $pass1 = $vals['pass1'];
        $pass2 = $vals['pass2'];
        if(empty($mail) || empty($pass1))
            throw new Exception("Empty register", 20);
        if($pass1 != $pass2){
            throw new Exception("Password mismatch", 21);
        }

        $data = array(
            'mail' => $mail,
            'pass' =>  new Zend_Db_Expr("SHA('".$pass1.$this->salt."')"),
        );
        $this->insert($data);
        $this->auth(array('user_login'=>$mail, 'pass_login'=>$pass1));
    }
    public function updateUser($vals, $id){

	$data = array(
	    'name' => $vals['feedback_name'],
	    'website' => $vals['feedback_page'],
	);
        $this->update($data, 'id = '. (int)$id);
	

    }
    public function changePassword($vals, $id){

        if($vals['pass1'] != $vals['pass2'])
            throw new Exception('Password mismatch', 15);
        $data = array(
	    'pass' =>  new Zend_Db_Expr("SHA('".$vals['pass1'].$this->salt."')"),
	);
        $this->update($data, 'id = '. (int)$id);


    }
    public function getAll(){
        $select = $this->select()
                       ->from(array('u'=>'users'))
                       ->setIntegrityCheck(false)
                       ->joinLeft('work_active', '(work_active.id_user =  u.id AND active =1 )', array('active', 'start_time'))
            ;
            
        $users = $this->fetchAll($select)->toArray();
        $work = new Model_Work();
        for($i = 0; $i < count($users); $i++){
            
            $w = $work->get($users[$i]['id']);
            $users[$i]['sum_work'] = $w['sum_work'];
            $users[$i]['sum_earnings'] = $w['sum_earnings'];
        }
        return $users;

    }
    public function getOne($id){
        $select = $this->select();
        $select->where("id = ?", $id);
	
        $row = $this->fetchRow($select);

        return $row->toArray();

    }
    public function getOneByMail($mail){
        $select = $this->select();
        $select->where("mail = ?", $mail);

        $row = $this->fetchAll($select);
        $arr = $row->toArray();
        if(count($arr) == 0)
            throw new Exception("No Email in db", "13");
        return $arr[0]['id'];
    }

}

