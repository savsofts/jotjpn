<style>
#save-and-go-back-button, #cancel-button{
	display:none;
}
p a{
	display:none;
}
</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create new server</h1>
        <!--
		<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
		-->
<?php if($this->session->flashdata('message')){ echo $this->session->flashData('message'); }?>
                                    
 
 </div>
 <form method="post" action="<?php echo site_url('user/api_create');?>"  >
	 <div class="row" style="background:#ffffff;padding-top:20px;">  
			<div class="col-lg-12 mb-12">
				<h5>Choose An Operating System</h5>	
				<div class="row" style="padding-bottom:30px;">
				<?php 
				$i=0;
					foreach($this->config->item('osArr') as $k => $os){ ?>
						
							<div class="col-lg-2 mb-2">
									
									<div class="card" style="height:90px;">
									  <div class="card-body">
										<input type="radio" name="image" value="<?php echo $k;?>" <?php if($i==0){ echo 'checked'; } ?>  required > <?php echo $os;?>
									  </div>
									</div>
							</div>
					  
					<?php $i+=1; } 
				
				
				?>
				  </div>
				<h5>Choose Server Type</h5>	
				<div class="row"  style="padding-bottom:30px;">
					<div class="col-lg-12">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
							<?php foreach($server_type as $k => $ser){ ?>
							  <li class="nav-item">
								<a class="nav-link <?php if($k==0){ echo "active";}?> " id="t<?php echo $k;?>-tab" data-toggle="tab" href="#t<?php echo $k;?>" role="tab" aria-controls="<?php echo $k;?>" aria-selected="true"><?php echo $ser['type_name'];?></a>
							  </li>
							<?php } ?>
							 
							</ul>
							<div class="tab-content" id="myTabContent">
							<?php foreach($server_type as $k => $ser){ ?>
							  <div class="tab-pane fade show <?php if($k==0){ echo "active";}?> " id="t<?php echo $k;?>" role="tabpanel" aria-labelledby="t<?php echo $k;?>-tab">
							  <br> 
							  <p><?php echo $ser['description'];?></p>
							  
							  <div class="row"  > 
								<?php 
								if(count($ser['servers'])==0){
									?>
									<div class='alert alert-danger'>No server available of this configuration</div>
									<?php
								}
								$i=0;
									foreach($ser['servers'] as $sk => $server){ ?>
										
											<div class="col-lg-3 mb-3">
													
													<div class="card">
													  <div class="card-body">
															<h5 class="card-title"><input type="radio" name="size" value="s-<?php echo $server['vcpu'];?>vcpu-<?php echo $server['ram_gb'];?>gb::<?php echo $server['id'];?>" <?php if($i==0){ echo 'checked'; } ?>  required >  <?php echo $this->config->item('currency_prefix')."".$server['price_per_month'];?>/month</h5>
															<?php if($server['price_per_hour'] != ""){ ?>
															<h6 class="card-subtitle mb-2 text-muted"><?php echo $this->config->item('currency_prefix')."".$server['price_per_hour'];?>/hour</h6>
															<?php } ?>
														<p style="font-size:13px;">
														<?php echo $server['vcpu'];?> CPU<br>
														<?php echo $server['ram_gb'];?>GB RAM<br>
														<?php echo $server['space_gb'];?>GB SSD<br>
														<?php echo $server['data_transfer_tb'];?>TB Data Transfer<br>
														</p>
														<div style="font-size:13px;background:#f7ff83;margin-top:10px;color:#212121;padding:2px;"><?php echo $server['other_info'];?></div>
														
													  </div>
													</div>
											</div>
									  
									<?php $i+=1; } 
								
								
								?>
								</div>
							  </div>
							<?php } ?>
							  
							</div>
					
					</div>				
				</div>

				<h5>Choose Region</h5>	
				<div class="row" style="padding-bottom:30px;">
				<?php 
				$i=0;
					  foreach($region as $k => $reg){ ?>
						
							<div class="col-lg-2 mb-2">
									
									<div class="card">
									  <div class="card-body">
									 
										<input type="radio" name="region" value="<?php echo $reg['region_code'];?>" <?php if($i==0){ echo 'checked'; } ?>  required  > <?php echo $reg['region_name'];?>
									  </div>
									</div>
							</div>
					  
					<?php $i+=1; } 
				
				
				?>
				  </div>

					
				<div class="row" style="padding-bottom:30px;">
				
						
							<div class="col-lg-6 mb-6">
							 
									<div class="card">
									  <div class="card-body">
									   <h5 class="card-title">Select SSH Key</h5>
										<p>Instead of root password, server required ssh key to make connection. SSH key is a more secure authentication method</p>
										 <select name="ssh_keys" class="form-control" required >
										 <option value="">Select</option>
										 <?php foreach($sshkey as $ssk => $ssh){ ?>
										 <option value="<?php echo $ssh['ssh_key_id'];?>"><?php echo $ssh['ssh_key_name'];?></option>
										 <?php } ?>
										 </select><br>
										<?php if(count($sshkey)==0){ ?><div class='alert alert-danger'>You don't have any ssh key in your account. <a href="<?php echo site_url('user/sshkey');?>">Click here</a> to add SSH Key</div><?php }else{ ?> 
										<a href="<?php echo site_url('user/sshkey');?>">Add New SSH Key</a> <?php } ?>
									  </div>
									</div>
									
							</div>
					 
										 
					
				</div>	
				  
				<div class="row" style="padding-bottom:30px;">
				
						
							<div class="col-lg-6 mb-6">
							 
									<div class="card">
									  <div class="card-body">
									   <h5 class="card-title">Give a name to this server</h5>	
										 <input type="text" class="form-control" name="name"  required >
										  
									  </div>
									</div>
									<br><br>
									<button type="submit" class="btn btn-success">Create Server</button>
							</div>
					 
										 
					
				</div>	
				  
			</div>
	 </div>
	 </form>
	 
 </div>

 