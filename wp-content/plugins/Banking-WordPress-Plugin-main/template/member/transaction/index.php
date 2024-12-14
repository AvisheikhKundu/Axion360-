

 <link 
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" 
  rel="stylesheet"  type='text/css'>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<h5 class="font-20 mt-15 mb-1">Transaction</h5>
 
   
<?php
 error_reporting(!E_WARNING);
?>
<!--Data display of transaction-->     
<div style="overflow-x:auto;width:100%;">      
<table cellspacing="3" cellpadding="3" class="table table-striped table-bordered">
    <tr>
        <th>Member</th>
		<th>transfer info</th>
        <th>debit</th>
        <th>credit</th>
    </tr>
	<?php foreach($transaction as $c){ ?>
    <tr>
        <td><?php 
		   $blogusers = get_userdata( $c->users_id);
		 ?>
		 <?=$blogusers->first_name?> <?=$blogusers->last_name?> <?=$blogusers->user_email?>
        </td>
        <td><?php  
              $transfer  = $wpdb->get_results("select * from wp_transfer where id='".$c->transfer_id."'"); 
            ?>
		   <?=$transfer[0]->acc_number?>-<?=$transfer[0]->amount?>
        
        </td>
        <td><?php echo $c->debit; ?></td>
         <td><?php echo $c->credit; ?></td>
    </tr>
	<?php } ?>
</table>
</div>
<!--End of Data display of transaction//--> 

<!--No data-->
<?php
	if(count($transaction)==0){
?>
 <div align="center"><h3>Data does not exists</h3></div>
<?php
	}
?>
<!--End of No data//-->

<!--Pagination-->
<!--End of Pagination//-->

