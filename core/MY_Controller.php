<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public $layout = 'default';
    public $view = '';
    public $title = 'Algoritmaru';

    public $session_prefix = '';

    public $navbar_navigation = [];

    public $active_navigation_id = '';

    public $auth = null;
    public $auth_not_redirect = '';
    public $auth_redirect = '';

    public $settings = [];

    public $administrator_mode = false;

     /**
     * This is the static website folder core, please give
     * it a full absolute url.
     *
     * @var string
     */
    // public $static_folder = 'http://himaster.akbarile.me/statics/';
    public $static_folder = null;

    public function __construct()
    {
        parent::__construct();

        $this->static_folder = base_url('statics').'/';

        $this->load->model([
            'Question_model' => 'Question',
            'Setting_model' => 'Setting',
            'User_model' => 'User'
        ]);

        $this->load->library([
            'form_validation',
            'encryption'
        ]);

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        
        /**
         * Load Settings
         */
        
        $settings = $this->Setting->get_all();
        if (empty($settings))
        {
            die('Application no configured!');
        } else
        {
            foreach ($settings as $setting) {
                $this->settings[$setting->SETTING_NAME] = $setting->SETTING_VALUE;
            }
        }

        /**
         * Fetch user data
         */
        $this->fetch();

        if ($this->authenticated())
        {
            $this->navbar_navigation = [
                [
                    'id'    => 'work',
                    'name'  => 'Bekerja',
                    'link'  => '/',
                    'icon'  => 'fa fa-laptop'
                ],
                [
                    'id'    => 'logout',
                    'name'  => 'Keluar',
                    'link'  => '/auth/logout',
                    'icon'  => null
                ]
            ];
        }
        else 
        {
            $this->navbar_navigation = [
                [
                    'id'    => 'login',
                    'name'  => 'Masuk',
                    'link'  => '/auth/login',
                    'icon'  => null
                ],
                [
                    'id'    => 'register',
                    'name'  => 'Daftar',
                    'link'  => '/auth/register',
                    'icon'  => null
                ]
            ];
        }

        
    }

    protected function redirect($path) {
        return redirect(base_url($path));
    }

    protected function form_error($errorId, $errorString, $dangerLevel = 'danger') {
        $this->session->set_userdata($this->session_prefix . '_' . $errorId, '<div class="alert alert-'.$dangerLevel.'">'.$errorString.'</div>');

        return $this;
    }

    protected function show_form_error($errorId) {
        $sessionError = $this->session->userdata($this->session_prefix . '_' . $errorId);

        if ($sessionError) {
            return $sessionError;
        }

        return;
    }

    protected function disconnect() {
        if ($this->authenticated()) {
            $this->session->unset_userdata($this->session_prefix . '_auth');
        }

        return redirect($this->auth_not_redirect);
    }

    protected function authenticated() {
        return $this->session->userdata($this->session_prefix . '_auth');
    }

    protected function auth_not() {
        if ($this->authenticated()) {
            return redirect($this->auth_redirect);
        }

        return;
    }

    protected function update_setting($name, $newValue) {
        return $this->Setting->where('SETTING_NAME', $name)->update([
            'SETTING_VALUE' => $newValue
        ]);
    }

    protected function auth_only() {
        $this->fetch();

        if ($this->authenticated() && ($this->auth || $this->administrator_mode)) return;

        return $this->redirect($this->auth_not_redirect);
    }

    protected function authenticate($data) {
        $this->session->set_userdata($this->session_prefix . '_auth', $data);

        return $this;
    }

    protected function set_layout($layout_name) {
        $this->layout = $layout_name;

        return $this;
    }

    protected function fetch()
    {
        $authSession = $this->authenticated();

        if ($authSession && empty($this->auth))
        {
            if (!$this->administrator_mode)
            {
                $user = $this->User->with('question')->where_username($authSession['username'])->get();

                if ($user)
                {
                    $this->auth = $user;
                    return;
                } else
                {
                    $this->disconnect();
                    return $this->redirect($this->auth_not_redirect);
                }
            }
        }
    }

    protected function render($view, $data = null) {
        $this->_convert_dot_to_slash($view);

        $_this = $this;

        $static_folder = $_this->static_folder;

        $navbar_navigation = $_this->navbar_navigation;
        $active_navigation_id = $_this->active_navigation_id;

        return $this->load->view('layouts/' . $_this->layout, array(
            'title'         => $_this->title,
            'view'          => 'app/' . $_this->view . '/' . $view . '.php',
            'data'          => $data,
            'collections'   => array(
                'form_error' => function($errorId) {
                    $sessionError = $this->session->userdata($this->session_prefix . '_' . $errorId);

                    if ($sessionError) {

                        $this->session->unset_userdata($this->session_prefix . '_' . $errorId);

                        return $sessionError;
                    }

                    return;
                },
                // Collection of your custom function here
                // This function will generate the static link helper for view
                'static'    => function ($resource_path, $type = 'stylesheet') use($static_folder) {
                    if ($type == 'text/javascript') {
                        return '<script rel="'.$type.'" src="'.$static_folder.$resource_path.'"></script>';
                    } else {
                        return '<link rel="'.$type.'" href="'.$static_folder.$resource_path.'">';
                    }
                },
                'static_link'   => function ($resource_path) use ($static_folder) {
                    return $static_folder.$resource_path;
                },
                'navigation'    => function () use($navbar_navigation, $active_navigation_id) {
                    $html = '';

                    foreach ($navbar_navigation as $navbar) {
                        $html .= '<li class="';

                        if ($navbar['id'] == $active_navigation_id) {
                            $html .= ' active';
                        }

                        $html .= '"><a href="'.base_url($navbar['link']).'">';

                        if (!empty($navbar['icon'])) {
                            $html .= '<span class="'.$navbar['icon'].'"></span>';
                        }

                        $html .= ' '.$navbar['name'];

                        if ($navbar['id'] == $active_navigation_id) {
                            $html .= ' <span class="sr-only">(current)</span>';
                        }

                        $html .= '</a></li>';
                    }

                    return $html;
                }
            )
        ));
    }

    private function _convert_dot_to_slash(&$str) {
        $str = str_replace('.', '/', $str);
    }

}
