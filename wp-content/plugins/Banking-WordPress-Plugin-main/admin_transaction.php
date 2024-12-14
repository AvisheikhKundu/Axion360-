<?php

  
  global $wpdb;
  $cmd='';
  $id = '';
  $cmd = isset($_REQUEST['cmd'])?$_REQUEST['cmd']:'';
  $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
  
  switch($cmd){
	case "save":
	         $created_at = "";
			 $updated_at = "";

			if($id<=0){
				 $created_at = date("Y-m-d H:i:s");
			 }
			else if($id>0){
				 $updated_at = date("Y-m-d H:i:s");
			 }

			$params = array(
			                'users_id'  => $_REQUEST['users_id'],
			                'transfer_id' => $_REQUEST['transfer_id'],
			                'debit' => $_REQUEST['debit'],
							'credit'  => $_REQUEST['credit'],
							'created_at' =>$created_at,
							'updated_at' =>$updated_at,
							
							);
			
			 
			if($id>0){
			unset($params['created_at']);
			}if($id<=0){
			unset($params['updated_at']);
			} 
			if($id<=0){
			$res = $wpdb->insert('wp_transaction',$params);
			}
			if($id>0){
			
			 $res = $wpdb->update('wp_transaction',$params,array('id'=>$_REQUEST['id']));
			 
			}
			 ob_start();
             ob_end_flush();
			 echo "<script>";
			  echo "window.location.href = 'admin.php?page=transaction';";
			 echo "</script>";
	      break;
	case "delete":  
	      $wpdb->delete('wp_transaction',array('id'=>$_REQUEST['id']));
		   ob_start();
           ob_end_flush();
		  //wp_redirect( 'admin.php?page=transaction' );
		   echo "<script>";
			  echo "window.location.href = 'admin.php?page=transaction';";
			 echo "</script>";
	      break;  
    case "edit":
	         if(!empty($_REQUEST['id'])){
		     	$transaction  = $wpdb->get_results("select * from wp_transaction where id='".$_REQUEST['id']."'"); 
			 }
			 include(dirname(__FILE__) . '/template/admin/transaction/form.php');  
		  break;		  
	default:
	   $transaction  = $wpdb->get_results("select * from wp_transaction  ORDER BY id DESC"); 
	   include(dirname(__FILE__) . '/template/admin/transaction/index.php');  
  }
?>