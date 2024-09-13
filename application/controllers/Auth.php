<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct(){
		parent::__construct();

        // if($this->session->userdata('cr_unique') !== 'CYLSUPERCODE14RACK') {
        //     $this->session->set_flashdata('notif2', 'Please Login First.');
        //     redirect(base_url());
        // }

        $this->load->model('Authmodel', 'auth');

        date_default_timezone_set('Asia/Jakarta');
    }

	public function index()
	{
		$this->load->view('login');
	}

	function loginCheck(){
		if($_POST){
			$email 	= $this->input->post('in_email');
			$pwd 	= md5($this->input->post('in_password'));

			$key = array(
				'email' 	=> $email,
				'password'	=> $pwd
			);

			$getUser = $this->auth->checkUser('users', $key)->row();

			if(count($getUser) > 0){
				if($getUser->status == '0'){
					$this->session->set_flashdata('notif', 'Akun tidak aktif');
					redirect(base_url());
				}else{
					$userdata   = array(
						'ses_userid' 	=> $getUser->id,
						'ses_email'     => $getUser->email,
						'ses_username'  => $getUser->username,
						'ses_role'   	=> $getUser->role
					);

					$this->session->set_userdata($userdata);

					redirect(base_url('dashboard'));
				}
			}else{
				$this->session->set_flashdata('notif', "Email dan password tidak sesuai");
				redirect(base_url());
			}
		}
	}

	public function logout(){
		$item   = array('ses_userid', 'ses_email', 'ses_username', 'ses_role');
		$this->session->unset_userdata($item);

		redirect(base_url());
	}
}

?>
