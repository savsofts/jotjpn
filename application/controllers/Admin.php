<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct()
	 {
	   parent::__construct();
	    $this->load->model("admin_model");
	    $this->load->model("support_model");
		$this->load->model("login_model");
	        $this->load->library('pagination');
		$this->load->library('grocery_CRUD');
		if(!$this->session->userdata('logged_in_admin')){
			$this->session->set_flashdata('message',"<div class='alert alert-danger'>Session expired</div>");
			redirect('login/admin');
		}
	 }


	public function _g_output($output = null)
	{
		$this->load->view('admin/header');
		$this->load->view('admin/server_type.php',(array)$output);
		$this->load->view('admin/footer');

	}
	public function index()
	{
		$this->_g_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}	
	
	public function dashboard()
	{
		 $data['do_api_key']=$this->admin_model->getDoApiKey();
		$data['monthly_earning']=$this->admin_model->monthlyEarning();
		$data['total_servers']=$this->admin_model->total_servers();
		$data['total_clients']=$this->admin_model->total_clients();
		
		$this->load->view('admin/header');
		$this->load->view('admin/dashboard',$data);
		$this->load->view('admin/footer');
	}
	
	
	public function login_as_client($client_id){
		 $client=$this->admin_model->get_client($client_id);
		 $this->session->set_userdata('logged_in',$client);
				redirect('user/dashboard');
		
	}
	

	public function server_type()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('sbadmin2');
			$crud->set_table('jotjpn_server_type');
			 $crud->set_subject('Server types');
			$crud->required_fields('type_name');
			$crud->columns('type_name','description');
			// $crud->unset_bootstrap();
			 $crud->unset_clone();
 			 $output = $crud->render();

			$this->_g_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function region()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('sbadmin2');
			$crud->set_table('jotjpn_region');
			 $crud->set_subject('Region');
			$crud->required_fields('region_name,region_status');
			$crud->columns('region_name','region_status');
			//$crud->unset_jquery();
			 $crud->unset_clone();
 			
			$output = $crud->render();

			$this->region_view($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function region_view($output = null)
	{
		$this->load->view('admin/header');
		$this->load->view('admin/region.php',(array)$output);
		$this->load->view('admin/footer');

	}



	public function servers()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('sbadmin2');
			$crud->set_table('jotjpn_servers');
			 $crud->set_subject('Servers');
			// $crud->required_fields('region_name,region_status');
			// $crud->columns('region_name','region_status');
			 $crud->unset_clone();
			 $crud->display_as('server_type_id','Server type');
			 $crud->display_as('vcpu','CPU Cores');
			 $crud->display_as('ram_gb','RAM  in GB');
			 $crud->display_as('space_gb','Disk Space  in GB');
			 $crud->display_as('data_transfer_tb','Data Transfer Monthly in TB');
			 $crud->columns('server_type_id','server_name','vcpu','ram_gb','price_month');
			 $crud->set_relation('server_type_id','jotjpn_server_type','type_name');
 			 
			$output = $crud->render();

			$this->server_view($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function server_view($output = null)
	{
		$this->load->view('admin/header');
		$this->load->view('admin/servers.php',(array)$output);
		$this->load->view('admin/footer');

	}	





	public function settings()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('sbadmin2');
			$crud->set_table('jotjpn_settings');
			 $crud->set_subject('Settings');
			// $crud->required_fields('region_name,region_status');
			// $crud->columns('region_name','region_status');
			 $crud->unset_clone();
			 $crud->display_as('paypal_receiver_id','Paypal Receiver ID');
			 $crud->display_as('digitalocean_api_key','Digitalocean API key');
			 $crud->display_as('razorpay_id','Razorpay ID');
			 $crud->unset_list();
  
			$output = $crud->render();

			$this->settings_view($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function settings_view($output = null)
	{
		$this->load->view('admin/header');
		$this->load->view('admin/settings.php',(array)$output);
		$this->load->view('admin/footer');

	}	






	public function transactions()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('sbadmin2');
			$crud->set_table('jotjpn_transaction');
			 $crud->set_subject('Transactions');
			// $crud->required_fields('region_name,region_status');
			// $crud->columns('region_name','region_status');
			 $crud->unset_clone();
			 $crud->unset_add();
			 $crud->unset_edit();
			 $crud->unset_delete();
			$crud->display_as('client_id','Client ID');
			 $crud->display_as('amount','Amount');
			 $crud->display_as('t_datetime','Transaction time');
			 $crud->display_as('t_id','Transaction ID');
			 $crud->display_as('t_mode','Payment Method');
			 $crud->display_as('t_status','Status');
			 $crud->columns('client_id','amount','t_datetime','t_status');
			  // $crud->fields('client_id','amount','t_datetime');
			  $crud->set_relation('client_id','jotjpn_clients','email');
 			
			$output = $crud->render();

			$this->transactions_view($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function transactions_view($output = null)
	{
		$this->load->view('admin/header');
		$this->load->view('admin/transactions.php',(array)$output);
		$this->load->view('admin/footer');

	}	






	public function clients()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('sbadmin2');
			$crud->set_table('jotjpn_clients');
			 $crud->set_subject('Clients');
			// $crud->required_fields('region_name,region_status');
			// $crud->columns('region_name','region_status');
			 $crud->unset_clone();
			 $crud->display_as('email','Email');
			 $crud->display_as('full_name','Name');
			 $crud->display_as('contact_number','Contact Number');
			 $crud->display_as('account_balance','Balance');
			 $crud->display_as('account_status','Status');
			 $crud->add_fields('email','password','full_name','contact_number','address','city','state','country','pin_code','company_name','tax_number','account_balance','account_status');
			 $crud->edit_fields('email','password','full_name','contact_number','address','city','state','country','pin_code','company_name','tax_number','account_balance','account_status');
			 $crud->change_field_type('password','password');
			 $crud->columns('email','account_balance','account_status');
			 $crud->callback_before_insert(array($this,'encrypt_password_callback'));
			 $crud->callback_before_update(array($this,'encrypt_password_callback'));
			 $crud->callback_edit_field('password',array($this,'decrypt_password_callback'));
			  $crud->add_action('Login as client', '', 'admin/login_as_client','btn btn-success');
			 $output = $crud->render();

			$this->clients_view($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function clients_view($output = null)
	{
		$this->load->view('admin/header');
		$this->load->view('admin/clients.php',(array)$output);
		$this->load->view('admin/footer');

	}	
	
	
	
	
	function encrypt_password_callback($post_array, $primary_key = null)
	{
	 
	 
	$post_array['password'] = base64_encode($post_array['password']);
	return $post_array;
	}
	
	
	function decrypt_password_callback($value)
	{
	 
	$decrypted_password = base64_decode($value);
	return "<input type='password' class='form-control' name='password' value='$decrypted_password' />";
	}


	public function support($ticket_status='0',$limit='0')
	{

		$rowperpage=$this->config->item('rowperpage');
		
		// Row position
		if($limit != 0){
			$limit = ($limit-1) * $rowperpage;
		}

      		$allcount = $this->support_model->getrecordCountAll();
		$supportdata=$this->support_model->support_list_all($ticket_status,$limit,$rowperpage);

		// Pagination Configuration
      		$config['base_url'] = base_url().'index.php/admin/support/'.$ticket_status='0';
      		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $allcount;
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['support'] = $supportdata;
		$data['row'] = $limit;

		$data['title']="Support Tickets";
		$this->load->view('admin/header');
		$this->load->view('admin/support.php',$data);
		$this->load->view('admin/footer');
	}

	public function view($id)
	{
		$logged_in_admin=$this->session->userdata('logged_in_admin');
		$admin_id=$logged_in_admin['id'];

		$data['id']=$id;
		$data['result']=$this->support_model->get_data($id);
		$data['result1']=$this->support_model->ticket_data($id);
$this->db->query("update jotjpn_message set msg_read='1' where msg_by !='$admin_id' and msg_read ='0' and ticket_id='$id'");
		$this->load->view('admin/header');
		$this->load->view('admin/view.php',$data);
		$this->load->view('admin/footer');

	}
	public function update_ticket($id)
	{
			$logged_in_admin=$this->session->userdata('logged_in_admin');
			$admin_id=$logged_in_admin['id'];
			$full_name=$logged_in_admin['username'];

			$last_msg=strtotime(date('d-m-Y h:i:sa',time()));
			$ticket_status=$_POST['ticket_status'];
			
			$query=$this->db->query("select jotjpn_tickets.*,jotjpn_clients.email from jotjpn_tickets join jotjpn_clients on jotjpn_clients.client_id=jotjpn_tickets.client_id where jotjpn_tickets.id='$id'");
			$userData=$query->row_array();
			$email=$userData['email'];

		        if($this->db->query("update jotjpn_tickets set last_msg='$last_msg',ticket_status='$ticket_status' where id='$id'")){
			$userdataa=array(
			'ticket_id'=>$id,
			'msg_by'=>$admin_id,
			'msg'=>$this->input->post('msg'),
			'msg_time'=>strtotime(date('d-m-Y h:i:sa',time())),
		 	'screenshot'=>$this->input->post('screenshot')
			);

			if($this->db->insert('jotjpn_message',$userdataa)){
			$query=$this->db->query("select ticket_id from jotjpn_message where ticket_id='$id'");
			$data=$query->num_rows();
			$this->db->query("update jotjpn_tickets set no_msg='$data' where id='$id'");
			require $this->config->item('absolute_serer_path').'class/class.phpmailer.php';
				 // Instantiation and passing `true` enables exceptions
				$mail = new PHPMailer(true);

				try {
				    //Server settings
				    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
				    $mail->isSMTP();                                            // Set mailer to use SMTP
				    $mail->Host       = $this->config->item('smtp_host');  // Specify main and backup SMTP servers
				    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
				    $mail->Username   = $this->config->item('smtp_username');                     // SMTP username
				    $mail->Password   = $this->config->item('smtp_password');                               // SMTP password
				    $mail->SMTPSecure = 'ssl';                   // Enable TLS encryption, `ssl` also accepted
				    $mail->Port       = $this->config->item('smtp_port');                                    // TCP port to connect to

				    //Recipients
				    $mail->setFrom($this->config->item('smtp_username'), $this->config->item('smtp_from_name'));
				    $mail->addAddress($email);     // Add a recipient
				    $mail->addReplyTo($this->config->item('smtp_username'), $this->config->item('smtp_from_name'));
				 
				    // Content
				    $mail->isHTML(true);                                  // Set email format to HTML
    				    $mail->Subject = "New reply added into ticket by ".'Support Team'." - #".$id;
				    $mail->Body    = "Hi, \n\r New reply added into ticket  by ".$full_name.".\n\r To view message login into client portal at ".base_url()." \n\r \n\r ".$this->config->item('app_name')."";
				    $mail->AltBody = "Hi, \n\r New reply added into ticket  by ".$full_name.".\n\r To view message login into client portal at ".base_url()."  \n\r \n\r ".$this->config->item('app_name')."";

				    $mail->send();
				  //echo '<div class="alert alert-success">Message has been sent</div>';
				} catch (Exception $e) {
				 //echo "<div class='alert alert-danger'>Message could not be sent.</div>";
				}
				$this->session->set_flashdata('message',"<div class='alert alert-success'>Updated Data successfuly!</div>");
				redirect('admin/support');
			 }else{
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>Unable to Update Data.</div>");
				redirect('admin/support');
			 }				 
			}

	}



}
