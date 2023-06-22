<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class President extends MY_Controller {

    public $auth_not_redirect = '/president/auth';
    public $auth_redirect = '/president';
    
    public $administrator_mode = true;

    /** To make sure the session not shared with user */
    public $session_prefix = 'admin';

    public $view = 'president';

	public function __construct()
	{
        parent::__construct();
        
        /** Reset the navigation */
        $this->navbar_navigation = [];

        if (!$this->authenticated())
        {
            $this->navbar_navigation = [
                [
                    'id'    => 'login',
                    'name'  => 'Masuk',
                    'link'  => '/president/auth',
                    'icon'  => null
                ]
            ];
        } else
        {
            $this->navbar_navigation = [
                [
                    'id'    => 'home',
                    'name'  => 'President',
                    'link'  => '/president',
                    'icon'  => 'fa fa-bus'
                ],
                [
                    'id'    => 'logout',
                    'name'  => 'Keluar',
                    'link'  => '/president/logout',
                    'icon'  => null
                ]
            ];
        }
	}

    public function logout()
    {
        $this->auth_only();

        return $this->disconnect();
    }

    public function question($id)
    {
        $this->auth_only();

        $canDelete = false;

        $question = $this->Question->where_id($id)->get();

        if (!$question)
            show_404();

        if ($question)
        {
            $getMaxId = $this->Question->fields('id')->order_by('id', 'desc')->get();

            if ($question->id == $getMaxId->id)
            {
                $canDelete = true;
            }
        }

        if ($canDelete)
        {
            if ($this->input->post('delete'))
            {
                /** Delete data or question */
                $this->Question->where_id($id)->delete();
                /** Alert table to reset auto increment */
                $this->db->query('ALTER TABLE `algoritmaru_questions` auto_increment = 1;');

                return $this->redirect($this->auth_redirect);
            }
        }

        $question_new = $this->input->post('question');
        $answer = $this->input->post('answer');

        if (!empty($question_new) && !empty($answer))
        {
            $this->Question->where_id($id)->update([
                'question' => $question_new,
                'answer' => $answer
            ]);

            $this->form_error('form_edit_question', '<strong>Sukses!</strong> Level berhasil diubah.', 'success');

            return $this->redirect('/president/question/' . $id);
        }

        return $this->render('question_edit', [
            'question' => $question,
            'canDelete' => $canDelete
        ]);

    }

    public function new_question()
    {
        $this->auth_only();

        $question = $this->input->post('question');
        $answer = $this->input->post('answer');

        if (!empty($question) && !empty($answer))
        {
            $this->Question->insert([
                'question' => $question,
                'answer' => $answer
            ]);

            $this->form_error('form_new_question', 'Level berhasil ditambahkan.', 'success');
        }

        return $this->render('new_question');
    }

	public function index()
	{
        $this->auth_only();

        $updated = false;

        if ($setting_register_close_msg = $this->input->post('REGISTER_CLOSE_MSG'))
        {
            if (!empty($setting_register_close_msg)) {
                $this->update_setting('REGISTER_CLOSE_MSG', $setting_register_close_msg);
                /** Flip the register setting */
                $this->update_setting('REGISTER_OPENED', !$this->settings['REGISTER_OPENED']);

                $updated = true;
            }
        }

        if ($reset_start_level = $this->input->post('RESET_START_LEVEL'))
        {
            if (!empty($reset_start_level) && is_numeric($reset_start_level) && $reset_start_level > 0)
            {
                $this->update_setting('RESET_START_LEVEL', $reset_start_level);

                $updated = true;
            }
        }

        if ($captcha_start_level = $this->input->post('CAPTCHA_START_LEVEL'))
        {
            if (!empty($captcha_start_level) && is_numeric($captcha_start_level) && $captcha_start_level > 0)
            {
                $this->update_setting('CAPTCHA_START_LEVEL', $captcha_start_level);

                $updated = true;
            }
        }

        if ($antibot_start_level = $this->input->post('ANTIBOT_START_LEVEL'))
        {
            if (!empty($antibot_start_level) && is_numeric($antibot_start_level) && $antibot_start_level > 0)
            {
                $this->update_setting('ANTIBOT_START_LEVEL', $antibot_start_level);

                $updated = true;
            }
        }

        $level_repository = [];
        $users = $this->User->fields('current_id_soal')->get_all();

        if ($users)
        {
            foreach ($users as $user)
            {
                if (empty($level_repository[$user->current_id_soal]))
                {
                    $level_repository[$user->current_id_soal] = 1;
                } else
                {
                    $level_repository[$user->current_id_soal] = $level_repository[$user->current_id_soal] + 1;
                }
            }
        }

        if ($updated)
            return $this->redirect($this->auth_redirect);

        return $this->render('index', [
            'settings' => $this->settings,
            'questions' => $this->Question->get_all(),
            'level_repository' => $level_repository,
            'users' => $this->User->get_all(),
        ]);
    }
    
    public function auth()
    {
        $this->auth_not();

        $this->form_validation->set_rules('access', 'Access Code', 'required');

        if ($this->form_validation->run() == TRUE)
        {
            $access = $this->input->post('access');

            if (sha1($access) == $this->settings['PRESIDENT_KEY'])
            {
                $this->authenticate([
                    'access' => true
                ]);

                return $this->redirect($this->auth_redirect);
            }
        }

        return $this->render('auth');
    }
}
