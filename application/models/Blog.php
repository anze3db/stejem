<?php
class Model_Blog extends Smotko_Db_Table_Abstract
{

    protected $_name="blog";
    protected $_primary = "id";

    public function getAll(){
        
        $select = $this->select()
                       ->from(array('b'=>'blog'))
                       ->setIntegrityCheck(false)
                       ->join('users', 'b.id_user = users.id', array('name', 'admin', 'website'))
                       ->order('b.date DESC')
                       ->where('deleted = 0')
                       ->limit('20')
                       ->order('date');
        return $this->fetchAll($select)->toArray();
    }
    
    public function add($values){

        $data = array(
            'id_user' => $this->user->id,
            'title' => $values['blog_title'],
            'content' => $values['blog_content'],
            'active' => $values['active'],
            'date' => date('Y-m-d H:i:s')
        );
        $this->insert($data);
    }
    public function change($values){
        $data['content'] = $values['blog_content'];
        $data['title'] = $values['blog_title'];
        $data['active'] = $values['active'];

        $this->update($data, 'id = '.$values['id']);
    }
    public function getFeed(){

        $arr = $this->getAll();
        
        $i = 0;
        $entries = array();
        foreach ($arr AS $blog) {
            if(!isset($published)){
                $published = (int)strtotime($blog['date']);
            }
            //TODO: Set permalink & link should not be /stran/.
            $entry = array(
                'guid'          => $blog['id'],
                'title'       => $blog['title'],
                'link'        => 'http://stejem.si/blog/index/stran/' . ++$i,
                'lastUpdate' => (int)strtotime($blog['date']),
                'description' => nl2br($blog['content']),
                'author'     => $blog['name'],
            );
            array_push($entries, $entry);
        }

        // Create the RSS array
        return array(
            'title'   => 'Štejem.si - blog',
            'link'    => 'http://stejem.si/blog',
            'charset' => 'utf-8',
            'published' => $published,
            'entries' => $entries
        );
    }
}

