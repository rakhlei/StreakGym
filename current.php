<?php
//replaces current user id in the table 'current'
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = $request->id;
	
$servername = "localhost";
$username = "agile2";
$password = "WUwbw43nTp2SyCNa";
$dbname = "agile2";
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "update current set fbid = '$id' where id=1";
	$result = $conn->query($sql);
	

$sql = "SELECT id FROM users where fbid = '$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

} else {
			
    $sql = "insert into users (fbid, total, count, points) values ('$id',0,1,0)";
	$result = $conn->query($sql);
}
$conn->close();
?>