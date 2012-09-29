<?php
session_start();

$perm_hash = $_POST['perm_hash'];

//this cookie expires in 1 month
$expire=time()+60*60*24*30;

setcookie("cpp-$perm_hash", $_POST['email_password'], $expire, '/');

if($_POST['email_password'] == $_SESSION[$_POST['perm_hash']]['password']){
	if(!isset($_COOKIE["cpp-global-password"]))
		setcookie("cpp-global-password", $_SESSION['global_password'], $expire, '/');
	
	echo json_encode(array('ecpp_auth' => 1, 'ecpp_status' => 1));
}else
	echo json_encode(array('ecpp_auth' => 0, 'ecpp_status' => 1));
?>
