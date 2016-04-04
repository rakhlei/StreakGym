<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Streak for the Gym</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<link rel="stylesheet" href="streak.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.27/angular.min.js"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.13/angular-ui-router.min.js"></script>
		<link rel="shortcut icon" type="image/x-icon" href="images/logoBrowser.png" />
	</head>

	<body data-spy="scroll" data-target=".navbar" data-offset="50">
		<div id="top" class="jumbotron text-center">
		<img src="images/logo.gif" alt="Streak for the Gym Logo" width="120" height="50">
			<h1>Streak for the Gym</h1>
			<p>Why Put Off Feeling Good?</p>
		</div>
	
<?php	

		$servername = "localhost";
		$username = "agile2";
		$password = "WUwbw43nTp2SyCNa";
		$dbname = "agile2";
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection to server database failed: " . $conn->connect_error);
		} 
		
		//GET CURRENT USERID
		$query = ("select fbid from current where id =1");
		$result = $conn->query($query);
		if($result==TRUE){
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
				$userid = $row["fbid"];
				}
			} else {
			echo "User not found";
			}
		} else {
			echo "error" . $query . "<br>" .$conn->error;
		}
		
		
		// TODAYS STEPS
		$query = ("select steps from log order by id desc limit 1");
		$result = $conn->query($query);
		if($result==TRUE){
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
				$todaysteps = $row["steps"];		
				}
			} else {
			echo "0 results";
			}
		} else {
			echo "error" . $sql . "<br>" .$conn->error;
		}
//----------------------------------------------------------------------
	// USER INFORMATION
	
	$query = ("select fullname from users where fbid='$userid'");
		$result = $conn->query($query);
		if($result==TRUE){
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$fullname = $row["fullname"];
				}
			} else {
				echo "0 results";
			}
		} else {
			echo "error" . $sql . "<br>" .$conn->error;
		}	
		
		$query = ("select aboutme from users where fbid='$userid'");
		$result = $conn->query($query);
		if($result==TRUE){
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$aboutme = $row["aboutme"];
				}
			} else {
				echo "0 results";
			}
		} else {
			echo "error" . $sql . "<br>" .$conn->error;
		}	
		
		$query = ("select birth from users where fbid='$userid'");
		$result = $conn->query($query);
		if($result==TRUE){
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$birth = $row["birth"];
				}
			} else {
				echo "0 results";
			}
		} else {
			echo "error" . $sql . "<br>" .$conn->error;
		}	
		
		function computeAge($starttime,$endtime)
		{
			$age = date("Y",$endtime) - date("Y",$starttime);
			if(date("z",$endtime) < date("z",$starttime)) $age--;
				return $age;
		}
		$born = strtotime($birth);
		$today = strtotime(date("Y-m-d"));
		$age = computeAge($born, $today);
	
		$query = ("select height from users where fbid='$userid'");
		$result = $conn->query($query);
		if($result==TRUE){
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$heightcm = $row["height"];
				}
			} else {
				echo "0 results";
			}
		} else {
			echo "error" . $sql . "<br>" .$conn->error;
		}	
		
		$height = $heightcm/100;
		
