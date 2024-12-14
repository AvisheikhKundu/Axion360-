 <link 
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" 
  rel="stylesheet"  type='text/css'>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<h5 class="font-20 mt-15 mb-1">Account</h5>
   
<?php
 error_reporting(!E_WARNING);
?>
<!--Action-->
<div>
	<div class="float_left padding_10">
		<a href="member-account?page=account&cmd=edit"
			class="btn btn-success">Add</a>
	</div>
	
	
</div>
<!--End of Action//--> 
   
<!--Data display of account-->     
<div style="overflow-x:auto;width:100%;">      
<table cellspacing="3" cellpadding="3" class="table table-striped table-bordered">
    <tr>
        <th>Memeber</th>
		<th>Account name</th>
        <th>Account number</th>
        <th>Picture</th>
        <th>Acc status</th>
		<th>Actions</th>
    </tr>
	<?php foreach($account as $c){ ?>
    <tr>
         <td><?php 
		   $blogusers = get_userdata( $c->users_id);
		 ?>
		 <?=$blogusers->first_name?> <?=$blogusers->last_name?> <?=$blogusers->user_email?>
         </td>
        <td><?php echo $c->account_name; ?></td>
		<td><?php echo $c->account_number; ?></td>
         <td><a href="<?php echo $c->file_picture; ?>" target="_blank"><img src="<?php echo $c->file_picture; ?>" style="width:100px;height:100px;"></a></td>

        <td><?php echo $c->acc_status; ?></td>
		<td>
            <a href="<?php echo site_url('member-account?&cmd=edit&id='.$c->id); ?>" class="action-icon"> Edit</a>
            <a href="<?php echo site_url('member-account?&cmd=delete&id='.$c->id); ?>" onClick="return confirm('Are you sure to delete this item?');" class="action-icon"> Delete</a>
         </td>
    </tr>
	<?php } ?>
</table>
</div>
<!--End of Data display of account//--> 

<!--No data-->
<?php
	if(count($account)==0){
?>
 <div align="center"><h3>Data does not exists</h3></div>
<?php
	}
?>
<!--End of No data//-->

<!--Pagination-->
<!--End of Pagination//-->