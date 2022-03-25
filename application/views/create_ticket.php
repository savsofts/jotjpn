<div class="container">
<div class="row">
<div class="col-lg-2">

</div>
<div class="col-lg-8">
  
<div class="card">
  <div class="card-header">
  Create New Support Ticket <a href="<?php echo site_url('support');?>" class="btn btn-primary btn-sm" style="float:right;">Back</a>
  </div>
  <div class="card-body" style="font-size:13px;">

  </div> 
</div>
 <div class="card" style="margin-top:5px;">
<div class="card-body">
<form method="post" action="<?php echo site_url('support/insert_ticket/');?>" enctype="multipart/form-data">
<div class="form-group">
<label>Title</label>
<input type="text" class="form-control" name="title" required autocomplete="off">
</div>
<div class="form-group">
<label>Select Server</label>
<select class="form-control" name="resource_id" required>
<option value="" hidden>---Please Select Server---</option>
<?php 
foreach($servers as $key => $val){
?>
<option value="<?php echo $val['id'];?>"><?php echo $val['server_name']; ?></option>
<option value="0">Other</option>
<input type="text" name="client_id" value="<?php echo $val['client_id']; ?>" hidden>
<input type="text" name="resource_type" value="<?php echo $val['server_name']; ?>" hidden>
<?php 
}
?>
</select>
</div>
<div class="form-group">
<label>Message</label>
<textarea class="form-control" name="msg" required ></textarea>
</div>
<div class="form-group">
<label>Attachment (optional)</label><br>
<i class="fa fa-paperclip" style="font-size:20px;" onclick="myFunction()"></i>
<input type="file" id="screenshot" class="form-control" accept="image/jpg" style="display:none;">
<p id="b64" hidden></p>
<input class="form-control" type="text" name="screenshot" id="texted" hidden/>
<img id="img" height="300" style="display:none;">
</div>
<div class="form-group">
<button class="btn btn-primary">Submit</button>
</div>

</form>

</div>
</div>
  
</div>
<div class="col-lg-3">

</div>
</div>
</div>

<script>

function readFile() {

  if (this.files && this.files[0]) {
    
    var FR= new FileReader();
    
    FR.addEventListener("load", function(e) {
      document.getElementById("img").src= e.target.result;
      document.getElementById("b64").innerHTML = e.target.result;
	  var text = $('#b64').html();
	  $('#texted').val(text);
	  $('#img').show();
    }); 
    
    FR.readAsDataURL( this.files[0] );
  }
  
}

document.getElementById("screenshot").addEventListener("change", readFile);

function myFunction() {
  var x = document.getElementById("screenshot");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
} 
</script>
