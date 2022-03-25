<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
 
    function __construct()
	 {
	   parent::__construct();
	    $this->load->model("user_model");
		$this->load->library('grocery_CRUD');
		if(!$this->session->userdata('logged_in')){
			$this->session->set_flashdata('message',"<div class='alert alert-danger'>Session expired</div>");
			redirect('login/index');
		}
		
	 }

	 
	
	public function index()
	{
		$this->_g_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}	
	
	public function dashboard($status='')
	{
		$logged_in=$this->session->userdata('logged_in');
		
		$client_id=$logged_in['client_id'];
		// check assigned server
		$assignedServers=$this->user_model->getAssignedSerers($client_id);
		if(count($assignedServers) == 0){
			$data['pricePopup']=1;
		}else{
			$data['pricePopup']=0;
		}
		 $data['status']=$status;
		$data['total_servers']=$this->user_model->total_servers($client_id);
		$data['account_balance']=$this->user_model->account_balance($client_id);
		$data['sshkeys']=$this->user_model->sshkeys($client_id);
		$query=$this->db->query("select email_verification from jotjpn_clients where client_id='$client_id' ");
		$row=$query->row_array();
		if($row['email_verification']=="Verified"){
		$this->load->view('header');
		$this->load->view('dashboard',$data);
		$this->load->view('footer');
		}else{
		$this->load->view('verify');	
		}
	}
	
	
	public function servers()
	{
		$logged_in=$this->session->userdata('logged_in');
		
		$client_id=$logged_in['client_id'];
		
		$data['servers']=$this->user_model->getAssignedSerers($client_id);
		$data['title']="Servers (".count($data['servers']).")";
		$this->load->view('header');
		$this->load->view('servers.php',$data);
		$this->load->view('footer');

	}

	public function addFund()
	{
		$logged_in=$this->session->userdata('logged_in');
		
		$client_id=$logged_in['client_id'];
		
		$data['title']="Add fund";
		$this->load->view('header',$data);
		$this->load->view('addFund.php',$data);
		$this->load->view('footer');

	}

	public function server_detail($server_identifier='')
	{
		$data['server_identifier']=$server_identifier;
		$logged_in=$this->session->userdata('logged_in');
		
		$client_id=$logged_in['client_id'];
		
		$data['server']=$this->user_model->getAssignedSerer($client_id,$server_identifier);
		 $data['do_api_key']=$this->user_model->getDoApiKey();
		 $data['title']=$data['server']['server_name'];
		$this->load->view('header');
		$this->load->view('server.php',$data);
		$this->load->view('footer');

	}

	
	
	public function create()
	{
		$logged_in=$this->session->userdata('logged_in');
		
		$client_id=$logged_in['client_id'];
		
		$data['server_type']=$this->user_model->getServerType();
		$data['region']=$this->user_model->getRegion();
		$data['sshkey']=$this->user_model->getSshkey($client_id);
		foreach($data['server_type'] as $k => $st){ 
		$data['server_type'][$k]['servers']=$this->user_model->getServers($st['server_type_id']);		
		}
		$data['title']="Create New Server";
		$this->load->view('header');
		$this->load->view('create.php',$data);
		$this->load->view('footer');

	}

	
	public function sshkey()
	{
		$logged_in=$this->session->userdata('logged_in');
		$client_id=$logged_in['client_id'];
		
		try{
			$crud = new grocery_CRUD();
			$loggedin=$this->session->userdata('logged_in');
			$crud->set_theme('sbadmin2');
			$crud->set_table('jotjpn_ssh_key');
			 $crud->set_subject('SSH Key');
			 
			 $crud->unset_clone();
			 $crud->unset_edit();
			$crud->display_as('ssh_key_id','ID');
			 $crud->display_as('ssh_key_name','Key Name (Any)');
			 $crud->display_as('public_key','Public Key');
			 $crud->where('jotjpn_ssh_key.client_id',$loggedin['client_id']);
			 $crud->columns('ssh_key_id','ssh_key_name');
			 $crud->add_fields('ssh_key_name','public_key');
			$crud->edit_fields('ssh_key_name','public_key');
			$crud->callback_after_insert(array($this,'api_ssh_key'));
			  
			$output = $crud->render();

			$this->ssh_view($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function ssh_view($output = null)
	{
		$this->load->view('header');
		$this->load->view('sshkey.php',(array)$output);
		$this->load->view('footer');

	}

	function api_ssh_key($post_array, $primary_key = null)
	{
	 $do_api_key=$this->user_model->getDoApiKey();
		
	 $ssh_key_name=$post_array['ssh_key_name'];
	 $public_key=$post_array['public_key'];


			$url=$this->config->item('do_api_url').'account/keys';
			$token = $do_api_key;
			//setup the request, you can also use CURLOPT_URL
			$ch = curl_init($url);

			// Returns the data/output as a string instead of raw data
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,'{
			"public_key": "'.$public_key.'",
			"name": "'.$ssh_key_name.'"
			}');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//Set your auth headers
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			   'Content-Type: application/json',
			   'Authorization: Bearer ' . $token
			   ));

			// get stringified data/output. See CURLOPT_RETURNTRANSFER
			$data = curl_exec($ch);
			$d=json_decode(trim($data)); 
			
			// get info about the request
			$info = curl_getinfo($ch);
			 
			// close curl resource to free up system resources
			if(isset($d->ssh_key->id)){ 
			$ssh_key_id=$d->ssh_key->id;
			$fingerprint=$d->ssh_key->fingerprint;
			$logged_in=$this->session->userdata('logged_in');
			$client_id=$logged_in['client_id'];
		
			 $this->db->query(" update jotjpn_ssh_key set ssh_key_id='$ssh_key_id', fingerprint='$fingerprint', client_id='$client_id' where id='$primary_key' ");	 
			}  
			curl_close($ch);
			 

			
	return true;
	}
	
	
	
	public function api_create(){
		$sizez=explode('::',$_POST['size']);
		$size=$sizez[0];
		$server_id=$sizez[1];
		$nm=$_POST['name'];
		$nm=str_replace(' ','-',$_POST['name']);
		$json='{"name":"'.$nm.'","region":"'.$_POST['region'].'","size":"'.$size.'","image":"'.$_POST['image'].'","ssh_keys":['.$_POST['ssh_keys'].'],"monitoring":true}';
		$do_api_key=$this->user_model->getDoApiKey();
		$server=$this->user_model->getServer($server_id);
	 


			$url=$this->config->item('do_api_url').'droplets';
			$token = $do_api_key;
			//setup the request, you can also use CURLOPT_URL
			$ch = curl_init($url);

			// Returns the data/output as a string instead of raw data
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//Set your auth headers
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			   'Content-Type: application/json',
			   'Authorization: Bearer ' . $token
			   ));

			// get stringified data/output. See CURLOPT_RETURNTRANSFER
			$data = curl_exec($ch);
			$d=json_decode(trim($data)); 
			
			// get info about the request
			$info = curl_getinfo($ch);
			 
			 curl_close($ch);
			
			// close curl resource to free up system resources
			if(isset($d->droplet->id)){ 
			$logged_in=$this->session->userdata('logged_in');
			
			$userdata=array(
			'server_identifier'=>$d->droplet->id,
			'client_id'=>$logged_in['client_id'],
			'server_id'=>$server_id,
			'server_name'=>$nm,
			'price_per_month'=>$server['price_per_month'],
			'price_per_hour'=>$server['price_per_hour'],
			'vcpu'=>$server['vcpu'],
			'ram_gb'=>$server['ram_gb'],
			'space_gb'=>$server['space_gb'],
			'data_transfer_tb'=>$server['data_transfer_tb'],
			'billed'=>0,
			'assigned_server_status'=>'Processing'
			
			);
			 if($this->db->insert('jotjpn_assigned_servers',$userdata)){
				$this->session->set_flashdata('message',"<div class='alert alert-success'>Server created successfuly!</div>");
				redirect('user/servers');
			 }else{
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>Unable to create server.</div>");
				redirect('user/create');
			 }				 
			}else{
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>".$d->message."</div>");
				redirect('user/create');
			}				
					
	}
	
	public function reboot_server($server_identifier){
		if($this->validateServerOwnership($server_identifier)==false){
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>This server is not assigned to your account!</div>");
				redirect('user/server_detail/'.$server_identifier);
		}
		
		$json='{"type":"reboot"}';
		$do_api_key=$this->user_model->getDoApiKey();
		 


			$url=$this->config->item('do_api_url').'droplets/'.$server_identifier.'/actions';
			$token = $do_api_key;
			//setup the request, you can also use CURLOPT_URL
			$ch = curl_init($url);

			// Returns the data/output as a string instead of raw data
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//Set your auth headers
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			   'Content-Type: application/json',
			   'Authorization: Bearer ' . $token
			   ));

			// get stringified data/output. See CURLOPT_RETURNTRANSFER
			$data = curl_exec($ch);
			$d=json_decode(trim($data)); 
			
			// get info about the request
			$info = curl_getinfo($ch);
			 
			 curl_close($ch);
			 if(isset($d->action->status)){ 
				$this->session->set_flashdata('message',"<div class='alert alert-success'>Server rebooted successfuly!</div>");
				redirect('user/server_detail/'.$server_identifier);
			 }else{
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>Unable to process reboot request. Try again.</div>");
				redirect('user/server_detail/'.$server_identifier);
			 }
		
	}
	



	public function poweroff_server($server_identifier){
		if($this->validateServerOwnership($server_identifier)==false){
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>This server is not assigned to your account!</div>");
				redirect('user/server_detail/'.$server_identifier);
		}		
		$json='{"type":"power_off"}';
		$do_api_key=$this->user_model->getDoApiKey();
		 


			$url=$this->config->item('do_api_url').'droplets/'.$server_identifier.'/actions';
			$token = $do_api_key;
			//setup the request, you can also use CURLOPT_URL
			$ch = curl_init($url);

			// Returns the data/output as a string instead of raw data
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//Set your auth headers
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			   'Content-Type: application/json',
			   'Authorization: Bearer ' . $token
			   ));

			// get stringified data/output. See CURLOPT_RETURNTRANSFER
			$data = curl_exec($ch);
			$d=json_decode(trim($data)); 
			
			// get info about the request
			$info = curl_getinfo($ch);
			 
			 curl_close($ch);
			 if(isset($d->action->status)){ 
				$this->session->set_flashdata('message',"<div class='alert alert-success'>Server shutdown successfuly!</div>");
				redirect('user/server_detail/'.$server_identifier);
			 }else{
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>Unable to process  request. Try again.</div>");
				redirect('user/server_detail/'.$server_identifier);
			 }
		
	}



	public function poweron_server($server_identifier){
		if($this->validateServerOwnership($server_identifier)==false){
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>This server is not assigned to your account!</div>");
				redirect('user/server_detail/'.$server_identifier);
		}		
		$json='{"type":"power_on"}';
		$do_api_key=$this->user_model->getDoApiKey();
		 


			$url=$this->config->item('do_api_url').'droplets/'.$server_identifier.'/actions';
			$token = $do_api_key;
			//setup the request, you can also use CURLOPT_URL
			$ch = curl_init($url);

			// Returns the data/output as a string instead of raw data
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//Set your auth headers
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			   'Content-Type: application/json',
			   'Authorization: Bearer ' . $token
			   ));

			// get stringified data/output. See CURLOPT_RETURNTRANSFER
			$data = curl_exec($ch);
			$d=json_decode(trim($data)); 
			
			// get info about the request
			$info = curl_getinfo($ch);
			 
			 curl_close($ch);
			 if(isset($d->action->status)){ 
				$this->session->set_flashdata('message',"<div class='alert alert-success'>Server power on successfuly!</div>");
				redirect('user/server_detail/'.$server_identifier);
			 }else{
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>Unable to process request. Try again.</div>");
				redirect('user/server_detail/'.$server_identifier);
			 }
		
	}
	
	
	
	public function distroy_server($server_identifier){
		
		if($this->validateServerOwnership($server_identifier)==false){
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>This server is not assigned to your account!</div>");
				redirect('user/server_detail/'.$server_identifier);
		}		 
		$do_api_key=$this->user_model->getDoApiKey();
		 

		if($this->config->item('allow_distroy_server') == true){
			$url=$this->config->item('do_api_url').'droplets/'.$server_identifier;
			$token = $do_api_key;
			//setup the request, you can also use CURLOPT_URL
			$ch = curl_init($url);

			 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//Set your auth headers
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			   'Content-Type: application/json',
			   'Authorization: Bearer ' . $token
			   ));

			// get stringified data/output. See CURLOPT_RETURNTRANSFER
			$data = curl_exec($ch);
			$d=json_decode(trim($data)); 
			
			// get info about the request
			$info = curl_getinfo($ch);
			 
			 curl_close($ch);
			 $this->user_model->inactivateServer($server_identifier);
			 if(isset($d->action->status)){ 
			  $this->db->query(" update jotjpn_assigned_servers set assigned_server_status='Inactive' where server_identifier='$server_identifier' ");	 
			
				$this->session->set_flashdata('message',"<div class='alert alert-success'>Server removed successfuly!</div>");
				redirect('user/servers/'.$server_identifier);
			 }else{
				//$this->session->set_flashdata('message',"<div class='alert alert-danger'>Unable to process request. Try again.</div>");
				// redirect('user/server_detail/'.$server_identifier);
				$this->session->set_flashdata('message',"<div class='alert alert-success'>Server removed successfuly!</div>");
				redirect('user/servers/'.$server_identifier);
				
			 }
		}else{
			$this->session->set_flashdata('message',"<div class='alert alert-danger'>Distroy server is disabled by admin</div>");
				redirect('user/server_detail/'.$server_identifier);
		}
		
	}

	public function transactions()
	{
		try{
			$crud = new grocery_CRUD();
			$loggedin=$this->session->userdata('logged_in');
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
			 $crud->where('jotjpn_transaction.client_id',$loggedin['client_id']);
			 $crud->columns('amount','t_datetime','t_status');
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
		$this->load->view('header');
		$this->load->view('transactions.php',(array)$output);
		$this->load->view('footer');

	}





	public function profile()
	{
		try{
			$crud = new grocery_CRUD();
			$loggedin=$this->session->userdata('logged_in');
			
			$crud->set_theme('sbadmin2');
			$crud->set_table('jotjpn_clients');
			 $crud->set_subject('Profile');
			// $crud->required_fields('region_name,region_status');
			// $crud->columns('region_name','region_status');
			 $crud->unset_clone();
			 $crud->unset_add();
			 $crud->unset_list();
			  $crud->unset_delete();
			 $crud->display_as('full_name','Name');
			 $crud->display_as('contact_number','Contact Number');
			 $crud->display_as('account_balance','Balance');
			 $crud->display_as('account_status','Status');
			 $crud->edit_fields('full_name','password','contact_number','address','city','state','country','pin_code','company_name','tax_number');
			 $crud->change_field_type('password','password');
			 $crud->columns('full_name','account_balance','account_status');
			 $crud->callback_before_insert(array($this,'encrypt_password_callback'));
			 $crud->callback_before_update(array($this,'encrypt_password_callback'));
			 $crud->callback_edit_field('password',array($this,'decrypt_password_callback'));
			 $crud->where('jotjpn_clients.client_id',$loggedin['client_id']);
			
			 $output = $crud->render();

			$this->profile_view($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function profile_view($output = null)
	{
		$this->load->view('header');
		$this->load->view('profile.php',(array)$output);
		$this->load->view('footer');

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
	
	
	
	function validateServerOwnership($server_identifier){
		$logged_in=$this->session->userdata('logged_in');
		$client_id=$logged_in['client_id'];
		if($this->user_model->validateServerOwnership($server_identifier,$client_id)){
			return true;
		}else{
			return false;
		}
		
	}

      function create_backup(){
		$logged_in=$this->session->userdata('logged_in');
		$client_id=$logged_in['client_id'];

		        $userdata=array(
			'unique_id'=>time(),
			'name'=>$this->input->post('name'),
			'server_id'=>$this->input->post('server_id'),
			'date'=>date('d-m-Y h:i:sa',time())
			);
			if($this->db->insert('jotjpn_backup',$userdata)){
			
				$this->session->set_flashdata('message',"<div class='alert alert-success'>Create Backup successfuly!</div>");
				redirect('user/backups');
			 }else{
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>Unable to Create Backup.</div>");
				redirect('user/backups');
			 }				 
			
     }
	
	public function backups()
	{
		try{
			$crud = new grocery_CRUD();

			 $crud->set_theme('sbadmin2');
			 $crud->set_table('jotjpn_backup');
			 $crud->set_subject('Backups');
			 $crud->unset_clone();
			 $crud->unset_add();
			 $crud->unset_edit();
			 $crud->display_as('unique_id','Id');
			 $crud->display_as('name','Name');
			 $crud->display_as('server_id','Server Id');
			 $crud->display_as('date','Date');
			 $crud->display_as('status','Status');
			 $crud->edit_fields('name');
			 $crud->columns('name','date','status');
			 $output = $crud->render();

			 $this->backup_list($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function backup_list($output = null){
	{
		$this->load->view('header');
		$this->load->view('backups.php',(array)$output);
		$this->load->view('footer');

	}	

	}
	


}
