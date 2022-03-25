<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $server['server_name'];?> <a href="javascript:location.reload();"><i class='fa fa-sync-alt'></i></a></h1>
        <!--
		<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
		-->
<?php if($this->session->flashdata('message')){ echo $this->session->flashData('message'); }?>
                                    
<div id="backupModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog">
    <div class="modal-content">
<form method="post" action="<?php echo site_url('user/create_backup/');?>" enctype="multipart/form-data">
      <div class="modal-header">
       <h4 class="modal-title" id="myModalLabel">Create Backup</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;  </button>
        
      </div>
      <div class="modal-body">
        <div class="form-group">
	<label>Name</label>
	<input type="text" class="form-control" name="name" required autocomplete="off">
	<input type="text" class="form-control" name="server_id" value="<?php echo $server['id'];?>" hidden>
	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button class="btn btn-primary">Submit</button>
      </div>
    </div>
</form>
  </div>
</div>
 </div>
 
<?php 
$status="active";
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
			curl_close($ch);
			  if(isset($d->droplet->status)){
			$status=$d->droplet->status;
			  }



			$url=$this->config->item('do_api_url').'monitoring/metrics/droplet/cpu?host_id='.$server_identifier.'&start='.(time()-3600).'&end='.time();
			 
		 
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
			curl_close($ch);
			  
			$cpuTime=array();
			$cpuU=array();
			if(isset($d->data->result)){
			foreach($d->data->result[0]->values as $k => $cpu){
				$cpuTime[]=date("H:i",$cpu[0]);
				$cpuU[]=$cpu[1];
			}
			
			}

			$url=$this->config->item('do_api_url').'monitoring/metrics/droplet/memory_free?host_id='.$server_identifier.'&start='.(time()-3600).'&end='.time();
			 
		 
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
			curl_close($ch);
			$memTime=array();
			$memU=array();
			if(isset($d->data->result)){
			foreach($d->data->result[0]->values as $k => $mem){
				$memTime[]= date("H:i",$mem[0]);
				$memU[]= intval(($mem[1]/1024)/1024);
			}
			
			}




?> 
 	 <div class="row" style="background:#ffffff;padding-top:20px;padding:10px;">  
<!--<a href="" class="btn btn-primary btn-sm" style="float:right;" data-toggle="modal" data-target="#backupModal">Backup</a>-->

<ul class="nav nav-tabs" style="border:none;">
<li class="nav-item dropdown no-arrow" style="padding-left:10px;">
<a class="nav-link dropdown-toggle" href="" style="border:1px solid #eaecf4;border-radius:0rem;">Overview</a>
  </li>
  <li class="nav-item dropdown no-arrow" style="padding-left:10px;">
<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border:1px solid #eaecf4;border-radius:0rem;">General settings</a>
<div class="dropdown-menu" aria-labelledby="userDropdown">
 <a class="dropdown-item" href="#" style="color:#6e707e;">Settings</a>
<?php 
 if($status=="active"){ ?>
<a href="javascript:popupModal('#rebootModal');" class="dropdown-item" style="color:#36b9cc;">Reboot</a>
<a href="javascript:popupModal('#powerModal');" class="dropdown-item" style="color:#f6c23e;">Power off</a>
<?php }else if($status=="off"){ ?>
<a href="<?php echo site_url('user/poweron_server/'.$server['server_identifier']);?>" class="btn btn-success" style="color:#1cc88a">Turn on</a>
<?php } ?>
<a href="javascript:popupModal('#distroyModal');" class="dropdown-item" style="color:#e74a3b;">Distroy</a>
 </div>
  </li>
  <!--
<form method="post" action="">
  <li class="nav-item dropdown no-arrow" style="padding-left:10px;">
    <a class="nav-link dropdown-toggle" href="#" id="AppDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border:1px solid #eaecf4;border-radius:0rem;">Application</a>
 <?php $application= 'Apache,MySQL,PHP';
       $AppData=explode(',',$application);
?>

<div class="dropdown-menu" aria-labelledby="AppDropdown">
 <span class="dropdown-item" style="color:#6e707e;">
<?php foreach($AppData as $key => $val){ ?>
<input type="checkbox" name="msg" id="chk<?php echo $key;?>" onClick="add();" value="<?php echo $val;?>" /><?php echo $val;?></br>
<input type="text" id="client_id" name="client_id" value="<?php echo $server['client_id']; ?>" hidden>
<input type="text" id="resource_id" name="resource_id" value="<?php echo $server['id']; ?>" hidden>
<input type="text" id="resource_type" name="resource_type" value="<?php echo $server['server_name']; ?>" hidden>
<?php } ?></span> 
 </div>
  </li>
</form>
-->
  <li class="nav-item dropdown no-arrow" style="padding-left:10px;">
