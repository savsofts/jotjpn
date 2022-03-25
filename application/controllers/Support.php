<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends CI_Controller {
 
    function __construct()
	 {
	   parent::__construct();
	    $this->load->model("user_model");
	    $this->load->model("support_model");
	    $this->load->library('pagination');
		$this->load->library('grocery_CRUD');
		if(!$this->session->userdata('logged_in')){
			$this->session->set_flashdata('message',"<div class='alert alert-danger'>Session expired</div>");
			redirect('login/index');
		}
		
	 }

	 
	
	public function index($ticket_status='0',$limit='0')
	{
		$logged_in=$this->session->userdata('logged_in');
		$client_id=$logged_in['client_id'];
		$rowperpage=$this->config->item('rowperpage');
		
		// Row position
		if($limit != 0){
			$limit = ($limit-1) * $rowperpage;
		}

      		$allcount = $this->support_model->getrecordCount();
		$supportdata=$this->support_model->support_list($ticket_status,$client_id,$limit,$rowperpage);

		// Pagination Configuration
      		$config['base_url'] = base_url().'index.php/support/index/'.$ticket_status='0';
      		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $allcount;
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['support'] = $supportdata;
		$data['row'] = $limit;



		$data['title']="Support Tickets";
		$this->load->view('header');
		$this->load->view('support.php',$data);
		$this->load->view('footer');
	}	
	public function create_ticket()
	{
		$logged_in=$this->session->userdata('logged_in');
		$client_id=$logged_in['client_id'];
		
		$data['servers']=$this->user_model->getAssignedSerers($client_id);
		$this->load->view('header');
		$this->load->view('create_ticket.php',$data);
		$this->load->view('footer');
	}	

	public function insert_ticket()
	{
		$resource_type=array();
		if($_POST['resource_id']!='0'){
			$resource_type=$this->input->post('resource_type');
		}else{
			$resource_type='0';
		}
		        $userdata=array(
			'client_id'=>$this->input->post('client_id'),
			'resource_id'=>$this->input->post('resource_id'),
			'resource_type'=>$resource_type,
			'title'=>$this->input->post('title'),
			'ticket_time'=>strtotime(date('d-m-Y h:i:sa',time())),
			);

		if($this->db->insert('jotjpn_tickets',$userdata)){
			$insert_id=$this->db->insert_id();
			$userdataa=array(
			'ticket_id'=>$insert_id,
			'msg_by'=>$this->input->post('client_id'),
			'msg'=>$this->input->post('msg'),
			'msg_time'=>strtotime(date('d-m-Y h:i:sa',time())),
		 	'screenshot'=>$this->input->post('screenshot')
			);

			if($this->db->insert('jotjpn_message',$userdataa)){
			
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
				    $mail->Subject = "New ticket has been generated - #".$insert_id." - ".$this->input->post('resource_type');
				    $mail->Body    = "Hi, \n\r New ticket has been generated.\n\r To reply the ticket login into admin portal at ".site_url('login/admin')." \n\r \n\r ".$this->config->item('app_name')."";
				    $mail->AltBody = "Hi, \n\r New ticket has been generated.\n\r To reply the ticket login into admin portal at ".site_url('login/admin')." \n\r \n\r ".$this->config->item('app_name')."";

				    $mail->send();
				  //echo '<div class="alert alert-success">Message has been sent</div>';
				} catch (Exception $e) {
				 //echo "<div class='alert alert-danger'>Message could not be sent.</div>";
				}
				$this->session->set_flashdata('message',"<div class='alert alert-success'>Raised Ticket successfuly!</div>");
				redirect('support');
			 }else{
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>Unable to Raised Ticket.</div>");
				redirect('support');
			 }
				 
			}

	}

	public function view($id)
	{
		$logged_in=$this->session->userdata('logged_in');
		$client_id=$logged_in['client_id'];

		$data['id']=$id;
		$data['result']=$this->support_model->get_data($id);
		$data['result1']=$this->support_model->ticket_data($id);
		$this->db->query("update jotjpn_message set msg_read='1' where msg_by !='$client_id' and msg_read ='0' and ticket_id='$id'");
		$this->load->view('header');
		$this->load->view('view.php',$data);
		$this->load->view('footer');

	}
	public function update_ticket($id)
	{
			$logged_in=$this->session->userdata('logged_in');
			$client_id=$logged_in['client_id'];
			$full_name=$logged_in['full_name'];					

			$last_msg=strtotime(date('d-m-Y h:i:sa',time()));
			
		        if($this->db->query("update jotjpn_tickets set last_msg='$last_msg' where id='$id'")){
			$userdataa=array(
			'ticket_id'=>$id,
			'msg_by'=>$client_id,
			'msg'=>$this->input->post('msg'),
			'msg_time'=>strtotime(date('d-m-Y h:i:sa',time())),
		 	'screenshot'=>$this->input->post('screenshot')
			);

			if($this->db->insert('jotjpn_message',$userdataa)){
			
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
    				    $mail->Subject = "New reply added into ticket by ".$full_name." - #".$id;
				    $mail->Body    = "Hi, \n\r New reply added into ticket  by ".$full_name.".\n\r To view message login into admin portal at ".site_url('login/admin')." \n\r \n\r ".$this->config->item('app_name')."";
				    $mail->AltBody = "Hi, \n\r New reply added into ticket  by ".$full_name.".\n\r To view message login into admin portal at ".site_url('login/admin')." \n\r \n\r ".$this->config->item('app_name')."";

				    $mail->send();
				  //echo '<div class="alert alert-success">Message has been sent</div>';
				} catch (Exception $e) {
				 //echo "<div class='alert alert-danger'>Message could not be sent.</div>";
				}
			
				$this->session->set_flashdata('message',"<div class='alert alert-success'>Updated Data successfuly!</div>");
				redirect('support');
			 }else{
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>Unable to Update Data.</div>");
				redirect('support');
			 }				 
			}

	}
function install_app(){
		$resource_type=array();
		if($_POST['resource_id']!='0'){
			$resource_type=$this->input->post('resource_type');
		}else{
			$resource_type='0';
		}
		        $userdata=array(
			'client_id'=>$this->input->post('client_id'),
			'resource_id'=>$this->input->post('resource_id'),
			'resource_type'=>$resource_type,
			'title'=>'Server Setup',
			'ticket_time'=>strtotime(date('d-m-Y h:i:sa',time())),
			);

		if($this->db->insert('jotjpn_tickets',$userdata)){
			$insert_id=$this->db->insert_id();
			$userdataa=array(
			'ticket_id'=>$insert_id,
			'msg_by'=>$this->input->post('client_id'),
			'msg'=>$this->input->post('msg'),
			'msg_time'=>strtotime(date('d-m-Y h:i:sa',time())),
			);

			if($this->db->insert('jotjpn_message',$userdataa)){
				$this->session->set_flashdata('message',"<div class='alert alert-success'>Raised Ticket successfuly!</div>");
				redirect('user/servers');
			 }else{
				$this->session->set_flashdata('message',"<div class='alert alert-danger'>Unable to Raised Ticket.</div>");
				redirect('user/servers');
			 }
				 
			}
}

}
