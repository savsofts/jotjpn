<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct()
	 {
	   parent::__construct();
	    $this->load->model("login_model");
	 }

	public function index()
	{
		if($this->session->userdata('logged_in')){
			redirect('user/dashboard');
		}
		$this->load->view('login');
	}
	public function register($pack=1)
	{
		$this->session->set_userdata('pack',$pack);
		$this->load->view('register');
	}
	public function forgot()
	{
		$this->load->view('forgot');
	}
	public function admin()
	{
		$this->load->view('admin/login_admin');
	}
	
	public function insertUser(){
		
		$email=$this->input->post('email');
		$password=$this->input->post('password');
		$result=$this->login_model->register($email,$password);
		if($result == true){
			$result=$this->login_model->verify($email,$password);
			if($result == false){
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>Invalid username or password</div>");
				redirect('login/');
			}else{
				$this->session->set_userdata('logged_in',$result);
				redirect('user/dashboard');
			}
		}else{
			$this->session->set_flashdata('message',"<div class='alert alert-danger'>".$result."</div>");
			redirect('login/register');
		}		
	}
	
	public function verifyCode(){
		$code=trim($this->input->post('code'));
		$logged_in=$this->session->userdata('logged_in');
		$email=$logged_in['email'];	
		$result=$this->login_model->verifyCode($email,$code);
		if($result == false){
			$this->session->set_flashdata('message',"<div class='alert alert-danger'>Invalid Code</div>");
			redirect('user/dashboard');
		}else{
			redirect('user/dashboard');
		}
		
	}
	public function resend(){
		$loggedin=$this->session->userdata('logged_in');
		$email=$loggedin['email'];	
		 
		$result=$this->login_model->resend($email);
		$this->session->set_flashdata('message',"<div class='alert alert-danger'>Code sent!</div>");
		redirect('user/dashboard');
		
	}
	
	public function verify(){
		$email=$this->input->post('email');
		$password=$this->input->post('password');
		$result=$this->login_model->verify($email,$password);
		if($result == false){
			$this->session->set_flashdata('message',"<div class='alert alert-danger'>Invalid username or password</div>");
			redirect('login/');
		}else{
			$this->session->set_userdata('logged_in',$result);
			redirect('user/dashboard');
		}
		
	}
	
	public function verifyAdmin(){
		$username=$this->input->post('username');
		$password=$this->input->post('password');
		$result=$this->login_model->verifyAdmin($username,$password);
		if($result == false){
			$this->session->set_flashdata('message',"<div class='alert alert-danger'>Invalid username or password</div>");
			redirect('login/admin');
		}else{
			$this->session->set_userdata('logged_in_admin',$result);
			redirect('admin/dashboard');
		}
		
	}
	public function reset(){
		$email=$this->input->post('email');
		$result=$this->login_model->reset($email);
		if($result == true){
			$this->session->set_flashdata('message',"<div class='alert alert-danger'>New password sent in email</div>");
			redirect('login');
		}else{
			$this->session->set_flashdata('message',"<div class='alert alert-danger'>".$result."</div>");
			redirect('login');
		}
		
	}
	
	
	public function logout(){
		$this->session->unset_userdata('logged_in');
		$this->session->set_flashdata('message',"<div class='alert alert-success'>Logged out successfully</div>");
			redirect('login');
	}
	public function logout_admin(){
		$this->session->unset_userdata('logged_in_admin');
		$this->session->set_flashdata('message',"<div class='alert alert-success'>Logged out from admin session</div>");
			redirect('login/admin');
	}
}
