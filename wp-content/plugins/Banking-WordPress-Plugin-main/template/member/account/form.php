
 <link 
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" 
  rel="stylesheet"  type='text/css'>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<?php
 error_reporting(!E_WARNING);
?>
<a  href="<?php echo site_url('member-account'); ?>" class="btn btn-info"><i class="arrow_left"></i> List</a>
<h5 class="font-20 mt-15 mb-1"><?php if($id<0){echo "Save";}else { echo "Update";} echo " "; echo str_replace('_',' ','Account'); ?></h5>
<!--Form to save data-->
<form method="post" action="<?php echo site_url('member-account?&cmd=save&id='.$account[0]->id); ?>" enctype="multipart/form-data">
   <div class="row"> 
      <div class="col">
          
           <div class="form-group"> 
          <label for="account_name" class="col-md-8 control-label">account name</label> 
          <div class="col-md-8"> 
           <input type="text" name="account_name" value="<?php echo ($_POST['account_name'] ? $_POST['account_name'] : $account[0]->account_name); ?>" class="form-control" id="account_name" /> 
          </div> 
           </div>
   
          <div class="form-group"> 
          <label for="account_number" class="col-md-8 control-label">Account number</label> 
          <div class="col-md-8"> 
           <input type="text" name="account_number" value="<?php echo ($_POST['account_number'] ? $_POST['account_number'] : $account[0]->account_number); ?>" class="form-control" id="account_number" /> 
          </div> 
           </div>
   
		   
           <div class="form-group"> 
          <label for="file_picture" class="col-md-8 control-label">Picture(jpg,png-picture)</label> 
          <div class="col-md-8"> 
           <input type="file" name="file_picture" value="" class="form-control" id="file_picture" /> 
          </div> 
           </div>
           
           <div class="form-group"> 
          <label for="bank_routing_number" class="col-md-8 control-label">acc status</label> 
          <div class="col-md-8"> 
            <select name="acc_status"  id="acc_status"  class="form-control"/> 
             <option value="">--Select--</option> 
              <option value="active" <?php if($account[0]->acc_status=='active'){ echo "selected";} ?>>active</option> 
              <option value="inactive" <?php if($account[0]->acc_status=='inactive'){ echo "selected";} ?>>inactive</option> 
            </select> 
          </div> 
           </div>
          
            
       </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-4 col-sm-8">
        <input type="hidden" name="id" value="<?=$account[0]->id?>" >
        <button type="submit" class="btn btn-success"><?php if(empty($account[0]->id)){?>Save<?php }else{?>Update<?php } ?></button>
    </div>
</div>
</form>
<!--End of Form to save data//-->	
<!--JQuery-->
<script>
	$( ".datepicker" ).datepicker({
		dateFormat: "yy-mm-dd", 
		changeYear: true,
		changeMonth: true,
		showOn: 'button',
		buttonText: 'Show Date',
		buttonImageOnly: true,
		buttonImage: '<?php echo base_url(); ?>public/datepicker/images/calendar.gif',
	});
</script>  			