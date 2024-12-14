
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
<h5 class="font-20 mt-15 mb-1"><?php if($id<0){echo "Save";}else { echo "Update";} echo " "; echo str_replace('_',' ','Transfer'); ?></h5>
<!--Form to save data-->
<form method="post" action="<?php echo site_url('member-transfer?&cmd=save&id='.$transfer[0]->id); ?>" enctype="multipart/form-data">
   <div class="row"> 
      <div class="col">
          <div class="form-group"> 
          <label for="amount" class="col-md-8 control-label">Amount</label> 
          <div class="col-md-8"> 
           <input type="text" name="amount" value="<?php echo ($_POST['amount'] ? $_POST['amount'] : $transfer[0]->amount); ?>" class="form-control" id="transfer" /> 
          </div> 
           </div>
           
           <div class="form-group"> 
          <label for="acc_number" class="col-md-8 control-label">Acc name</label> 
          <div class="col-md-8"> 
           <input type="text" name="acc_name" value="<?php echo ($_POST['acc_name'] ? $_POST['acc_name'] : $transfer[0]->acc_name); ?>" class="form-control" id="transfer" /> 
          </div> 
           </div>
   
          <div class="form-group"> 
          <label for="acc_number" class="col-md-8 control-label">Acc number</label> 
          <div class="col-md-8"> 
           <input type="text" name="acc_number" value="<?php echo ($_POST['acc_number'] ? $_POST['acc_number'] : $transfer[0]->acc_number); ?>" class="form-control" id="transfer" /> 
          </div> 
           </div>
   
		   <div class="form-group"> 
          <label for="bank_name" class="col-md-8 control-label">Bank name</label> 
          <div class="col-md-8"> 
           <input type="text" name="bank_name" value="<?php echo ($_POST['bank_name'] ? $_POST['bank_name'] : $transfer[0]->bank_name); ?>" class="form-control" id="transfer" /> 
          </div> 
           </div>
           
            <div class="form-group"> 
          <label for="bank_country" class="col-md-8 control-label">Bank country</label> 
          <div class="col-md-8"> 
           <input type="text" name="bank_country" value="<?php echo ($_POST['bank_country'] ? $_POST['bank_country'] : $transfer[0]->bank_country); ?>" class="form-control" id="transfer" /> 
          </div> 
           </div>
           
            <div class="form-group"> 
          <label for="bank_address" class="col-md-8 control-label">Bank address</label> 
          <div class="col-md-8"> 
           <input type="text" name="bank_address" value="<?php echo ($_POST['bank_address'] ? $_POST['bank_address'] : $transfer[0]->bank_address); ?>" class="form-control" id="transfer" /> 
          </div> 
           </div>
           
            <div class="form-group"> 
          <label for="bank_swift_code" class="col-md-8 control-label">Bank swift code</label> 
          <div class="col-md-8"> 
           <input type="text" name="bank_swift_code" value="<?php echo ($_POST['bank_swift_code'] ? $_POST['bank_swift_code'] : $transfer[0]->bank_swift_code); ?>" class="form-control" id="transfer" /> 
          </div> 
           </div>
           
            <div class="form-group"> 
          <label for="bank_routing_number" class="col-md-8 control-label">Bank routing number</label> 
          <div class="col-md-8"> 
           <input type="text" name="bank_routing_number" value="<?php echo ($_POST['bank_routing_number'] ? $_POST['bank_routing_number'] : $transfer[0]->bank_routing_number); ?>" class="form-control" id="transfer" /> 
          </div> 
           </div>
           
           <div class="form-group"> 
          <label for="bank_routing_number" class="col-md-8 control-label">Transfer type</label> 
          <div class="col-md-8"> 
            <select name="transfer_type"  id="transfer_type"  class="form-control"/> 
             <option value="">--Select--</option> 
              <option value="deposit" <?php if($transfer[0]->transfer_type=='deposit'){ echo "selected";} ?>>Deposit</option> 
              <option value="withdraw" <?php if($transfer[0]->transfer_type=='withdraw'){ echo "selected";} ?>>Withdraw</option> 
            </select> 
          </div> 
           </div>
           
            <div class="form-group"> 
          <label for="document_file" class="col-md-8 control-label">Attached document file(jpg,png-picture)</label> 
          <div class="col-md-8"> 
           <input type="file" name="document_file" value="" class="form-control" id="document_file" /> 
          </div> 
           </div>
           
       </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-4 col-sm-8">
        <input type="hidden" name="id" value="<?=$transfer[0]->id?>" >
        <button type="submit" class="btn btn-success"><?php if(empty($transfer[0]->id)){?>Save<?php }else{?>Update<?php } ?></button>
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