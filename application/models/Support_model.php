<?php
Class Support_model extends CI_Model
{
 
 function getrecordCount() {
       $this->db->select('count(*) as allcount');
       $this->db->from('jotjpn_tickets');
       $query = $this->db->get();
       $result = $query->result_array();
       return $result[0]['allcount'];
 }
 function support_list($ticket_status,$client_id,$limit,$rowperpage){
	     if($ticket_status=='0'){
	$query=$this->db->query("select * from jotjpn_tickets where client_id='$client_id' order by id desc limit $limit,$rowperpage");
	     }
	     else{
	$query=$this->db->query("select * from jotjpn_tickets where ticket_status='$ticket_status' and client_id='$client_id' order by id desc limit $limit,$rowperpage");
		}
	return $query->result_array(); 	 
 }
 function get_data($id){
		$query=$this->db->query("select jotjpn_tickets.client_id,jotjpn_tickets.resource_type,jotjpn_tickets.resource_id,jotjpn_tickets.title,jotjpn_tickets.ticket_status,jotjpn_tickets.ticket_time,jotjpn_tickets.last_msg,
jotjpn_message.msg_by,jotjpn_message.msg,jotjpn_message.msg_time,jotjpn_message.screenshot,jotjpn_clients.full_name from jotjpn_tickets join jotjpn_message on jotjpn_message.ticket_id=jotjpn_tickets.id left join jotjpn_clients on jotjpn_clients.client_id=jotjpn_tickets.client_id where jotjpn_tickets.id='$id'");
	return $query->row_array(); 
 }
 function ticket_data($id){
	$query=$this->db->query("select * from jotjpn_tickets join jotjpn_message on jotjpn_message.ticket_id=jotjpn_tickets.id where jotjpn_tickets.id='$id'");
	return $query->result_array(); 
 }
 function getrecordCountAll() {
       $this->db->select('count(*) as allcount');
       $this->db->from('jotjpn_tickets');
       $query = $this->db->get();
       $result = $query->result_array();
       return $result[0]['allcount'];
 }
 function support_list_all($ticket_status,$limit,$rowperpage){
		 if($ticket_status=='0'){
	$query=$this->db->query("select * from jotjpn_tickets order by id desc limit $limit,$rowperpage");
		 }
		else{
	$query=$this->db->query("select * from jotjpn_tickets where ticket_status='$ticket_status' order by id desc limit $limit,$rowperpage");
		}
	return $query->result_array(); 	 
 }
 
}
