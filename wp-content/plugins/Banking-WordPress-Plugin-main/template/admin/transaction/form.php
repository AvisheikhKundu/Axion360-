 <link 
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" 
  rel="stylesheet"  type='text/css'>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<?php
 error_reporting(!E_WARNING);
?>
<a  href="<?php echo 'admin.php?page=transaction'; ?>" class="btn btn-info"><i class="arrow_left"></i> List</a>
<h5 class="font-20 mt-15 mb-1"><?php if($id<0){echo "Save";}else { echo "Update";} echo " "; echo str_replace('_',' ','Wp_transaction'); ?></h5>
<!--Form to save data-->
<form method="post" action="admin.php?page=transaction&cmd=save&id=<?=$transaction[0]->id?>" enctype="multipart/form-data">
<div class="card">
   <div class="card-body">    
          
         <?php
		   $blogusers = get_users( );//array( 'role__in' => array( 'author', 'subscriber' ) ) );
		 ?>
         <div class="form-group"> 
         <label for="users_id" class="col-md-8 control-label">Member</label> 
          <div class="col-md-8"> 
            <select name="users_id"  id="users_id"  class="form-control"/> 
             <option value="">--Select--</option> 
              <?php
			    foreach ( $blogusers as $user ) {
			  ?>
              <option value="<?=$user->ID?>" <?php if($transaction[0]->users_id==$user->ID){ echo "selected";} ?>><?=$user->first_name?> <?=$user->last_name?> <?=$user->user_email?></option> 
              <?php
				}
			  ?>	
            </select> 
          </div> 
          </div>
          <div class="form-group"> 
          <label for="Media name" class="col-md-4 control-label">transfer_id</label> 
          <div class="col-md-8"> 
			<?php
              $transfer  = $wpdb->get_results("select * from wp_transfer where 1  order BY id DESC"); 
            ?>
           <select name="transfer_id">
			<?php
            for($i=0;$i<count($transfer);$i++){
            ?>
            <option value="<?=$transfer[$i]->id?>" <?php if(isset($_POST['transfer_id'])&& $_POST['transfer_id'] ==$transfer[$i]->id){ echo "selected";}?>><?=$transfer[$i]->acc_number?>-<?=$transfer[$i]->amount?></option>
             <?php
             }
            ?>  
            </select> 
          </div> 
           </div>
           
           <div class="form-group"> 
          <label for="Media name" class="col-md-4 control-label">debit</label> 
          <div class="col-md-8"> 
           <input type="text" name="debit" value="<?php echo ($_POST['debit'] ? $_POST['debit'] : $transaction[0]->debit); ?>" class="form-control" id="debit" /> 
          </div> 
           </div>
           
           <div class="form-group"> 
          <label for="Media name" class="col-md-4 control-label">credit</label> 
          <div class="col-md-8"> 
           <input type="text" name="credit" value="<?php echo ($_POST['credit'] ? $_POST['credit'] : $transaction[0]->credit); ?>" class="form-control" id="credit" /> 
          </div> 
           </div>
        
         
   </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-4 col-sm-8">
        <input type="hidden" name="id" value="<?=$transaction[0]->id?>" >
        <button type="submit" class="btn btn-success"><?php if(empty($transaction[0]->id)){?>Save<?php }else{?>Update<?php } ?></button>
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