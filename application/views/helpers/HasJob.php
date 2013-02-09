<?php

class Zend_View_Helper_HasJob {

    function hasJob(){

        $jobs = new Model_Jobs();
        return $jobs->hasJob();


    }
}