//----------------------------------------------------------------------		
	// Streak Caluculations
		// Tested helper methods
	
		// START DATE - FOR 1M
		$query = ("select day from log where id=1");
		$result = $conn->query($query);
		if($result==TRUE){
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
				$startdate = $row["day"];
				}
			} else {
			echo "0 results";
			}
		} else {
			echo "error" . $sql . "<br>" .$conn->error;
		}
		
		// SET START DATE
		function setStartDate($startdate, $conn, $userid){
			$que = ("update users set startdate='$startdate' where fbid='$userid'");
			$result = $conn->query($que);
			if($result==TRUE){
			} else {
				echo "error" . $que . "<br>" .$conn->error;
			}
		}
		
		// SET START DATE -- NOT EVERY TIME: FIX only if null
		// function testNull ($conn, $id){
		$query = ("SELECT startdate FROM users where fbid='$userid'");

			if($result=($conn->query($query))){
			if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
					$start = $row["startdate"];
					if ($start === NULL){
						setStartDate($startdate, $conn, $userid);
			}
					}
			
		}
		}
		
		
		//testNull($conn,$userid);

		// GET START DATE FROM DB
		function getStart($conn, $userid){
			$query = ("select startdate from users where fbid ='$userid'");
			$result = $conn->query($query);
			if($result==TRUE){
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
					$start = $row["startdate"];
					}
				} else {
					echo "0 results";
				}
			} else {
				echo "error" . $sql . "<br>" .$conn->error;
			}
			return $start;
		}

		// GET STEPS FOR A DAY
		function getSteps($date, $conn){
			$query = ("select steps from log where day='$date'");
			$result = $conn->query($query);
			if($result==TRUE){
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
					$steps = $row["steps"];
					}
				} else {
				echo "0 results";
				}
			} else {
				echo "error" . $sql . "<br>" .$conn->error;
			}
			return $steps;
		}
		
		// GETS TOTAL
		function getTotal($conn, $userid){
			$query = ("select total from users where fbid='$userid'");
			$result = $conn->query($query);
			if($result==TRUE){
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
					$total = $row["total"];
					}
				} else {
				echo "0 results";
				}
			} else {
				echo "error" . $sql . "<br>" .$conn->error;
			}
			return $total;
		}
		
		// GETS COUNT
		function getCount($conn, $userid){
			$query = ("select count from users where fbid ='$userid'");
			$result = $conn->query($query);
			if($result==TRUE){
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
					$count = $row["count"];
					}
				} else {
				echo "0 results";
				}
			} else {
				echo "error" . $sql . "<br>" .$conn->error;
			}
			return $count;
		}
		
				
		// SETS COUNT
		function setCount($conn, $userid, $count){
			$query = ("update users set count=$count where fbid ='$userid'");
			$result = $conn->query($query);
			if($result==TRUE){

			} else {
				echo "error" . $sql . "<br>" .$conn->error;
			}
		}
		
		// SETS TOTAL
		function setTotal($conn, $userid, $total){
			$query = ("update users set total=$total where fbid ='$userid'");
			$result = $conn->query($query);
			if($result==TRUE){

			} else {
				echo "error" . $sql . "<br>" .$conn->error;
			}
		}
		
		// GETS POINTS
		function getPoints($conn, $userid){
			$query = ("select points from users where fbid ='$userid'");
			$result = $conn->query($query);
			if($result==TRUE){
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
					$pts = $row["points"];
					}
				} else {
				echo "0 results";
				}
			} else {
				echo "error" . $sql . "<br>" .$conn->error;
			}
			return $pts;
		}
		
				
		// SETS POINTS
		function setPoints($conn, $userid, $points){
			$query = ("update users set points=$points where fbid ='$userid'");
			$result = $conn->query($query);
			if($result==TRUE){

			} else {
				echo "error" . $sql . "<br>" .$conn->error;
			}
		}
		
		
//-----------------------------------------------------------------------

	//------1 month alone-------
	
	// steak counter
	$count = getCount($conn, $userid);
	// streak total
	$total = getTotal($conn, $userid);
	// get start date
	$start = getStart($conn, $userid);
	// date = start date
	$date = $start;

	$todayts = strtotime(date("Y-m-d"));
	//$tommrts = strtotime("+1 day", $todayts);
	// this is the formatted date for tomorrow: date("Y-m-d", $tommrts);
	
	while(true){
		
		$datets = strtotime($date);
		
		// if date not equal tomorrow's date -- edit: todays date - day not completed
		if($datets != $todayts){
			
			// get day steps
			$steps = getSteps($date, $conn);
			
			if ($steps >= 7000){
				// add steps to streak total
				$total = $total + $steps;
				$count++;
				if ($count == 8){
					$count =1;
					pointConversion($conn, $total, $userid);
					$total =0;
				}
			}else{
				$count=1;
				$total=0;
			}		
		}else{
			// store streak counter and total and update start date
			setCount($conn, $userid, $count);
			setTotal($conn, $userid, $total);
			setStartDate($date, $conn, $userid);
			break;
		}
		$tmp = strtotime("+1 day", $datets);
		$date = date("Y-m-d", $tmp);
		
	}

		
	function pointConversion ($conn, $total, $id){
		$before = getPoints($conn, $id);
		$points = 0;
		if($total>=49000 && $total<=69999){	
			$points=10;	
		}else if ($total>=70000 && $total<=90999){
			$points=15;
		}else if ($total>=91000 && $total<=111999){
			$points=20;
		}else if ($total>=112000 && $total<=132999){
			$points=25;
		}else if ($total>=133000){
			$points=30;
		}
		$after = $before + $points;
		setPoints($conn, $id, $after);
	}
	
	$pointsEarned = getPoints($conn, $userid);
		
		
