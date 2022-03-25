<style>
a {
   padding-left: 5px;
   padding-right: 5px;
   margin-left: 5px;
   margin-right: 5px;
}
</style>
<title><?php echo $title;?></title>
<div class="container  ">
	<div class="row">
		<div class="col-lg-12">		

<?php if($this->session->flashdata('message')){ echo $this->session->flashData('message'); }?>
		
		<div class="card"  >
		  <div class="card-body">
			<h5 class="card-title"><?php echo $title;?></h5>
    <input type="text" id="search" placeholder="Search Status" > &nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary btn-sm" onclick="searchStatus();">Search</button><br><br>
			<div style="clear:both;"></div>
			<table class="table table-bordered" id="table_id" class="display">
				<thead>
					<tr>
					<th>#</th><th>Title</th><th>Resource Type</th><th>Status</th><th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($support as $k => $row){ ?>
					<tr>
					 <td><?php echo $row['id'];?></td>
					 <td><?php echo $row['title'];?></td>
					 <?php if($row['resource_type']=='0'){ ?>
					 <td><?php echo 'Other';?></td>
					 <?php } else{?>
					 <td><?php echo $row['resource_type'];?></td>
					 <?php } ?>
					 <?php if($row['ticket_status']=='Closed'){ ?>
					 <td style="color:red;"><?php echo $row['ticket_status'];?></td>
					 <?php } else{?>
					 <td style="color:green;"><?php echo $row['ticket_status'];?></td>
					 <?php } ?>
					<td>
					<a href="<?php echo site_url('admin/view/'.$row['id']);?>">view</a>
					</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
	<!-- Paginate -->
	<div style="margin-top:10px;">
		<?php echo $pagination; ?>
	</div>
		  </div>
		</div>		
			


		</div>
	</div>

</div>
<script>
function searchStatus(){
	var v=$('#search').val();
	window.location="<?php echo site_url('admin/support/');?>"+v;
}

</script>
