<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    public $auth_not_redirect = '/auth/login';
    public $auth_redirect = '/';
    
    public $view = 'auth';

	public function __construct()
	{
		parent::__construct();
	}

    public function logout()
    {
        return $this->disconnect();
    }

	public function index()
	{
        $this->auth_not();

        return $this->redirect($this->auth_not_redirect);
    }
    
    public function login()
    {
        $this->auth_not();

        $this->form_validation->set_rules('username', 'Username', 'required|max_length[24]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

        if ($this->form_validation->run() == TRUE)
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->User->where_username($username)->get();
            /**
             * Jika user tersedia dan password sama
             */
            if ($user && $this->encryption->decrypt($user->password) == $password)
            {

                /** Update last login */
                $this->User->where_username($user->username)->update([
                    'last_login' => date('Y-m-d H:i:s')
                ]);

                $this->authenticate([
                    'username' => $user->username
                ]);

                return $this->redirect($this->auth_redirect);
            } else
            {
                $this->form_error('form_login', 'Username atau password tidak ditemukan.');
            }
        }

        return $this->render('login');
    }

    public function register()
    {
        $this->auth_not();

        $register_open = $this->settings['REGISTER_OPENED'];

        if ($register_open)
        {
            $this->form_validation->set_rules('name', 'Nama Lengkap', 'required|max_length[100]');
            $this->form_validation->set_rules('username', 'Username', 'required|max_length[24]|callback__username_unique');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

            if ($this->form_validation->run() == TRUE)
            {
                $data = [
                    'name' => $this->input->post('name'),
                    'username' => $this->input->post('username'),
                    'password' => $this->encryption->encrypt($this->input->post('password')),
                ];

                $this->User->insert($data);

                $this->form_error('form_login', 'Akun anda berhasil dibuat, silahkan masuk.', 'success');

                return $this->redirect($this->auth_not_redirect);
            }
        }

        return $this->render('register', [
            'open' => $register_open,
            'message' => $this->settings['REGISTER_CLOSE_MSG']
        ]);
    }

    public function _username_unique($username)
    {
        $user = $this->User->where_username($username)->as_array();
        
        if ($user->count_rows() == 0)
        {
            return true;
        }

        $this->form_validation->set_message('_username_unique', 'Username telah digunakan, gunakan nama pengguna yang lain.');
        return false;
    }
}
