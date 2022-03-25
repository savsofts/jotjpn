 
</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Server (<?php echo count($servers);?>) <a href="javascript:location.reload();"><i class='fa fa-sync-alt'></i></a></h1>
        <!--
		<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
		-->
<?php if($this->session->flashdata('message')){ echo $this->session->flashData('message'); }?>
                                    
 
 </div>
 
 
 	 <div class="row" style="background:#ffffff;padding-top:20px;">  
			<div class="col-lg-12 mb-12">
				<div class="row" style="padding-bottom:30px;">
				<?php 
				if(count($servers)==0){ ?> <div class="alert alert-danger">You don't have any server. <a href="<?php echo site_url('user/create');?>">Create Now</a></div><?php }  
				$i=0;
					foreach($servers as $k => $server){ ?>
						
							<div class="col-lg-4 mb-4">
									
									<div class="card" style="">
									  <div class="card-body">
										
										<h5 class="card-title"><a href="<?php echo site_url('user/server_detail/'.$server['server_identifier']);?>"><?php echo $server['server_name'];?></a></h5>
										<h6 class="card-subtitle mb-2 text-muted"><?php if($server['server_ip'] != ""){ echo 'ipv4: '.$server['server_ip'];}else{ echo "Preparing your server.."; ?> 
										<div class="spinner-border text-primary" role="status">
										  <span class="sr-only">Loading...</span>
										</div>
										
										<?php } ?> </h6>
										<p><?php echo $server['vcpu'];?>CPU <?php echo $server['ram_gb'];?>GB RAM <?php echo $server['space_gb'];?>GB SSD</p>
										
									  </div>
									</div>
							</div>
					  
					<?php $i+=1; } 
				
				
				?>
				
				  </div>
			</div>
	</div>
 
 
 </div>