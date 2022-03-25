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
        <h1 class="h3 mb-0 text-gray-800">Transactions</h1>
        <!--
		<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
		-->

 
 </div>
 <div class="row" style="background:#ffffff;">  
                         <div class="col-lg-12 mb-12">
								<div style="padding: 10px">
									<?php echo $output; ?>
								</div>

						</div>
 </div>
 </div>
 
 
 
<!-- End of Main Content -->
   <?php foreach($js_files as $file): ?>
   <?php 
  
   $file=str_replace("".base_url()."vendor/datatables/","".base_url()."theme/vendor/datatables/",$file);
   ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>
 
