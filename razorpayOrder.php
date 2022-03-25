<?php 
	define('BASEPATH','');
	require('razorpay-php/Razorpay.php');
	require('application/config/database.php');
	 
	use Razorpay\Api\Api;

$servername = $db['default']['hostname'];
$username = $db['default']['username'];
$password = $db['default']['password'];
$dbname = $db['default']['database']; 
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql6="select  * from jotjpn_settings where id='1' ";
$result6=$conn->query($sql6);
$row6 = $result6->fetch_assoc();
 
 
$r_id=$row6['razorpay_id'];
$r_key=$row6['razorpay_key'];

if($_GET['req']=="createOrder"){
	$client_id=$_GET['client_id'];
	$amount=$_GET['amount'];

	$api = new Api($r_id, $r_key);
	$res=$api->order->create(array('receipt' => $client_id.'-'.time(), 'amount' => $amount, 'currency' => 'INR'));
	$jarr=array(
	'order_id'=>$res->id,
	'receiptt'=>$res->receipt
	);
	echo json_encode($jarr);
}

if($_GET['req']=="getOrder"){
	$order_id=$_GET['order_id'];
	$client_id=$_GET['client_id'];
	$api = new Api($r_id, $r_key);
	$res=$api->order->fetch($order_id);
	 print_r($res);
	 
	 
	 $txn_id=$res->id;
	 $amt=(($res->amount_paid)/100)/75;
	 $p_amount=number_format((float)$amt, 2, '.', '');
	 
	 

date_default_timezone_set("Asia/Calcutta");
$tim=date('Y-m-d H:i:s',time());
$sql2="select  id from jotjpn_transaction where t_id='$txn_id' ";
$result=$conn->query($sql2);
if($result->num_rows >= 1){
	exit('Failed');
}
$sql = "INSERT INTO jotjpn_transaction (client_id, amount, t_datetime, t_id, t_mode, t_status, remark)
VALUES ('$client_id','$p_amount','$tim', '$txn_id', 'Razorpay','Success','Fund Added')";
 
if ($conn->query($sql) === TRUE) {
 //  echo "New record created successfully";
} else {
 //  echo "Error: " . $sql . "<br>" . $conn->error;
}
$sql = "update jotjpn_clients set account_balance=(account_balance + $p_amount) where client_id='$client_id' ";
 
if ($conn->query($sql) === TRUE) {
 //  echo "New record created successfully";
} else {
 //  echo "Error: " . $sql . "<br>" . $conn->error;
}
 
$conn->close();	
	
	
	
}