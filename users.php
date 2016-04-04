<?php

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$data = $request->response;
$column = $request->column;
$id = $request->id;

insert($id, $data, $column);

function connect(){
	
$servername = "localhost";
$username = "agile2";
$password = "WUwbw43nTp2SyCNa";
$dbname = "agile2";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	echo "Connected successfully";
	
		return $conn;
}

function insert($id, $data, $col){
	$conn = connect();

	$query = ("UPDATE users set $col='$data' where fbid = '$id'");
	if($conn->query($query)==TRUE){
		echo "success\n";
	} else {
		echo "error" . $sql . "<br>" .$conn->error;
	}
	
	$conn->close();
}
	
?>