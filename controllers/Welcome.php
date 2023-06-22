<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public $auth_not_redirect = '/auth/login';
	public $auth_redirect = '/';
	
	public function __construct()
	{
		parent::__construct();

		$this->load->helper([
			'captcha'
		]);

		$this->auth_only();
	}

	/**
	 * Return true if current soal more than soal
	 */
	private function isCompleted ($auth, $question_maximum) {
		return ($auth->current_id_soal > $question_maximum) === true;
	}

	public function index()
	{
		$captcha = false;
		$captcha_data = null;

		$antibot = false;

		$reset = false;

		$question_maximum = $this->Question->where(1)->as_array()->count_rows();

		function build_query($arrays)
		{
			$return = '';

			$i = 0;

			foreach ($arrays as $key => $value)
			{
				$return .= $key . '=' . str_replace(['%27', '%28', '%29', '%2A', '%21'], ["'", '(', ')', '*', '!'], rawurlencode($value));

				if ($i++ != count($arrays) - 1)
				{
					$return .= '&';
				}
			}

			return $return;
		}

		if (!empty($this->auth->question))
		{
			/* Jika terdapat captcha di settings */
			if ($this->auth->question->id >= $this->settings['CAPTCHA_START_LEVEL'])
			{
				$captcha = true;
			}

			if ($this->auth->question->id >= $this->settings['ANTIBOT_START_LEVEL'])
			{
				$antibot = true;
			}

			if ($this->auth->question->id >= $this->settings['RESET_START_LEVEL'])
			{
				$reset = true;
			}

			$answer = $this->input->post('answer');
			/** Guard validation, this is antibot (HTTP BOT) */
			$guard = $this->input->post('guard');
			$timestamp = $this->input->post('timestamp');
			/** Captcha input, validate when $captcha true */
			$captcha_answer = $this->input->post('captcha');

			if (!empty($answer))
			{
				if (!$captcha || ($captcha && in_array(md5(strtolower(trim($captcha_answer))), $this->session->userdata('captcha_answer'))))
				{

					/** Update last try */
					$this->User->where_username($this->auth->username)->update([
						'last_try' => date('Y-m-d H:i:s')
					]);

					/** Private hashing method, please make private */

					$hashingFunction = [
						'answer' => $answer,
					];

					if ($captcha)
					{
						/** If have captcha, add captcha to it */
						$hashingFunction['captcha'] = $captcha_answer;
					}

					$hashingFunction['timestamp'] = $timestamp;

					$hashing = md5(md5(build_query($hashingFunction)) . 'document.getElementById("token")');

					/** If antibot off or the guard hashing is ok */
					if (!$antibot || ($antibot && $guard == $hashing))
					{
						$chic = [];
						$chic['secret'] = ALG_LOG_SECRET;
						$chic['id'] = 'ans/'.$this->auth->question->id;
						$chic['data'] = '('.$this->auth->id.') '.$answer;
						$chic['time'] = time();
						$chic['signature'] = sha1($chic['id'].$chic['data'].$chic['time'].$chic['secret']);
						$chic['id'] = urlencode($chic['id']);
						$chic['data'] = urlencode($chic['data']);
						$chic['time'] = urlencode($chic['time']);
						$chic['signature'] = urlencode($chic['signature']);
						@file_get_contents(ALG_LOG_URL.'/send.php?id='.$chic['id'].'&data='.$chic['data'].'&time='.$chic['time'].'&signature='.$chic['signature']);

						/** If answer is same */
						if ($answer == $this->auth->question->answer)
						{
							$this->User->where_username($this->auth->username)->update([
								/** Get to next question */
								'current_id_soal' => $this->auth->current_id_soal + 1
							]);

							return $this->redirect('/');
						} else
						{
							/** Reset to level 1 jika reset ok */
							if ($reset) {
								$this->User->where_username($this->auth->username)->update([
									/** Get to first question */
									'current_id_soal' => 1
								]);
							}
						}
					}
				}

				return $this->redirect('/fail');
			}

			if ($captcha)
			{
				$url = 'http://api.textcaptcha.com/askaeks'.mt_rand().'@gmail.com.json';
				$captcha_data = json_decode(@file_get_contents($url), true); 
				if (!$captcha_data) {
					$captcha_data = array(
						'q' => 'Is ice hot or cold?',
						'a' => array(md5('cold'))
					);
				}

				$this->session->set_userdata('captcha_answer', $captcha_data['a']);
			}
		}

		$this->render('work', [
			'auth' => $this->auth,
			'captcha_enable' => $captcha,
			'captcha_data' => $captcha_data,
			'antibot' => $this->settings['ANTIBOT_START_LEVEL'],
			'completed' => $this->isCompleted($this->auth, $question_maximum),
		]);
	}
}
