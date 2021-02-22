<?php
if (isset($_POST['username']) && $_POST['username'] && isset($_POST['password']) && $_POST['password']) {

	// Credenciales actuales
	if(($_POST['username'] == 'root') && ($_POST['password'] == 'root')){
    	echo json_encode(array('success' => 1));
	}
	else{
		echo json_encode(array('success' => 0));
	}
} else {
    echo json_encode(array('success' => 0));
}