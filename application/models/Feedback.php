<?php
class Model_Feedback extends Smotko_Db_Table_Abstract{

    protected $_name="feedback";
    protected $_primary = "id";
   
    public function get($id_blog){
        
        $select = $this->select()
                       ->from(array('f'=>'feedback'))
                       ->setIntegrityCheck(false)
                       //->joinLeft('users', 'f.id_user = users.id', '*')
                       ->order('f.id ASC')
                       //->limit('20')
                        ->where('deleted = 0 AND id_blog = ' . $id_blog )
        ;

        return $this->fetchAll($select)->toArray();

    }
    public function add($values){

        //Update user info if changed:
        if($this->user !== null && ($this->user->name != $values['feedback_name'] || $this->user->website != $values['feedback_page'])){
            $user = new Model_Users();
            $user->updateUser($values, $this->user->id);

        }
        //set http:// in front of website
        if($values['feedback_page']){
            $values['feedback_page'] = 'http://' . str_replace('http://', '', $values['feedback_page']);
        }
        $data = array(
            'id_user'       => $this->user->id,
            'id_blog'       => $values['id_blog'],
            'user_name'     => $values['feedback_name'],
            'user_website'  => $values['feedback_page'],
            'added'         => date('Y-m-d H:i:s'),
            'feedback'      => $values['feedback'],
        );
        $this->insert($data);
    }

}
