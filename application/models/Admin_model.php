<?php
Class Admin_model extends CI_Model
{
 
  function getDoApiKey(){
	$query=$this->db->query("select digitalocean_api_key from jotjpn_settings where id='1'  ");
	$do=$query->row_array(); 
	return $do['digitalocean_api_key'];	
 }
 
 function monthlyEarning(){
	 $tim2=date('Y-m-d',time());
	 $tim1=date('Y-m-d',(time()-(86400*30)));
	 
	 $query=$this->db->query("select sum(amount) as amount from jotjpn_transaction where t_datetime >= '$tim1' and t_datetime <= '$tim2'   ");
	$do=$query->row_array(); 
	return $do['amount'];	
 }
 
 function total_servers(){
	$query=$this->db->query("select * from jotjpn_assigned_servers where assigned_server_status !='Inactive'  ");
	return $query->num_rows();
	 
 }
 function total_clients(){
	$query=$this->db->query("select * from jotjpn_clients where account_status ='Active'  ");
	return $query->num_rows();
	 
 }
 function get_client($client_id){
	$query=$this->db->query("select * from jotjpn_clients where client_id ='$client_id'  ");
	return $query->row_array();
	 
 }
  
 
}