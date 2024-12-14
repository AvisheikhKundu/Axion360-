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
			                'users_id' =>  get_current_user_id(),
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
							'transfer_status' => 'pending',
							'created_at' =>$created_at,
							'updated_at' =>$updated_at,
							
							);
							
			$uploads_dir = ABSPATH.'/wp-content/uploads/document_file';
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
					
			}
			if($id>0){
			
			 $res = $wpdb->update('wp_transfer',$params,array('id'=>$_REQUEST['id']));
			 
			}
			 ob_start();
             ob_end_flush();
			 echo "<script>";
			  echo "window.location.href = 'member-transfer';";
			 echo "</script>";
	      break;
	case "delete":  
	      $wpdb->delete('wp_transfer',array('id'=>$_REQUEST['id'],'users_id'=>get_current_user_id()));
		   ob_start();
           ob_end_flush();
		  //wp_redirect( 'member.php?page=transfer' );
		   echo "<script>";
			  echo "window.location.href = 'member-transfer';";
			 echo "</script>";
	      break;  
    case "edit":
	         if(!empty($_REQUEST['id'])){
		     	$transfer  = $wpdb->get_results("select * from wp_transfer where id='".$_REQUEST['id']."'  AND users_id='".get_current_user_id()."'"); 
			 }
			 include(dirname(__FILE__) . '/template/member/transfer/form.php');  
		  break;		  
	default:
	   $transfer  = $wpdb->get_results("select * from wp_transfer WHERE   users_id='".get_current_user_id()."'  ORDER BY id DESC"); 
	   include(dirname(__FILE__) . '/template/member/transfer/index.php');  
  }
?>