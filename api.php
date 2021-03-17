<?php 

 $con = new mysqli("localhost","root","","phpcrud");
 
 $response["error"]=false;
 $action = "read";

 if (isset($_GET['action'])) {
 	$action = $_GET['action'];
 }
 if ($action=="read") {
 	$users = array();
 	$result = $con->query("select * from users"); 
 	while ($row = $result->fetch_assoc() ) {
 	    array_push($users, $row);
 	}
 	$response['users'] = $users;
 }elseif($action=="create"){
 	$name = $_POST['name'];
 	$contact = $_POST['contact'];
 	$email = $_POST['email'];
 	$sql = "insert into users(name,contact,email) values('$name','$contact','$email')";
 	
 	$result = $con->query($sql);
 	if ($result) {
 		$response['message']="Data Save Successfully";
 	}else{
 		$response["error"]=true;
 		$response['message']="Data Save Failed";
 	}
 }elseif($action=="update"){
 	$id = $_POST['id'];
 	$name = $_POST['name'];
 	$contact = $_POST['contact'];
 	$email = $_POST['email'];
 	$sql = "update  users set name='$name',contact='$contact',email='$email' where id='$id' ";
 	$result = $con->query($sql);
 	if ($result) {
 		$response['message']="Data Update Successfully";
 	}else{
 		$response["error"]=true;
 		$response['message']="Data Update Failed";
 	}
 }elseif($action=="delete"){
 	$id = $_POST['id'];
 
 	$sql = "delete from  users where id='$id' ";
 	$result = $con->query($sql);
 	if ($result) {
 		$response['message']="Data Delete Successfully";
 	}else{
 		$response["error"]=true;
 		$response['message']="Data Delete Failed";
 	}
 }else{
 	die("Invalid Action");
 }
header("content-type: application/json");
echo json_encode($response);