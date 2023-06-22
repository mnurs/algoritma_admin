<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model {

    public $table = 'users';
    public $primary_key = 'id';

	public function __construct() {
        parent::__construct();
        
        $this->has_one['question'] = array('Question_model', 'id', 'current_id_soal');
	}

}