<a class="nav-link dropdown-toggle" href="#" id="backupDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border:1px solid #eaecf4;border-radius:0rem;">Backup</a>
<div class="dropdown-menu" aria-labelledby="backupDropdown">
 <a class="dropdown-item" href="" data-toggle="modal" data-target="#backupModal" style="color:#4e73df;">Backup</a>
 </div>
  </li>
  </ul>  
</div>

 	 <div class="row" style="background:#ffffff;padding-top:20px;padding:10px;">  
			<div class="col-lg-12 mb-12">
				  <p><?php echo $server['vcpu'];?> CPU /&nbsp;&nbsp; <?php echo $server['ram_gb'];?> GB RAM /&nbsp;&nbsp; <?php echo $server['space_gb'];?> GB SSD /&nbsp;&nbsp; <?php echo $server['data_transfer_tb'];?> TB Monthly Data Transfer
				  &nbsp;&nbsp;&nbsp;&nbsp; Created on: <?php echo $server['created_time'];?>
				  </p>
					<hr>
				  <p>IPv4: <?php echo $server['server_ip'];?></p>
				  <div class="row">
					<div class="col-lg-6 mb-6">
					<h5>Last 60 Min. CPU consumption (in %)</h5>
						<canvas  id="lineChart"></canvas>
					</div>
					<div class="col-lg-6 mb-6">
					<h5>Last 60 Min. Free Memory (in MB)</h5>
						<canvas  id="lineChart2"></canvas>
					</div>
				  </div>
				
				  <hr>
				  
				  
				  <?php 
				  if($status=="active"){ ?>
				  <!--<a href="javascript:popupModal('#rebootModal');" class="btn btn-info">Reboot</a>
				  <a href="javascript:popupModal('#powerModal');" class="btn btn-warning">Power off</a>-->
				  <?php }else if($status=="off"){ ?>
				   <!--<a href="<?php echo site_url('user/poweron_server/'.$server['server_identifier']);?>" class="btn btn-success">Turn on</a>-->
				  
				  <?php } ?>
				  <!--<a href="javascript:popupModal('#distroyModal');" class="btn btn-danger" style="float:right;">Distroy</a>-->
				
				   
			</div>
	</div>
 
 
 </div>
 








<div class="modal fade" id="rebootModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Do you really want to Reboot this server?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

		

     </div>
      <div class="modal-footer">
        <a href="<?php echo site_url('user/reboot_server/'.$server['server_identifier']);?>"  class="btn btn-primary">Reboot Now</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


 
<div class="modal fade" id="powerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Do you really want to Power off this server?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

		 

     </div>
      <div class="modal-footer">
        <a href="<?php echo site_url('user/poweroff_server/'.$server['server_identifier']);?>"  class="btn btn-primary">Power off now</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>





 
<div class="modal fade" id="distroyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Do you really want to Distroy this server?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class='alert alert-danger'>This action can not be undo</div>
		 

     </div>
      <div class="modal-footer">
        <a href="<?php echo site_url('user/distroy_server/'.$server['server_identifier']);?>"  class="btn btn-primary">Distroy now</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


 
 <script>
 function popupModal(id){
	 $(id).modal('show');
 }
 
 

var xValues = <?php echo json_encode($cpuTime);?>;
var yValues = <?php echo json_encode($cpuU);?>;
var barColors = ["#16b72a"];
// ["#16b72a","#ee4823","#d2d22e","#23bfee"];

var myChart = new Chart("lineChart", {
  type: 'line',
  data: {
    labels: xValues,
    datasets: [{
		label: '',
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
	  plugins: {
            legend: {
                display: false
			}
	  },
    title: {
      display: true,
      text: "Daily Presence Chart "
    }
  }
});




var xValues = <?php echo json_encode($memTime);?>;
var yValues = <?php echo json_encode($memU);?>;
var barColors = ["#16b72a"];
// ["#16b72a","#ee4823","#d2d22e","#23bfee"];

var myChart = new Chart("lineChart2", {
  type: 'line',
  data: {
    labels: xValues,
    datasets: [{
		label: '',
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
	  plugins: {
            legend: {
                display: false
			}
	  },
    title: {
      display: true,
      text: "Daily Presence Chart "
    }
  }
});

function add(){
$("input[name='msg']:checked").each(function(i) {
 var msg=$(this).val();
 var client_id=$('#client_id').val();
 var resource_id=$('#resource_id').val();
 var resource_type=$('#resource_type').val();
//alert(appdata);
$.post(base_url+"index.php/support/install_app/",{msg:msg,client_id:client_id,resource_id:resource_id,resource_type:resource_type},function(data){
var data=JSON.parse(data);
//if(data.result=="success"){
//alert('data');
//}
//else{
//}

});
});
 }


 </script>
