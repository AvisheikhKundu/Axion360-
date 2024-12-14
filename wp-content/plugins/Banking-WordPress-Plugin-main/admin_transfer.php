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
			                'users_id' => $_REQUEST['users_id'],
							'amount' => $_REQUEST['amount'],
							'acc_name' => $_REQUEST['acc_name'],
							'acc_number' => $_REQUEST['acc_number'],
							'bank_name' => $_REQUEST['bank_name'],
							'bank_country' => $_REQUEST['bank_country'],
							'bank_address' => $_REQUEST['bank_address'],
							'bank_swift_code' => $_REQUEST['bank_swift_code'],
							'bank_routing_number' => $_REQUEST['bank_routing_number'],
							//'document_file' => $_REQUEST['greeting'],
							'transfer_type' => $_REQUEST['transfer_type'],
							'transfer_status' => $_REQUEST['transfer_status'],
							'created_at' =>$created_at,
							'updated_at' =>$updated_at,
							
							);
							
			$uploads_dir = get_home_path().'/wp-content/uploads/document_file';
			if(!is_dir($uploads_dir)){
			  mkdir($uploads_dir); 
			}
			
				if ($_FILES["document_file"]["error"]==UPLOAD_ERR_OK) {
					$tmp_name = $_FILES["document_file"]["tmp_name"];
					// basename() may prevent filesystem traversal attacks;
					// further validation/sanitation of the filename may be appropriate
					$name = basename($_FILES["document_file"]["name"]);
					move_uploaded_file($tmp_name, "$uploads_dir/$name");
					$params['document_file'] = get_site_url()."/wp-content/uploads/document_file/$name";
					
				}		
			 
			if($id>0){
			unset($params['created_at']);
			}if($id<=0){
			unset($params['updated_at']);
			} 
			if($id<=0){
					$res= $wpdb->insert('wp_transfer',$params);
					$insert_id = $wpdb->insert_id; 
					if($_REQUEST['transfer_status']=='completed'){
						    $debit =0;
							$cerdit =0;
							if($_REQUEST['transfer_type']=='deposit'){
								$cerdit =$_REQUEST['amount'];
							}
							else{
								$debit =$_REQUEST['amount'];
							}
							$params = array(
							                'users_id' => $_REQUEST['users_id'],
											'transfer_id' => $insert_id,
											'debit' => $debit,
											'credit'  => $cerdit,
											'created_at' =>$created_at,
											'updated_at' =>$updated_at,
											);
							
							$res = $wpdb->insert('wp_transaction',$params);
							
					}
			}
			if($id>0){
			
			 $res = $wpdb->update('wp_transfer',$params,array('id'=>$_REQUEST['id']));
			       if($_REQUEST['transfer_status']=='completed'){
					        $debit =0;
							$cerdit =0;
							if($_REQUEST['transfer_type']=='deposit'){
								$cerdit =$_REQUEST['amount'];
							}
							else{
								$debit =$_REQUEST['amount'];
							}
							$params = array(
							                'users_id' => $_REQUEST['users_id'],
											'transfer_id' =>$_REQUEST['id'],
											'debit' => $debit,
											'credit'  => $cerdit,
											'created_at' =>$created_at,
											'updated_at' =>$updated_at,
											);
							
							$res =  $wpdb->insert('wp_transaction',$params);
							
					}
			 
			 
			}
			 ob_start();
             ob_end_flush();
			 echo "<script>";
			  echo "window.location.href = 'admin.php?page=transfer';";
			 echo "</script>";
	      break;
	case "delete":  
	      $wpdb->delete('wp_transfer',array('id'=>$_REQUEST['id']));
		   ob_start();
           ob_end_flush();
		  //wp_redirect( 'admin.php?page=transfer' );
		   echo "<script>";
			  echo "window.location.href = 'admin.php?page=transfer';";
			 echo "</script>";
	      break;  
    case "edit":
	         if(!empty($_REQUEST['id'])){
		     	$transfer  = $wpdb->get_results("select * from wp_transfer where id='".$_REQUEST['id']."'"); 
			 }
			 include(dirname(__FILE__) . '/template/admin/transfer/form.php');  
		  break;		  
	default:
	   $transfer  = $wpdb->get_results("select * from wp_transfer   ORDER BY id DESC"); 
	   include(dirname(__FILE__) . '/template/admin/transfer/index.php');  
  }
?>