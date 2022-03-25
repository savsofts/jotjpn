<?php
Class Login_model extends CI_Model
{
 
 
 function verifyAdmin($username,$password){
	 $password=md5($password);
	 $query=$this->db->query("select * from jotjpn_user where username='$username' and password='$password' ");
	 if($query->num_rows()==0){
		 return false;
	 }else{
		 return $query->row_array();
	 }
 }
 
 function verify($email,$password){
	 $password=base64_encode($password);
	  $query=$this->db->query("select * from jotjpn_clients where email='$email' and password='$password' and account_status='Active' ");
	 if($query->num_rows()==0){
		 return false;
	 }else{
		 return $query->row_array();
	 }
 }
 
function register($email,$password){
	 $password=base64_encode($password);
	  $query=$this->db->query("select * from jotjpn_clients where email='$email'  ");
	 if($query->num_rows()==0){
		 $code=rand(111111,999999);
		 $userdata=array(
		 'email'=>$email,
		 'password'=>$password,
		 'account_status'=>'Active',
		 'verification_code'=>$code
		 );
		 if($this->db->insert('jotjpn_clients',$userdata)){
			 
		
		
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
    // Attachments
   //  $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
     // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Verification code for registration on '.$this->config->item('app_name');
    $mail->Body    = "Hi, \r\n \r\n Your verification code to register a new account with ".$this->config->item('app_name')." is ".$code." \r\n \r\n  Team \r\n  ".$this->config->item('app_name')."";
    $mail->AltBody = "Hi, \r\n \r\n  Your verification code to register a new account with ".$this->config->item('app_name')." is ".$code." \r\n \r\n Team \r\n  ".$this->config->item('app_name')."";

    $mail->send();
  //  echo '<div class="alert alert-success">Message sent successfully and we will contact you shortly</div>';
} catch (Exception $e) {
  //  echo "<div class='alert alert-danger'>Message could not be sent.</div>";
}


			 
			 return "done";
		 }else{
			 return "Failed to insert";
		 }
		 return true;
	 }else{
		 return "Account with given email id already exist";
	 }
 }
 
function reset($email){
	  $query=$this->db->query("select * from jotjpn_clients where email='$email'  ");
	 if($query->num_rows()==1){
		// sent email
		$p=rand(111111,999999);
		 $password=base64_encode($p);
		 $this->db->query("update jotjpn_clients set password='$password' where email='$email' ");



		
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
     
    // Attachments
   //  $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
     // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'New password to login at  '.$this->config->item('app_name');
    $mail->Body    = "Hi, \r\n \r\n Your new password is ".$p." \r\n Change your password after login \r\n \r\n  Team \r\n  ".$this->config->item('app_name')."";
    $mail->AltBody = "Hi, \r\n \r\n Your new password is ".$p." \r\n Change your password after login \r\n \r\n  Team \r\n  ".$this->config->item('app_name')."";

    $mail->send();
  //  echo '<div class="alert alert-success">Message sent successfully and we will contact you shortly</div>';
} catch (Exception $e) {
  //  echo "<div class='alert alert-danger'>Message could not be sent.</div>";
}

	
		return true;
	 }else{
		 return "Account doesn't exist with given email id";
	 }
 }
function resend($email){
	 
	  $query=$this->db->query("select * from jotjpn_clients where email='$email'  ");
	 if($query->num_rows()==1){
		 $row=$query->row_array();
		 $code=$row['verification_code'];
		// sent email
		 
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
    // Attachments
   //  $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
     // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Verification code for registration on '.$this->config->item('app_name');
    $mail->Body    = "Hi, \r\n \r\n Your verification code to register a new account with ".$this->config->item('app_name')." is ".$code." \r\n \r\n  Team \r\n  ".$this->config->item('app_name')."";
    $mail->AltBody = "Hi, \r\n \r\n  Your verification code to register a new account with ".$this->config->item('app_name')." is ".$code." \r\n \r\n Team \r\n  ".$this->config->item('app_name')."";
 
    $mail->send();
   //  echo '<div class="alert alert-success">Message sent successfully and we will contact you shortly</div>';
} catch (Exception $e) {
   // echo "<div class='alert alert-danger'>Message could not be sent.</div>";
}
	
		return true;
	 }else{
		 return false;
	 }
 }
 
function verifyCode($email,$code){
	  $query=$this->db->query("select * from jotjpn_clients where email='$email' and verification_code='$code' ");
 
	 if($query->num_rows()==1){
		  $this->db->query("update jotjpn_clients set email_verification='Verified' where email='$email' ");
	
		return true;
	 }else{
		 return false;
	 }
 }
 
}