//-----------------------------------------------------------------------
	// Progress Bars
		$streak = $total;
	
		$bar1 = 0;
		$bar2 = 0;
		$bar3 = 0;
		$bar4 = 0;
		$bar5 = 0;
		$bar6 = 0;
	
		while(true){
			if($streak<=49000){
				$bar1=100*($streak/49000);
				break;
			}else{
				$bar1=100;
			}
			if($streak<=69000){
				$bar2=100*(($streak-49000)/20999);
				break;
			}else{
				$bar2=100;
			}
			if($streak<=90999){
				$bar3=100*(($streak-69999)/20999);
				break;
			}else{
				$bar3=100;
			}
			if($streak<=111999){
				$bar4=100*(($streak-90999)/20999);
				break;
			}else{
				$bar4=100;
			}
			if($streak<=132999){
				$bar5=100*(($streak-111999)/20999);
				break;
			}else{
				$bar5=100;
			}
			if($streak<=153999){
				$bar6=100*(($streak-132999)/20999);
				break;
			}else{
				$bar6=100;
				break;
			}
		}
		
		$one = "width:".number_format($bar1,0,'.','')."%";
		$two = "width:".number_format($bar2,0,'.','')."%";
		$three = "width:".number_format($bar3,0,'.','')."%";
		$four = "width:".number_format($bar4,0,'.','')."%";
		$five = "width:".number_format($bar5,0,'.','')."%";
		$six = "width:".number_format($bar6,0,'.','')."%";

//----------------------------------------------------------------------
		
		$conn->close();

	
