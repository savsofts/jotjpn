<?php
Class User_model extends CI_Model
{
 
 
 function getServerType(){
	 $query=$this->db->query("select * from jotjpn_server_type order by server_type_id asc ");
	return $query->result_array();
	 
 }
 
  function getRegion(){
	 $query=$this->db->query("select * from jotjpn_region order by id asc ");
	return $query->result_array();
	 
 }
 
 
 function getServers($id){
	$query=$this->db->query("select * from jotjpn_servers where server_status='Active' and server_type_id='$id' order by price_per_month asc ");
	return $query->result_array();
 }
 
 
 function getServersByClient(){
		$query=$this->db->query("select * from jotjpn_assigned_servers left join jotjpn_clients on jotjpn_clients.client_id=jotjpn_assigned_servers.client_id where assigned_server_status='Active' order by id asc ");
	return $query->result_array();
  
	 
 }
 
 
 function getSshkey($client_id){
	$query=$this->db->query("select * from jotjpn_ssh_key where client_id='$client_id' order by id asc ");
	return $query->result_array(); 
	 
 }
 function getServer($id){
	$query=$this->db->query("select * from jotjpn_servers where id='$id' order by id asc ");
	return $query->row_array(); 
	 
 }
 
 function getDoApiKey(){
	$query=$this->db->query("select digitalocean_api_key from jotjpn_settings where id='1'  ");
	$do=$query->row_array(); 
	return $do['digitalocean_api_key'];	
 }
 
 function total_servers($client_id){
	$query=$this->db->query("select * from jotjpn_assigned_servers where assigned_server_status !='Inactive' and client_id='$client_id'   ");
	return $query->num_rows();
	 
 }
 
function account_balance($client_id){
	$query=$this->db->query("select * from jotjpn_clients where   client_id='$client_id'   ");
	$row=$query->row_array();
	 return $row['account_balance'];
 }
 
function sshkeys($client_id){
	$query=$this->db->query("select * from jotjpn_ssh_key where  client_id='$client_id'   ");
	return $query->num_rows();
 }
 
 function getAssignedSerers($client_id){
		$query=$this->db->query("select * from  jotjpn_assigned_servers where client_id='$client_id' and assigned_server_status !='Inactive' order by id desc ");
	return $query->result_array(); 
 
	 
 }
 
 function getAssignedSerer($client_id,$server_identifier){
		$query=$this->db->query("select * from  jotjpn_assigned_servers where client_id='$client_id' and server_identifier='$server_identifier' and assigned_server_status !='Inactive' order by id desc ");
	return $query->row_array(); 
 
	 
 }

 function getAssignedSerersAllProcessing(){
		$query=$this->db->query("select server_identifier from  jotjpn_assigned_servers where   assigned_server_status ='Processing' order by id asc limit 5 ");
	return $query->result_array(); 
 
	 
 }
 function inactivateServer($server_identifier){
	  $query=$this->db->query("update  jotjpn_assigned_servers set assigned_server_status='Inactive' where   server_identifier='$server_identifier' ");
 }
 
 function validateServerOwnership($server_identifier,$client_id){
	 $query=$this->db->query("select server_identifier from  jotjpn_assigned_servers where  client_id='$client_id' and  server_identifier='$server_identifier' ");
	if($query->num_rows() == 1){
		return true;
	}else{
		return false;
	}
 }
 
 function getrecordCountAll() {
       $this->db->select('count(*) as allcount');
       $this->db->from('jotjpn_backup');
       $query = $this->db->get();
       $result = $query->result_array();
       return $result[0]['allcount'];
 }

 function backups($limit,$rowperpage){
	 $query=$this->db->query("select * from jotjpn_backup where delete_status='0' order by id desc limit $limit,$rowperpage");
	return $query->result_array();
	 
 }
 
}
