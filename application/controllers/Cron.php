<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
 
    function __construct()
	 {
	   parent::__construct();
	    $this->load->model("user_model");
		
		
	 }

	function bill(){
		$servers=$this->user_model->getServersByClient();
	
		foreach($servers as $k => $server){
			$price_per_hour=$server['price_per_hour'];
			$client_id=$server['client_id'];
			$balance=$server['account_balance'];
			$a_balance=$balance-$price_per_hour;
			$server_id=$server['id'];
			$dt=date('Y-m-d H:00:00',time());
			$a_balance=number_format((float)$a_balance, 3, '.', '');
			$query=$this->db->query("select id from jhotjpn_billing where client_id='$client_id' and server_id='$server_id' and bill_time='$dt'  ");
			if($query->num_rows()==0){
			$userdata=array(
			'client_id'=>$client_id,
			'server_id'=>$server['id'],
			'amount'=>$server['price_per_hour'],
			'bill_time'=>date('Y-m-d H:00:00',time()),
			'balance'=>$a_balance
			);
			
				
			$this->db->insert('jhotjpn_billing',$userdata);
			$this->db->query("update jotjpn_clients set account_balance='$a_balance' where client_id='$client_id' ");
			}
		}

	}

	
	function getIps(){
	 $do_api_key=$this->user_model->getDoApiKey();
	 $servers=$this->user_model->getAssignedSerersAllProcessing();
		foreach($servers as $k => $server){
			$server_identifier=$server['server_identifier'];

			$url=$this->config->item('do_api_url').'droplets/'.$server_identifier;
			  
			$token = $do_api_key;
			//setup the request, you can also use CURLOPT_URL
			$ch = curl_init($url);

			// Returns the data/output as a string instead of raw data
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
			 
			if(isset($d->droplet->networks->v4)){ 
			foreach($d->droplet->networks->v4 as $ipk => $ipv){
				if($ipv->type == "public"){
				$ipv4=$ipv->ip_address;
				 
			 $this->db->query(" update jotjpn_assigned_servers set server_ip='$ipv4', assigned_server_status='Active' where server_identifier='$server_identifier' ");
				}
			}
			}
			curl_close($ch);
		}
		if(count($servers)==0){
			echo "No server under processing";
		}

	}		
	 
}