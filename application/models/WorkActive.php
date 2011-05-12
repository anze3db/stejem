<?php
class Model_WorkActive extends Zend_Db_Table_Abstract{

    protected $_name="work_active";
    protected $_primary = "id";
    protected $user;

    public function init(){
        $auth = Zend_Auth::getInstance();
        $this->user = $auth->getStorage()->read();
    }

    public function get(){

        $select = $this->select();
        $select->where(" id_user = ? AND active = 1 ", $this->user->id)
               ->from('work_active', array('*', 'difference' => new Zend_Db_Expr('TIMEDIFF(CURTIME(), start_time)'),
                                                'earnings' => new Zend_Db_Expr('ROUND(TIME_TO_SEC(TIMEDIFF(CURTIME(), start_time)) * wage/60/60, 2)')));
        $row = $this->fetchRow($select);
        if ($row) {
           
            $arr = $row->toArray();
            $arr['now'] = date("G:i:s");
             $sec = abs(strtotime($arr['now']) - strtotime($arr['start_time']));
            
            $arr['difference'] = floor($sec/60/60) . 'h ' . floor($sec/60)%60 . 'm';
            $arr['earnings'] = round(abs(strtotime($arr['now']) - strtotime($arr['start_time'])) * $arr['wage']/60/60, 2);
            
            return $arr;
        }
    }
    public function start($vals){

        $arr = $this->get();
        if(!is_array($arr)){
            $data = array(
                'id_user' => $this->user->id,
                'id_job' =>  $vals['id_job'],
                'active' => 1,
                'wage' =>  str_replace(",", ".", $vals['wage']),
                'start_time' => date("Y-m-d G:i:s"),
                'date' =>  date("Y-m-d"),
            );        
            $this->insert($data);
        }
    }
    public function end(){
        $data = $this->get();
        if(is_array($data)){
	    
            $data["end_time"] = date("Y-m-d G:i:s");
            $data["date"] = date("d.m.Y");
            try{
            $work = new Model_Work();
            $work->add($data);
            }
            catch (Exception $ex){
                $update = array(
                    'end_time' => $data["end_time"],
                    'active' => 0
                );
                $this->update($update, "id = ". $data["id"]);
                throw $ex;
            }
            $update = array(
              'end_time' => $data["end_time"],
              'active' => 0
            );
            $this->update($update, "id = ". $data["id"]);
        }
        else{

            throw new Exception("Not working", 41);
        }

    }
}
?>
