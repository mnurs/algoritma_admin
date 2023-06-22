<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends CI_Controller {

	public function index()
	{
        header('Content-type: application/json');

		$this->load->library('migration');

        if ($this->migration->current() === FALSE) {
            show_error($this->migration->error_string());
        }

        echo json_encode(['status' => 'ok']);
    }

    public function seed()
    {
        $this->load->model(['Setting_model' => 'Setting']);

        $this->Setting->insert([
            [
                'SETTING_NAME' => 'REGISTER_OPENED',
                'SETTING_VALUE' => 0
            ],
            [
                'SETTING_NAME' => 'REGISTER_CLOSE_MSG',
                'SETTING_VALUE' => 'Pendaftaran telah ditutup.'
            ],
            [
                'SETTING_NAME' => 'RESET_START_LEVEL',
                'SETTING_VALUE' => 1
            ],
            [
                'SETTING_NAME' => 'CAPTCHA_START_LEVEL',
                'SETTING_VALUE' => 1
            ],
            [
                'SETTING_NAME' => 'ANTIBOT_START_LEVEL',
                'SETTING_VALUE' => 1
            ],
            [
                'SETTING_NAME' => 'PRESIDENT_KEY',
                'SETTING_VALUE' => sha1('tampandanberani')
            ]
        ]);
    }
    
}
