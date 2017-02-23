<?php

    //connects to the database and gets the stuff when needed.
    function connect() {
    	$serverName = "localhost";
		  $userName = "";
		  $password = "";
		  
		  $dbname = "accountibilityKeeper";
		  
		  $conn = new mysqli($serverName, $userName, $password, $dbname);
		  
		  if ($conn->connect_error) {
		  	die("connection failed;" . $conn->connet_error);
		  }
		  return $conn;
    }
    
    function disconnect($conn) {
    	$conn->close();
    }
    
    //--------------------------------------------------------------------
    
    
    function db_getListOfActivities() {
    	$conn = connect();
		  $sql = "select * from `activities`";
		  
		  $result = $conn->query($sql);
		  
		  $resultArray = array();
		  
		  if ($result->num_rows > 0) {
		  	while($row = $result->fetch_assoc()) {
		  		array_push($resultArray, $row);
		  	}
		  	return $resultArray;
		  } else {
		  	return "0 activities list";
		  }
		  disconnect($conn);
    }
    
    function getActivity() {
    	
    }
    
    function addActivity($name, $desc, $time, $day) {
    	$conn = connect();
		  $sql = "insert into activities (name, description, time, day) values ('$name', '$desc', '$time', '$day')";
		  echo $sql;
		  $result = $conn->query($sql);
		  
		  if ($result === TRUE) {
		  	return true;
		  } else {
		  	return $conn->error;
		  }
		  disconnect($conn);
    }
    
    function updateActivity() {
    	
    }
    
    function deleteActivity() {
    	
    }
    
    //--------------------------------------------------------------------
    
    
    function checkUserLogin($username, $password) {
    	$conn = connect();
    	$sql = "select count(*), id from `user` where `name` = '".$username."' and `password` = '".$password."'";
		  $result = $conn->query($sql);
		  disconnect($conn);
		  $row = mysqli_fetch_row($result);
		  if ($row[0] == 1) {
		  	return $row[1];
		  } else {
		  	return false;
		  }
    }
    
    function getUserId() {
    	
    }
    
    //--------------------------------------------------------------------
    
    
    function db_getUserSchedule($userId) {
    	$conn = connect();
		  $sql = "select activities.name, activities.description from activities INNER JOIN schedule ON schedule.activityId = activities.id where schedule.userId = " . $userId;
		  
		  $result = $conn->query($sql);
		  
		  $resultArray = array();
		  
		  if ($result->num_rows > 0) {
		  	while($row = $result->fetch_assoc()) {
		  		array_push($resultArray, $row);
		  	}
		  	return $resultArray;
		  } else {
		  	echo "0 activities list";
		  }
		  disconnect($conn);
    }
    
    function createSchedule() {
    	
    }
    function updateSchedule() {
    	
    }
    function deleteSchedule() {
    	
    }
    
    //--------------------------------------------------------------------
    
    
    function getListOfRecords() {
    	
    }
    
    function db_getRecord($userId, $date) {
    	$conn = connect();
		  $sql = "SELECT * FROM `record` WHERE userId = " . $userId . " and DATE(date) = '" . $date . "'";
		  //echo $sql;
		  $result = $conn->query($sql);
		  $resultArray = array();
		  if ($result->num_rows > 0) {
		  	
		  	while($row = $result->fetch_assoc()) {
		  	array_push($resultArray, $row);
		  		//echo "userId: " . $row["userId"] . " activityId: " . $row["activityId"] . " status: " . $row["status"];
		  	}
		  	return $resultArray;
		  } else {
		  	echo "0 records list";
		  }
		  disconnect($conn);
    }
    
    function createRecord() {
    	
    }
    function updateRecord() {
    	
    }
    function deleteRecord() {
    	
    }
    
    
    
    //--------------------------------------------------------------------
    
    
    function getstuff() {
    	$conn = connect();
		  $sql = "select activities.name, user.name from `schedule`, `activities`, `user`";
		  
		  $result = $conn->query($sql);
		  
		  if ($result->num_rows > 0) {
		  	while($row = $result->fetch_assoc()) {
		  		echo "activities name: " . $row["name"] . " - user.name: " . $row["name"];
		  	}
		  } else {
		  	echo "0 results";
		  }
		  disconnect($conn);
		  
    }
    
    
    
?> 
