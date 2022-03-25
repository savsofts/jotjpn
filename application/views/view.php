<div class="container">
<div class="row">
<div class="col-lg-2">

</div>
<div class="col-lg-8">

<div class="card">
  <div class="card-header">
    <b style="font-size:16px;"><?php echo $result['title'];?></b> <a href="<?php echo site_url('support');?>" class="btn btn-primary btn-sm" style="float:right;">Back</a><br>
 <b style="font-size:13px;"><?php echo date('d-m-Y h:i:sa',$result['ticket_time']);?> by <?php echo $result['full_name'];?></b>
  </div>
  <div class="card-body" style="font-size:13px;">

  </div> 
</div><br>
<?php foreach($result1 as $k => $row){ ?>
<div class="card"  style="margin-bottom:10px;">
  <div class="card-header">
 <h6 style="font-size:12px;"><?php echo date('d-m-Y h:i:sa',$row['msg_time']);?> by <?php echo $result['full_name'];?></h6>
    <span style="font-size:16px;"><?php echo $row['msg'];?></span><br>
<?php if($row['screenshot']!='') { ?>
<img src="<?php echo ($row['screenshot']);?>" style="height:250px;"><br> 
<?php } ?>

  </div>
</div>
<?php } if($result['ticket_status']=='Open'){ ?>
<div class="card" style="margin-top:5px;">
<div class="card-body">
<form method="post" action="<?php echo site_url('support/update_ticket/'.$id);?>" enctype="multipart/form-data">
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
<?php } ?>  
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
