<style>


			.fund-popup{
				left:15%;
				width:70%;
				position:fixed;
				top:20%;
				height:500px;
				display:none;
				background:#ffffff;
				padding:30px;
				z-index:1000;
			}


			.popup-bg{
				left:0px;
				width:100%;
				position:fixed;
				top:0px;
				height:100%;
				display:none;
				background:#212121;
				opacity:0.7;
				z-index:900;
			}
@media screen and (max-width: 650px) {
 		.fund-popup{
				left:0px;
				width:100%;
				position:fixed;
				top:0px;
				height:100%;
				display:none;
				background:#ffffff;
				padding:30px;
				z-index:1000;
			}
}
</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <!--
		<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
		-->
    </div>

</div>
<!-- End of Main Content -->
                   <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-1 col-md-1 mb-1"></div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                               Account Balance</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->config->item('currency_prefix')."".$account_balance;?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Servers</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_servers;?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-server fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                SSH Keys</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $sshkeys;?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-key fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         
                    </div>
					
					<div class="row">
					<div class="col-xl-1"></div>
					<div class="col-xl-10">
					<?php if($account_balance <= 10){ ?>
					<div class="alert alert-danger">Your account balance is <?php if($account_balance > 0){ ?>low<?php }else{ ?>negative <?php }?> <br><a href="<?php echo site_url('user/addFund');?>">Add fund</a> to your account to prevent account suspension.</div>
					<?php }else{ ?>
					
					<div class="alert alert-success"><a href="javascript:addFund();">Add fund</a> to your account.</div>
					
					<?php } ?>
					<div class="alert alert-warning"><a href="<?php echo site_url('user/create');?>">Create server</a> within a minute. Get LAMP (Apache, Mysql & PHP) installed automatically.</div>

					</div>
					
					</div>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>




<div class="popup-bg"></div>
<div class="fund-popup">



<div class="card">
  <div class="card-header">
    Add fund to create your first server
	<span style="float:right;">Account Balance: $<?php echo $account_balance;?></span>
  </div>
  <div class="card-body">
	<?php 
	if($status=="completed"){
		?>
	 <div class="alert alert-success" style="font-size:12px;">Your payment has been completed and pending for verification. It will not take more than 2 minutes <a href="<?php echo site_url('user/dashboard/');?>" title="Refresh"><i class="fa fa-sync"></i></a>. If your account balance didn't update after 2 minutes then email us at <?php echo $this->config->item('support_email');?> </div>
	 
		
	<?php } ?>
	<?php 
	if($status=="cancel"){
		?>
	 <div class="alert alert-danger" style="font-size:12px;">Your payment has been cancelled</div>
	 
		
	<?php } ?>
	<?php 
	$logged_in=$this->session->userdata('logged_in');
		 
		$client_id=$logged_in['client_id'];
		$sql6="select  * from jotjpn_settings where id='1' ";
		$query=$this->db->query($sql6);
		$setting=$query->row_array();
		
		?>
<table style="border:0px;">
<tr><td>
<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypalform">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $setting['paypal_receiver_id'];?>">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="item_name" value="<?php echo $this->config->item('app_name');?> Add Fund">
<input type="hidden" name="notify_url" value="<?php echo base_url();?>/ipn.php">
<input type="hidden" name="custom" value="<?php echo $client_id;?>">
<input type="hidden" name="return" value="<?php echo site_url('user/dashboard/completed');?>">
<input type="hidden" name="cancel_return" value="<?php echo site_url('user/dashboard/cancel');?>">
<?php
$amount="29.99"; 
if($this->session->userdata('pack')){
	if($this->session->userdata('pack')=="0"){ $amount="12.99"; }
	if($this->session->userdata('pack')=="1"){ $amount="29.99"; }
	if($this->session->userdata('pack')=="2"){ $amount="59.99"; }
}
?>
$<input type="text" name="amount" id="amount" value="<?php echo $amount;?>" onchange="validateAmount(this);" style="width: 70px;
    padding: 4px;
    border: 0px;
    border-bottom: 1px solid #212121;" autocomplete=off > USD 
	
	<button type="submit" class="btn btn-sm btn-primary">Pay with Paypal</button>
	
	
	
	

