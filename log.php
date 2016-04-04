<?php

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$file = $request->response;

$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($file, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);
	
truncate();
foreach ($jsonIterator as $key => $val) {
	
    if(is_array($val)) {

    } else {
		if($key == 'dateTime'){
			insert($val, "day", -1);
		} else if ($key == 'value'){
			$val = (int)$val;
			$count = getID();
			insert($val, "steps", $count);
		}

    }
}

function getID(){

	$count = 0;
	$conn = connect();

	$sql = "SELECT id FROM log order by id desc limit 1";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
			$count = $row["id"];
	    }
	} else {
	    echo "0 results";
	}
	$conn->close();
	return $count;
}

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

function insert($x1, $col, $id){
	$conn = connect();
	
	if($id==-1){	
		$query = ("INSERT INTO log ($col) VALUES('$x1')");
		if($conn->query($query)==TRUE){
			echo "success\n";
		} else {
			echo "error" . $sql . "<br>" .$conn->error;
		}	
	}else{
		$query = ("UPDATE log set $col=$x1 where ID = $id");
		if($conn->query($query)==TRUE){
			echo "success\n";
		} else {
			echo "error" . $sql . "<br>" .$conn->error;
		}	
	}
	$conn->close();
}

function truncate($user){
	$conn = connect();
	$query = ("truncate log");
	if($conn->query($query)==TRUE){
		echo "success\n";
	} else {
		echo "error" . $sql . "<br>" .$conn->error;
	}
	
	$conn->close();
}

?>