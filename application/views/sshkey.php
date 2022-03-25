 
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">SSH Key(s)</h1>
		
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
 How to create SSH Key?
</button>

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
 




<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">How to create ssh key?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Follow these instructions to create or add SSH keys on Linux, MacOS & Windows. Windows users required OpenSSH (Usually windows 10+ has pre-installed)</p>
		<p>
		1) Open a terminal and run the following command:<br>
		<code>ssh-keygen</code>
		</p>
		<p>
		2) You will be prompted to save and name the key.<br>
		<code>Generating public/private rsa key pair. Enter file in which to save the key (/Users/USER/.ssh/id_rsa):</code>
		</p>


		<p>
		3) Next you will be asked to create and confirm a passphrase for the key (highly recommended):<br>
		<code>Enter passphrase (empty for no passphrase):
Enter same passphrase again:Enter passphrase (empty for no passphrase):
Enter same passphrase again:</code>
		</p>



		<p>
		4) This will generate two files, by default called id_rsa and id_rsa.pub. Next, add this public key.<br>
		</p>

		<p>
		5) Copy and paste the contents of the .pub file, typically id_rsa.pub, into the "Add SSH Key -> Public Key Field".<br>
		For linux: <code>cat ~/.ssh/id_rsa.pub</code><br>
		For windows: <code>notepad FILENAME.pub</code><br>Note: here FILENAME is the name you entered in 1st step
		</p>

		 


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>