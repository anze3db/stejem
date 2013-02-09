<?php
class Model_Work extends Zend_Db_Table_Abstract{

    protected $_name="work";
    protected $_primary = "id";
    protected $user;

    public function init(){
        $auth = Zend_Auth::getInstance();
        $this->user = $auth->getStorage()->read();
    }
    
    public function get($id=0, $ids = array()){
        
        if($id == 0){
            $id = $this->user->id;
        }
        $select = $this->select();


            $select->from('work as w',
                        array('*',
                              'difference' =>
                                  new Zend_Db_Expr("IF(w.end_time>w.start_time, TIMEDIFF(w.end_time, w.start_time), TIMEDIFF('24:00', ABS(TIMEDIFF(w.end_time, w.start_time))))"),
                              'earnings' =>
                                  new Zend_Db_Expr("TIME_TO_SEC(IF(w.end_time>w.start_time, TIMEDIFF(w.end_time, w.start_time), TIMEDIFF('24:00', ABS(TIMEDIFF(w.end_time, w.start_time)))))*w.wage/3600"),
                              'seconds' =>
                                  new Zend_Db_Expr("TIME_TO_SEC(IF(w.end_time>w.start_time, TIMEDIFF(w.end_time, w.start_time), TIMEDIFF('24:00', ABS(TIMEDIFF(w.end_time, w.start_time)))))"),
                                  )
                        )
			->joinLeft('jobs as j', 'w.id_job = j.id', 'name')
               ->order(array('w.id','w.date ASC', 'w.start_time ASC'))
	       ->setIntegrityCheck(false)
               ->where('w.deleted = 0 AND w.id_clearance = 0 AND w.id_user = ? ', $id);
 
        if(!empty($ids)){
            $select->where('w.id IN ('.implode(",",$ids).')');
        }

        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("Napaka pri pobiranju podatkov");
        }
        $sum = 0.0;
        $seconds = 0;
        $arr = $row->toArray();
	$j = 0;
        foreach($arr as $i){

            $sum += $i['earnings'];
            $seconds += $i['seconds'];
        }
	
	 
        $sum_work = sprintf( "%02.2d:%02.2d", floor( $seconds / 3600 ), floor(($seconds % 3600)/60));
        return array('sum_earnings'=>$sum, 'sum_work'=>$sum_work, 'work'=>$arr);

    }
    public function getById($id){
        return $this->fetchRow('id = '. (int)$id . ' AND id_user = '.$this->user->id)->toArray();
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
    public function add($values){

        /*START PSYG BUG*/
        $datum = $values['date'];
        $select = $this->select();
        $select->from('work')

               ->where('deleted = 0 AND id_clearance = 0 AND id_user = ?', $this->user->id)
               ->where('date = ?', new Zend_Db_Expr("str_to_date('$datum', '%d.%m.%Y')"))
               ->where("start_time < '".$values['end_time']."' AND start_time >= '". $values['start_time']."' ".
                       "OR start_time <= '".$values['start_time']."' AND end_time >= '". $values['end_time']."' ".
                       "OR end_time > '".$values['start_time']."' AND end_time <= '". $values['end_time']."'");
                       

        $row = $this->fetchAll($select);
        if ($row->count()>0) {
            throw new Exception("Duplicate work", 40);
        }
        /*END PSYG BUG*/
        
        $data = array(
            'id_user' => $this->user->id,
            'id_job' => $values['id_job'],
            'date' => new Zend_Db_Expr("str_to_date('$datum', '%d.%m.%Y')"),
            'start_time' => $values['start_time'],
            'end_time' => $values['end_time'],
            'wage' => str_replace(",", ".", $values['wage']),
        );
        $this->insert($data);
    }
    public function edit($vals){

        $datum = $vals['date'];
        $data = array(
            'id_user' => $this->user->id,
            'id_job' => $vals['id_job'],
            'date' => new Zend_Db_Expr("str_to_date('$datum', '%d.%m.%Y')"),
            'start_time' => $vals['start_time'],
            'end_time' => $vals['end_time'],
            'wage' => str_replace(",", ".", $vals['wage']),

        );
        
        $this->update($data, 'id = '. (int)$vals['id'] . ' AND id_user = ' . $this->user->id);

    }
    public function updateClearance($ids){
	
	
        $result = $this->get(0, $ids);
        $clear = new Model_Clearance();
        $id = $clear->add($result);

        $data = array(
          'id_clearance' => $id,
        );
        $this->update($data, "id IN (".implode(",", $ids).")");
    }
    public function deleteClearance($id){

        $data = array(
          'id_clearance' => 0,
        );
        $this->update($data, "id_clearance = $id");
    }
}
?>
