 
 <link 
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" 
  rel="stylesheet"  type='text/css'>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<?php
   global $wpdb;
?>
<h5 class="font-20 mt-15 mb-1">Dashboard</h5>
<div class="row">
    <div class="col">
    <div class="card" style="width: 18rem;">
      <i class="bi bi-users"></i>
      <div class="card-body">
        <h5 class="card-title">Members</h5>
        <p class="card-text"><?php
           $result = count_users();
		   echo $result['total_users'];
		?></p>
        <a href="users.php" class="btn btn-primary">Users</a>
      </div>
    </div>
    </div>
    <div class="col">
    <div class="card" style="width: 18rem;">
      <i class="bi bi-currency-dollar"></i>
      <div class="card-body">
        <h5 class="card-title">Balance</h5>
        <p class="card-text">
           <?php
		      $transfer  = $wpdb->get_results("select SUM(credit-debit) as total from wp_transaction"); 
		      echo $transfer[0]->total;
           ?>
        </p>
        <a href="admin.php?page=transaction" class="btn btn-primary">Balance</a>
      </div>
    </div>
    </div>  
    <div class="col">
    <div class="card" style="width: 18rem;">
      <i class="bi bi-currency-dollar"></i>
      <div class="card-body">
        <h5 class="card-title">Credit</h5>
        <p class="card-text">
        <?php
		      $credit  = $wpdb->get_results("select SUM(credit) as total from wp_transaction"); 
		      echo $credit[0]->total;
           ?>
        </p>
        <a href="admin.php?page=transaction" class="btn btn-primary">Credit</a>
      </div>
    </div>
    </div>
    <div class="col">
    <div class="card" style="width: 18rem;">
      <i class="bi bi-currency-dollar"></i>
      <div class="card-body">
        <h5 class="card-title">Debit</h5>
        <p class="card-text">
        <?php
		      $debit  = $wpdb->get_results("select SUM(debit) as total from wp_transaction"); 
		      echo $debit[0]->total;
           ?>
        </p>
        <a href="admin.php?page=transaction" class="btn btn-primary">Debit</a>
      </div>
    </div>
    </div>
    <div class="col">
    <div class="card" style="width: 18rem;">
      <div class="card-body">
        <h5 class="card-title">Transfer</h5>
        <p class="card-text"><?php
		      $transfer  = $wpdb->get_results("select count(*) as total  from wp_transfer"); 
		      echo $transfer[0]->total;
           ?></p>
        <a href="admin.php?page=transfer" class="btn btn-primary">Transfer</a>
      </div>
    </div>
    </div>
    <div class="col">   
    <div class="card" style="width: 18rem;">
      <div class="card-body">
        <h5 class="card-title">Transactions</h5>
        <p class="card-text"><?php
		      $transaction  = $wpdb->get_results("select count(*) as total  from wp_transaction"); 
		      echo $transaction[0]->total;
           ?></p>
        <a href="admin.php?page=transaction" class="btn btn-primary">Transaction</a>
      </div>
    </div>
    </div>
</div>

<br><br>
 <a href="https://www.paypal.com/paypalme/patacorporation">Donate</a>