?>

		<!-- ACTIVITY Section -->
		<div id="activity" class="container-fluid">
			<div class="row">
				<div class="col-md-4 push">
					<p class="medSize">Today's Steps</p>
					<p id="circleSide1"><?php echo number_format($todaysteps); ?> Steps</p>
				</div>
				<div class="col-md-4">
					<p class="medSize text-center">Number of Current Points</p>
					<div class="circle"><?php echo $pointsEarned; ?> Points</div>
				</div>
				<div class="col-md-4 push">
					<p class="medSize text-center">Streak Length</p>
					<p id="circleSide2"><?php echo $count?>/7 Days</p>
				</div>
			</div>
		</div>

		<!-- POINTS Section -->
		<div id="points" class="container-fluid bg-grey">
			<h2 class="text-center">Points Breakdown</h2>
			<p class="text-center" style="color: #f4511e; font-size: 20px;">Current Streak: <?php echo number_format($total);?> Steps</p>
			<div class="col-md-8 col-md-offset-2">
			<h4>Minimum 49,000 steps per week</h4>
			<div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" 	aria-valuenow="15550" aria-valuemin="0" aria-valuemax="100" style=<?php echo $one; ?>>
							<?php echo round($bar1); ?>%
						</div>
			</div>
			<h4>49,000 - 69,999 steps = 10 points</h4>
			<div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" 	aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style=<?php echo $two; ?>>
							<?php echo round($bar2); ?>%
						</div>
			</div>
			<h4>70,000 - 90,999 steps = 15 points</h4>
			<div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" 	aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style=<?php echo $three; ?>>
							<?php echo round($bar3); ?>%
						</div>
			</div>
			<h4>91,000 - 111,999 steps = 20 points</h4>
			<div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" 	aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style=<?php echo $four; ?>>
							<?php echo round($bar4); ?>%
						</div>
			</div>
			<h4>112,000 - 132,999 steps = 25 points</h4>
			<div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" 	aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style=<?php echo $five; ?>>
							<?php echo round($bar5); ?>%
						</div>
			</div>
			<h4>133,000 and above = 30 points</h4>
			<div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" 	aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style=<?php echo $six; ?>>
							<?php echo round($bar6); ?>%
						</div>
			</div>
			</div>
		</div>
		
		<!-- REWARDS Section -->
		<div id="rewards" class="container-fluid text-center">
			<h2>REWARDS</h2>
			<h4>What we offer</h4>
			<br>
			<div class="row">
				<div class="col-sm-4">
					<img src="images/gym300.png" alt="Lifting image" width="70" height="70">
					<h4>Gym Membership (300 points)</h4>
					<p>Get a month long free gym membership</p>
				</div>
				<div class="col-sm-4">
					<img src="images/lift.png" alt="Lifting image" width="70" height="70">
					<h4>Gym Membership (100 points)</h4>
					<p>Get a one week long free gym membership</p>
				</div>
				<div class="col-sm-4">
					<img src="images/squat.png" alt="Lifting image" width="70" height="70">
					<h4>Gym Discount (400 points)</h4>
					<p>Get 15 % next yearlong gym membership</p>
				</div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-sm-4">
					<img src="images/discount400.png" alt="Lifting image" width="70" height="70">
					<h4>Cross-fit (100 points)</h4>
					<p>Get a free week of cross-fit classes</p>
				</div>
				<div class="col-sm-4">
					<img src="images/cycling1.png" alt="Lifting image" width="70" height="70">
					<h4>Spinning (100 points)</h4>
					<p>Get a free week of spinning classes</p>
				</div>
				<div class="col-sm-4">
					<img src="images/yoga.png" alt="Lifting image" width="70" height="70">
					<h4>Yoga (100 points)</h4>
					<p>Get a free week of yoga classes</p>
				</div>
			</div>
		</div>
		
		<!-- ABOUT ME -->
		<div id="aboutMe" class="container-fluid bg-grey">
			<div class="col-md-4">
				<img src={{avatar}} class="img-circle img-responsive" alt="user" width="221" height="295">
			</div>
		
			<p class="medSize">Name: <?php echo $fullname ?> </p>
			<p class="smSize">Age: <?php echo $age ?></p>
			<p class="smSize">Height: <?php echo $height ?> m</p>
			<p class="smSize">About:</p>
			<p class="smSize"><?php echo $aboutme ?></p>
		</div>

		
		<!-- INFO -->
		<div id="info" class="container-fluid">
			<h2 class="text-center">HOW DO I GET REWARDED?</h2>
				<div class="col-md-8 col-md-offset-2">
				<p>Here at Streak for the Gym, we're all about streaks! Each day you work to hit your FitBit's daily step goal. We push you though, any goals under 7,000 steps per day will automatically be pushed up to 7,000.</p>
				<p>Every 7-day streak you accomplish you'll earn points! The more steps you take, the more points you earn!</p>
				<center>
				<p>49,000 - 69,999 steps = 10 points</p>
				<p>70,000 - 90,999 steps = 15 points</p>
				<p>91,000 - 111,999 steps = 20 points</p>
				<p>112,000 - 132,999 steps = 25 points</p>
				<p>133,000 and above steps = 30 points</p>
				</center>
				<p>Once the 7-days end, these points will go to your point bank. Once you get enough points you can redeem them for one of our many rewards!</p>
				<p>There's a catch though! If you miss a day and don't hit your daily step goal, your streak ends :(. A new streak will start the next day!</p>
				</div>
		</div>
		
		<!-- FOOTER -->
		<footer class="container-fluid text-center">
			<p>Bootstrap Theme Made By <a href="http://www.w3schools.com" title="Visit w3schools">www.w3schools.com</a></p>
		</footer>

	</body>
</html>
