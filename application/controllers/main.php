<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('main_model');
		$this->load->library('session');
		$this->load->library('form_validation');
	}

	public function index(){

		// $this->login();
		// $this->register();

		$this->load->view("home");

	}

	public function login(){
		$this->load->view("login_view");
	}

	public function login_validation(){

		$this->form_validation->set_rules('user_email', 'Email', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'required');
		
		if($this->form_validation->run()){
			//True
			$user_email = $_POST['user_email'];
			$user_password = $_POST['user_password'];

			//model function
			$this->load->model('main_model');
			if($this->main_model->can_login($user_email, $user_password)){

				$user_data = $this->main_model->can_login($user_email, $user_password);
				foreach($user_data->result() as $row){

					$_SESSION['id'] = $row->id;
					$_SESSION['first_name'] = $row->first_name;
					$_SESSION['last_name'] = $row->last_name;
					$_SESSION['email'] = $row->email;
					$_SESSION['priveledge'] = $row->priveledge;

				}

				if($_SESSION['priveledge'] == "consti"){

					redirect(base_url() . 'main/enter');
				}
				else{
					redirect(base_url() . 'main/admin');
				}
			}
			else{
				
				$_SESSION['error'] = 'Invalid Email or Password';
				redirect(base_url() . 'main/login');
			}
		}
		else{
			//false
			$this->login();
		}
	}

	public function enter(){

		// if($_SESSION['id'] != ''){
			
		//     echo '<h2>Welcome ' . $_SESSION['last_name'] . '</h2>';
		//     echo '<a href="' .base_url(). 'main/logout">Logout</a>';
		// }
		// else{

		//     redirect(base_url());
		// }

		$this->load->view("enter_home");
	}

	public function complaint(){

		$this->load->view("complaint");
	}

	public function create_complaint(){

		$this->form_validation->set_rules('complaint', 'Complaint', 'required');

		if($this->form_validation->run()){

			$complaint = $_POST['complaint'];
			$consti_id = $_SESSION['id'];
			$first_name = $_SESSION['first_name'];
			$last_name = $_SESSION['last_name'];
			$email = $_SESSION['email'];

			$data = array(
				"consti_id"			=>$consti_id,
				"complaints"		=>$complaint,
				"first_name"		=>$first_name,
				"last_name"			=>$last_name,
				"email"					=>$email
			);

			$this->load->model('main_model');
			if($this->main_model->enter_complaint($data)){

				$_SESSION['success'] = 'Complaint sent successfully';
				$this->load->view("complaint");
			}

		}
		else{
			$_SESSION['error'] = 'Please type your Complaint';
			$this->load->view("complaint");
		}
	}

	public function admin(){
		$this->load->view("admin");
	}

	public function admin_complaint(){
		$data["fetch_data"] = $this->main_model->fetch_data_complaint();
		$this->load->view("admin_complaint", $data);
	}


	public function logout(){

		unset($_SESSION['id']);
		unset($_SESSION['first_name']);
		unset($_SESSION['last_name']);
		unset($_SESSION['email']);
		redirect(base_url() . 'main/login');
	}

	public function register(){

		$this->load->view("register_view");
	}

	public function register_validation(){

		$this->form_validation->set_rules('first_name', 'Firstname', 'required');
		$this->form_validation->set_rules('middle_name', 'Middlename', 'required');
		$this->form_validation->set_rules('last_name', 'Lastname', 'required');
		$this->form_validation->set_rules('user_email', 'Email Address', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'required');
		$this->form_validation->set_rules('user_address', 'Address', 'required');
		$this->form_validation->set_rules('user_age', 'Age', 'required');
		$this->form_validation->set_rules('user_sex', 'Sex', 'required');
		$this->form_validation->set_rules('user_birthday', 'Birthday', 'required');
		$this->form_validation->set_rules('user_civil_status', 'Civil Status', 'required');
		$this->form_validation->set_rules('user_number', 'Cellphone Number', 'required');

		if($this->form_validation->run()){
			//True
			$first_name = $_POST['first_name'];
			$middle_name = $_POST['middle_name'];
			$last_name = $_POST['last_name'];
			$user_email = $_POST['user_email'];
			$user_password = $_POST['user_password'];
			$user_address = $_POST['user_address'];
			$user_age = $_POST['user_age'];
			$user_sex = $_POST['user_sex'];
			$user_birthday = $_POST['user_birthday'];
			$user_civil_status = $_POST['user_civil_status'];
			$user_number = $_POST['user_number'];
			$priveledge = "consti";

			$data = array(
				"first_name"            =>$first_name,
				"middle_name"           =>$middle_name,
				"last_name"             =>$last_name,
				"email"            =>$user_email,
				"password"         =>$user_password,
				"address"          =>$user_address,
				"age"              =>$user_age,
				"sex"              =>$user_sex,
				"birthday"         =>$user_birthday,
				"civil_status"     =>$user_civil_status,
				"number"           =>$user_number,
				"priveledge"				=>$priveledge
			);
			$this->load->model('main_model');
			if($this->main_model->register($data)){

				$_SESSION['success'] = 'Successfully Registered';
				redirect(base_url() . 'main/register');
			}
			else{

				$_SESSION['error'] = 'Email already registered';
				redirect(base_url() . 'main/register');
			}
		}
		else{
			//False
			$this->register();
		}
	}


}