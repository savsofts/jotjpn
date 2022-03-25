<?php 
namespace Listener;
use Mysqli;

define('BASEPATH','');
require('application/config/database.php');
require('application/config/config.php');

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
 
// Set this to true to use the sandbox endpoint during testing:
$enable_sandbox = false;

// Use this to specify all of the email addresses that you have attached to paypal:
$my_email_addresses = array($row6['paypal_receiver_id']);

// Set this to true to send a confirmation email:
$send_confirmation_email = false;
$confirmation_email_address = "My Name <my_email_address@gmail.com>";
$from_email_address = "My Name <my_email_address@gmail.com>";

// Set this to true to save a log file:
$save_log_file = true;
$log_file_dir = __DIR__ . "/paypal_logs";

// Here is some information on how to configure sendmail:
// http://php.net/manual/en/function.mail.php#118210


require('PaypalIPN.php');
use PaypalIPN;

$ipn = new PaypalIPN();
if ($enable_sandbox) {
    $ipn->useSandbox();
}
$verified = $ipn->verifyIPN();

$data_text = "";
foreach ($_POST as $key => $value) {
    $data_text .= $key . " = " . $value . "\r\n";
}

$test_text = "";
if ($_POST["test_ipn"] == 1) {
    $test_text = "Test ";
}

// Check the receiver email to see if it matches your list of paypal email addresses
$receiver_email_found = false;
foreach ($my_email_addresses as $a) {
    if (strtolower($_POST["receiver_email"]) == strtolower($a)) {
        $receiver_email_found = true;
        break;
    }
}

date_default_timezone_set("America/Los_Angeles");
list($year, $month, $day, $hour, $minute, $second, $timezone) = explode(":", date("Y:m:d:H:i:s:T"));
$date = $year . "-" . $month . "-" . $day;
$timestamp = $date . " " . $hour . ":" . $minute . ":" . $second . " " . $timezone;
$dated_log_file_dir = $log_file_dir . "/" . $year . "/" . $month;

$paypal_ipn_status = "VERIFICATION FAILED";
if ($verified) {
    $paypal_ipn_status = "RECEIVER EMAIL MISMATCH";
    if ($receiver_email_found) {
        $paypal_ipn_status = "Completed Successfully1";


        // Process IPN
        // A list of variables are available here:
        // https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/

        // This is an example for sending an automated email to the customer when they purchases an item for a specific amount:
        if ($_POST["payment_status"] == "Completed") {
           $p_amount= $_POST["mc_gross"];
		   $email=$_POST["payer_email"];
		   $client_id=$_POST["custom"];
			$txn_id=$_POST['txn_id'];

 
date_default_timezone_set("Asia/Calcutta");
$tim=date('Y-m-d H:i:s',time());
$sql2="select  id from jotjpn_transaction where t_id='$txn_id' ";
$result=$conn->query($sql2);
if($result->num_rows >= 1){
	exit('Failed');
}
$sql = "INSERT INTO jotjpn_transaction (client_id, amount, t_datetime, t_id, t_mode, t_status, remark)
VALUES ('$client_id','$p_amount','$tim', '$txn_id', 'Paypal','Success','Fund Added')";
 
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


    }
} elseif ($enable_sandbox) {
    if ($_POST["test_ipn"] != 1) {
        $paypal_ipn_status = "RECEIVED FROM LIVE WHILE SANDBOXED";
    }
} elseif ($_POST["test_ipn"] == 1) {
    $paypal_ipn_status = "RECEIVED FROM SANDBOX WHILE LIVE";
}

if ($save_log_file) {
    // Create log file directory
    if (!is_dir($dated_log_file_dir)) {
        if (!file_exists($dated_log_file_dir)) {
            mkdir($dated_log_file_dir, 0777, true);
            if (!is_dir($dated_log_file_dir)) {
                $save_log_file = false;
            }
        } else {
            $save_log_file = false;
        }
    }
    // Restrict web access to files in the log file directory
    $htaccess_body = "RewriteEngine On" . "\r\n" . "RewriteRule .* - [L,R=404]";
    if ($save_log_file && (!is_file($log_file_dir . "/.htaccess") || file_get_contents($log_file_dir . "/.htaccess") !== $htaccess_body)) {
        if (!is_dir($log_file_dir . "/.htaccess")) {
            file_put_contents($log_file_dir . "/.htaccess", $htaccess_body);
            if (!is_file($log_file_dir . "/.htaccess") || file_get_contents($log_file_dir . "/.htaccess") !== $htaccess_body) {
                $save_log_file = false;
            }
        } else {
            $save_log_file = false;
        }
    }
    if ($save_log_file) {
        // Save data to text file
        file_put_contents($dated_log_file_dir . "/" . $test_text . "paypal_ipn_" . $date . ".txt", "paypal_ipn_status = " . $paypal_ipn_status . "\r\n" . "paypal_ipn_date = " . $timestamp . "\r\n" . $data_text . "\r\n", FILE_APPEND);
    }
}

if ($send_confirmation_email) {
    // Send confirmation email
  //  mail($confirmation_email_address, $test_text . "PayPal IPN : " . $paypal_ipn_status, "paypal_ipn_status = " . $paypal_ipn_status . "\r\n" . "paypal_ipn_date = " . $timestamp . "\r\n" . $data_text, "From: " . $from_email_address);
}

// Reply with an empty 200 response to indicate to paypal the IPN was received correctly
header("HTTP/1.1 200 OK");