</form>
	
</td><td>
 
 <button id="rzp-button1" class="btn btn-sm btn-success" >Pay with RazorPay</button>
 
</td>
</tr>
</table>
<span style="font-size:12px;color:#666666;" id="minprice"></span>
	<br><br>
	 <button type="button" class="btn btn-sm btn-secondary" onclick="addValue('60')">+ $60</button>
	 <button type="button" class="btn btn-sm btn-secondary" onclick="addValue('100')">+ $100</button>
	 <button type="button" class="btn btn-sm btn-secondary" onclick="addValue('250')">+ $250</button> 

  </div>
</div>

<br>
<div class="card">
  <div class="card-body">
  <div class="alert alert-warning" style="font-size:12px;">Add $100 fund and get $130 credit into your account ($30 Extra)</div>
  <div class="alert alert-success" style="font-size:12px;">Add $250 fund and get $300 credit into your account ($50 Extra)</div>
  </div>
</div>
<br>
<a href="<?php echo site_url('login/logout');?>" style="float:right;" id="popupLogout">Logout</a>
<a href="javascript:closePopup();" style="float:right;display:none;" class="btn btn-secondary" id="popupClose" >Close</a>

</div>


<script>

<?php if($pricePopup==1){
	 if($account_balance <= 10){
	?>
	$('.popup-bg').show();
	$('.fund-popup').show();
	<?php 
	 }
}
?>


function addFund(){
		$('#popupLogout').hide();
		$('#popupClose').show();
		$('.popup-bg').show();
		$('.fund-popup').show();
}


function closePopup(){
		$('.popup-bg').hide();
		$('.fund-popup').hide();
}


function validateAmount(e){
	var a=$(e).val();
	if(parseFloat(a) >=12.99){
	}else{
		$(e).val('12.99');
		$('#minprice').html("Minimum $12.99 USD");
	}

	 

}


function addValue(v){
		$('#amount').val(v);
}


var i=0;
var pp=setInterval(function(){
	if(pp >= 10){
		clearInteral(pp);
	}
		$('.PaymentButton-securedBy').html('Pay with RazorPay');
},1000);

</script>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<script>
<?php 
$logged_in=$this->session->userdata('logged_in'); 
?>

document.getElementById('rzp-button1').onclick = function(e){
	$('#rzp-button1').html('Processing..');
	$('#rzp-button1').attr('disabled',true);
$.post("<?php echo base_url();?>/razorpayOrder.php?req=createOrder&client_id=<?php echo $client_id;?>&amount="+(parseFloat($('#amount').val())*75*100),{},function(data2){
	$('#rzp-button1').html('Pay with RazorPay');
	$('#rzp-button1').attr('disabled',false);
var jdata=JSON.parse(data2.trim());

var options = {
    "key": "<?php echo $setting['razorpay_key'];?>", // Enter the Key ID generated from the Dashboard
    "amount": (parseFloat($('#amount').val())*75*100), // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "INR",
    "name": "<?php echo $this->config->item('app_name');?> Account Fund",
    "description": "",
    "image": "<?php echo base_url('images/logo.png');?>",
    "order_id": jdata.order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "handler": function (response){
		console.log(response);
$.post("<?php echo base_url();?>/razorpayOrder.php?req=getOrder&client_id=<?php echo $client_id;?>&order_id="+response.razorpay_order_id,{},function(data3){

	setTimeout(function(){
		location.reload();
	},2000);

});
       // alert(response.razorpay_payment_id);
       // alert(response.razorpay_order_id);
       // alert(response.razorpay_signature)
    },
    "prefill": {
        "name": "",
        "email": "<?php echo $logged_in['email'];?>",
        "contact": ""
    },
    "notes": {
        "address": ""
    },
    "theme": {
        "color": "#3399cc"
    }
};

var rzp1 = new Razorpay(options);
rzp1.on('payment.failed', function (response){
	
       console.log(response);
});

    rzp1.open();
    e.preventDefault();	
});


}
</script>



