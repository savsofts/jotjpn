<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php if(isset($title)){ echo $title; }else{ echo "Dashboard"; } ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url('theme');?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
<script>
var base_url="<?php echo base_url();?>";
</script>
    <!-- Custom styles for this template-->
    <link href="<?php echo base_url('theme');?>/css/sb-admin-2.min.css" rel="stylesheet">
   <script src="<?php echo base_url('theme');?>/vendor/jquery/jquery.min.js"></script>
<style>
.chosen-drop{
	min-width:350px;
}
</style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url();?>" target="new">
                <div class="sidebar-brand-icon rotate-n-15">
                     
                </div>
                <div class="sidebar-brand-text mx-3"><?php echo $this->config->item('app_name');?> <sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo site_url('user/dashboard');?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Servers
            </div>

			
						<li class="nav-item">
							<a class="nav-link" href="<?php echo site_url('user/create');?>" style="color:#f8f9fc;">
								<i class="fas fa-fw fa-plus-square" style="color:#f8f9fc;"></i>
								<span>Create New</span></a>
						</li>
			
					

						<li class="nav-item">
							<a class="nav-link" href="<?php echo site_url('user/servers');?>">
								<i class="fas fa-fw fa-server"></i>
								<span>Servers</span></a>
						</li>


						<li class="nav-item">
							<a class="nav-link" href="<?php echo site_url('user/sshkey');?>">
								<i class="fas fa-fw fa-key"></i>
								<span>SSH Key</span></a>
						</li>

						<li class="nav-item">
<?php 
$loggedin=$this->session->userdata('logged_in'); 
$client_id=$loggedin['client_id'];
$query=$this->db->query("select jotjpn_tickets.client_id,jotjpn_message.* from jotjpn_tickets join jotjpn_message on jotjpn_message.ticket_id=jotjpn_tickets.id where jotjpn_tickets.client_id='$client_id' and jotjpn_message.msg_read='0'");
$unread=$query->num_rows();
?>
						<a class="nav-link" href="<?php echo site_url('support');?>">
						<span><i class="fa fa-bell"></i>
<?php if($unread!='0'){ ?>
<span  class="blink" style="margin:-8px;border-radius:50%;padding:2px; font-size:10px;background-color:#ff3030;color:#fff;width:18px; height:18px;z-index:2;position: absolute;"><?php echo $unread;?></span>&nbsp;&nbsp;&nbsp;&nbsp; <?php } ?> Support
						</span>
                           				 </a>						  
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo site_url('user/backups');?>">
								<i class="fa fa-upload"></i>
								<span>Backups</span></a>
						</li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Account
            </div>
			 
						<li class="nav-item">
							<a class="nav-link" href="<?php echo site_url('user/transactions');?>">
								<i class="fas fa-fw fa-money-check-alt"></i>
								<span>Transactions</span></a>
						</li>

						<li class="nav-item">
						<?php $loggedin=$this->session->userdata('logged_in');
						?>
							<a class="nav-link" href="<?php echo site_url('user/profile/edit/'.$loggedin['client_id']);?>">
								<i class="fas fa-fw fa-user"></i>
								<span>Profile</span></a>
						</li>

						 
						
						<li class="nav-item">
							<a class="nav-link" href="<?php echo site_url('login/logout');?>">
								<i class="fas fa-fw fa-sign-out-alt"></i>
								<span>Logout</span></a>
						</li>


  
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

             

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
 
                       <?php 
					   if($this->session->userdata('logged_in_admin')){
						?>
						<li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle"  href="<?php echo site_url('admin/dashboard');?>">Back to admin panel</a>
						</li>
					   <?php } ?>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Logged in as <?php $loggedin=$this->session->userdata('logged_in'); echo $loggedin['email']; ?></span>
								   
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <!-- <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
								 <div class="dropdown-divider"></div>
								-->
                               
                                
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
