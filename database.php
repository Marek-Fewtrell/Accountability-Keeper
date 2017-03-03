<?php

    //connects to the database and gets the stuff when needed.
    function connect() {
    	$serverName = "localhost";
		  $userName = "ak-dbAccess";
		  $password = "password";
		  
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
		  $sql = "select *, DATE_FORMAT(time, '%h:%i') as formatted_time from `activities`";
		  
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
    
    function getActivity($id) {
    	$conn = connect();
		  $sql = "select * from activities where id=$id";
		  //echo $sql;
		  $result = $conn->query($sql);
		  
		  if ($result->num_rows > 0) {
		  	$row = mysqli_fetch_assoc($result);
		  	return $row;
		  } else {
		  	return false;
		  }
    }
    
    function addActivity($name, $desc, $time, $day) {
    	$conn = connect();
		  $sql = "insert into activities (name, description, time, day) values ('$name', '$desc', '$time', '$day')";
		  //echo $sql;
		  $result = $conn->query($sql);
		  
		  if ($result === TRUE) {
		  	return true;
		  } else {
		  	return $conn->error;
		  }
		  disconnect($conn);
    }
    
    function updateActivity($id, $name, $description, $time, $day) {
    	$conn = connect();
    	$sql = "update activities set name='$name', description='$description', time='$time', day='$day' where id=$id";
    	//echo $sql;
    	
    	$result = $conn->query($sql);
    	
    	if ($result === TRUE) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    function deleteActivity($id) {
    	$conn = connect();
    	$sql = "delete from activities where id = $id";
    	//echo $sql;
    	
    	$result = $conn->query($sql);
    	
    	if ($result === TRUE) {
    		return true;
    	} else {
    		return false;
    	}
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
    
    function createUser($username, $password) {
    	$conn = connect();
    	$sql = "insert into `user` (name, password) values ('$username', '$password')";
		 	//echo $sql;
		  $result = $conn->query($sql);
		  
		  if ($result === TRUE) {
		  	return true;
		  } else {
		  	return false;
		  }
		  disconnect($conn);
    }
    
    function getUserId() {
    	
    }
    
    //--------------------------------------------------------------------
    
    
    function db_getUserSchedule($userId) {
    	$conn = connect();
		  $sql = "select schedule.id, activities.id AS activitiesId, activities.name, activities.description, activities.day, activities.time, schedule.startDate from activities INNER JOIN schedule ON schedule.activityId = activities.id where schedule.userId = " . $userId;
		  //echo $sql;
		  $result = $conn->query($sql);
		  
		  $resultArray = array();
		  
		  if ($result->num_rows > 0) {
		  	while($row = $result->fetch_assoc()) {
		  		array_push($resultArray, $row);
		  	}
		  	return $resultArray;
		  } else {
		  	return false;
		  }
		  disconnect($conn);
    }
    
    function db_getUserScheduleAfterDate($userId, $date) {
    	$conn = connect();
		  $sql = "select schedule.id, activities.id AS activitiesId, activities.name, activities.description, activities.day, DATE_FORMAT(activities.time, '%h:%i') as time, schedule.startDate from activities INNER JOIN schedule ON schedule.activityId = activities.id where schedule.userId = " . $userId . " and schedule.startDate <= '" . $date . "'";
		  //echo $sql;
		  $result = $conn->query($sql);
		  
		  $resultArray = array();
		  
		  if ($result->num_rows > 0) {
		  	while($row = $result->fetch_assoc()) {
		  		array_push($resultArray, $row);
		  	}
		  	return $resultArray;
		  } else {
		  	return false;
		  }
		  disconnect($conn);
    }
    
    function db_getSingleScheduleItem($id) {
    	$conn = connect();
		  $sql = "select * from schedule where id=$id";
		  //echo $sql;
		  $result = $conn->query($sql);
		  
		  if ($result->num_rows > 0) {
		  	$row = mysqli_fetch_assoc($result);
		  	return $row;
		  } else {
		  	return false;
		  }
    }
    
    function createSchedule($activityId, $userId, $startDate) {
    	$conn = connect();
		  $sql = "insert into schedule (activityId, userId, startDate) values ('$activityId', '$userId', '$startDate')";
		  //echo $sql;
		  $result = $conn->query($sql);
		  
		  if ($result === TRUE) {
		  	return true;
		  } else {
		  	return $conn->error;
		  }
		  disconnect($conn);
    }
    function updateSchedule($activityId, $startDate, $id) {
    	$conn = connect();
    	$sql = "update schedule set activityId='$activityId', startDate='$startDate' where id=$id";
    	//echo $sql;
    	
    	$result = $conn->query($sql);
    	
    	if ($result === TRUE) {
    		return true;
    	} else {
    		return false;
    	}
    }
    function deleteSchedule($id) {
    	$conn = connect();
    	$sql = "delete from schedule where id = $id";
    	//echo $sql;
    	
    	$result = $conn->query($sql);
    	
    	if ($result === TRUE) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    //--------------------------------------------------------------------
    
    
    function getListOfRecords($userId) {
    	$conn = connect();
		  $sql = "select * from records where userId = $userId";
		  
		  $result = $conn->query($sql);
		  
		  $resultArray = array();
		  
		  if ($result->num_rows > 0) {
		  	while($row = $result->fetch_assoc()) {
		  		array_push($resultArray, $row);
		  	}
		  	return $resultArray;
		  } else {
		  	return false;
		  }
		  disconnect($conn);
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
		  	echo false;
		  }
		  disconnect($conn);
    }
    
    function createRecord($userId, $activityId, $status, $date) {
    	$conn = connect();
		  //$sql = "insert into record (userId, activityId, status, date) values ('$userId', '$activityId', '$status', '$date')";
		  $sql = "insert into record (userId, activityId, status, date) values ('$userId', '$activityId', '$status', NOW())";
		  //echo $sql;
		  $result = $conn->query($sql);
		  
		  if ($result === TRUE) {
		  	return true;
		  } else {
		  	return $conn->error;
		  }
		  disconnect($conn);
    }
    function updateRecord($userId, $recordId, $status) {
    	$conn = connect();
    	$sql = "update record set status='$status', date=NOW() where id=$recordId and userId = $userId";
    	//echo $sql;
    	
    	$result = $conn->query($sql);
    	
    	if ($result === TRUE) {
    		return true;
    	} else {
    		return false;
    	}
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
