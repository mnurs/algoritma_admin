<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fail extends MY_Controller {

	public $auth_not_redirect = '/auth/login';
	public $auth_redirect = '/';
	
	public function __construct()
	{
		parent::__construct();

		$this->auth_only();
	}

    public function index()
    {
        return $this->render('fail');
    }
}
