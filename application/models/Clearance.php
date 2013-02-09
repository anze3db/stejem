<?php

class Model_Clearance extends Smotko_Db_Table_Abstract{

    protected $_name="clearance";
    protected $_primary = "id";

    public function add($vals){

        $id_job = $vals['work'][0]['id_job'];
        $start = $vals['work'][0]['date'];
        $end = $vals['work'][count($vals['work'])-1]['date'];
	
        $data = array(
            'id_user' => $this->user->id,
            'id_job' => $id_job,
            'sum_earnings' =>    $vals['sum_earnings'],
            'sum_work' => $vals['sum_work'],
            'date_start' => $start,
            'date_end' => $end,            
        );
        return $this->insert($data);
    }
    public function get($id=0, $year=0){

        if($id==0){
            $id =  $this->user->id;
        }
        $select = $this->select();
        $select->where("clearance.deleted = 0 AND clearance.id_user=?", $id)
               ->setIntegrityCheck(false)
		//->joinLeft("jobs", "clearance.id_job = jobs.id", "*")
               ->order('date_end DESC')
               ->from('clearance',
                    array('*',
                          'seconds' =>
                              new Zend_Db_Expr("TIME_TO_SEC(sum_work)"),
                              )
                    );
        if($year>0){
            $select->where("date_end >= cast('" . $year . "-01-01' as date) AND date_end < cast('" . ($year+1). "-01-01' as date)");
        }
        
        $row = $this->fetchAll($select);
        
        if (!$row) {
            throw new Exception("Napaka pri pobiranju podatkov");
        }

        $sum = 0.0;
        $seconds = 0;
        $arr = $row->toArray();
        foreach($arr as $i){
            $sum += $i['sum_earnings'];
            $seconds += $i['seconds'];
        }
        $sum_clearance = sprintf( "%02.2d:%02.2d", floor( $seconds / 3600 ), floor(($seconds % 3600)/60));
        return array('sum_earnings'=>$sum, 'sum_clearance'=>$sum_clearance, 'clearance'=>$arr);
        

    }
    public function getByYear($year){
        return $this->get(0, $year);

    }
    public function getYears(){

        $id = $this->user->id;
        $select = $this->select();
        $select->where("clearance.deleted = 0 AND clearance.id_user=?", $id)
               ->order('date_end ASC')
               ->limit(1)
               ->setIntegrityCheck(false);
        $row = $this->fetchRow($select);
        
        if(!$row)
                return array();

        $arr = $row->toArray();
        $oldestYear = (int)substr($arr['date_end'], 0, 4);
        $year = (int)date('Y');
        $years = array();
        while($oldestYear <= $year)
            array_push($years, $oldestYear++);
        
        return array_reverse($years);
        
    }
    public function delete($id){
        //TODO: Transaction :F
        $work = new Model_Work();
        $work->deleteClearance($id);
        parent::delete($id);
    }
}

