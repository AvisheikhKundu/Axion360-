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
							'account_name' => $_REQUEST['account_name'],
							'account_number' => $_REQUEST['account_number'],
							//'file_picture' => $_REQUEST['file_picture'],
							'acc_status' => $_REQUEST['acc_status'],
							'created_at' =>$created_at,
							'updated_at' =>$updated_at,
							
							);
							
			$uploads_dir = get_home_path().'/wp-content/uploads/file_picture';
			if(!is_dir($uploads_dir)){
			  mkdir($uploads_dir); 
			}
			
				if ($_FILES["file_picture"]["error"]==UPLOAD_ERR_OK) {
					$tmp_name = $_FILES["file_picture"]["tmp_name"];
					// basename() may prevent filesystem traversal attacks;
					// further validation/sanitation of the filename may be appropriate
					$name = basename($_FILES["file_picture"]["name"]);
					move_uploaded_file($tmp_name, "$uploads_dir/$name");
					$params['file_picture'] = get_site_url()."/wp-content/uploads/file_picture/$name";
					
				}		
			 
			if($id>0){
			unset($params['created_at']);
			}if($id<=0){
			unset($params['updated_at']);
			} 
			if($id<=0){
					$res= $wpdb->insert('wp_account',$params);
			}
			if($id>0){
			
			 $res = $wpdb->update('wp_account',$params,array('id'=>$_REQUEST['id']));
			}
			 ob_start();
             ob_end_flush();
			 echo "<script>";
			  echo "window.location.href = 'admin.php?page=account';";
			 echo "</script>";
	      break;
	case "delete":  
	      $wpdb->delete('wp_account',array('id'=>$_REQUEST['id']));
		   ob_start();
           ob_end_flush();
		  //wp_redirect( 'admin.php?page=account' );
		   echo "<script>";
			  echo "window.location.href = 'admin.php?page=account';";
			 echo "</script>";
	      break;  
    case "edit":
	         if(!empty($_REQUEST['id'])){
		     	$account  = $wpdb->get_results("select * from wp_account where id='".$_REQUEST['id']."'"); 
			 }
			 include(dirname(__FILE__) . '/template/admin/account/form.php');  
		  break;		  
	default:
	   $account  = $wpdb->get_results("select * from wp_account   ORDER BY id DESC"); 
	   include(dirname(__FILE__) . '/template/admin/account/index.php');  
  }
?>