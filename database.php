<?php
	/*
	 * Database interaction is handled in this file.
	 *
	*/
    //Connects to the database and sets up the connection
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
    
    //Disconnects from the database.
    function disconnect($conn) {
    	$conn->close();
    }
    
    //--------------------------------------------------------------------
    
    //Retrieves all activities.
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
    
    //Retrieves just one activity by ID.
    function getActivity($id) {
    	$conn = connect();
		  $sql = "select * from activities where id=$id";
		  $result = $conn->query($sql);
		  
		  if ($result->num_rows > 0) {
		  	$row = mysqli_fetch_assoc($result);
		  	return $row;
		  } else {
		  	return false;
		  }
    }
    
    //Inserts an activity.
    function addActivity($name, $desc, $time, $day) {
    	$conn = connect();
		  $sql = "insert into activities (name, description, time, day) values ('$name', '$desc', '$time', '$day')";
		  $result = $conn->query($sql);
		  
		  if ($result === TRUE) {
		  	return true;
		  } else {
		  	return $conn->error;
		  }
		  disconnect($conn);
    }
    
    //Updates a specific activity.
    function updateActivity($id, $name, $description, $time, $day) {
    	$conn = connect();
    	$sql = "update activities set name='$name', description='$description', time='$time', day='$day' where id=$id";
    	
    	$result = $conn->query($sql);
    	
    	if ($result === TRUE) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    //Deletes an activity.
    function deleteActivity($id) {
    	$conn = connect();
    	$sql = "delete from activities where id = $id";
    	
    	$result = $conn->query($sql);
    	
    	if ($result === TRUE) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    //--------------------------------------------------------------------
    
    //Checks whether the provided user name and password are a user in the database.
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
    
    //Creates a new user.
    function createUser($username, $password) {
    	$conn = connect();
    	$sql = "insert into `user` (name, password) values ('$username', '$password')";
		  $result = $conn->query($sql);
		  
		  if ($result === TRUE) {
		  	return true;
		  } else {
		  	return false;
		  }
		  disconnect($conn);
    }
    
    
    //--------------------------------------------------------------------
    
    //Gets the schedule for a user.
    function db_getUserSchedule($userId) {
    	$conn = connect();
		  $sql = "select schedule.id, activities.id AS activitiesId, activities.name, activities.description, activities.day, activities.time, DATE_FORMAT(schedule.startDate, '%e-%c-%Y') as startDate from activities INNER JOIN schedule ON schedule.activityId = activities.id where schedule.userId = " . $userId;
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
    
    //Gets the schedule for a user after a specified date.
    function db_getUserScheduleAfterDate($userId, $date) {
    	$conn = connect();
		  $sql = "select schedule.id, activities.id AS activitiesId, activities.name, activities.description, activities.day, DATE_FORMAT(activities.time, '%h:%i') as time, schedule.startDate from activities INNER JOIN schedule ON schedule.activityId = activities.id where schedule.userId = " . $userId . " and schedule.startDate <= '" . $date . "'";
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
    
    //Retrieves a single schedule item by ID.
    function db_getSingleScheduleItem($id) {
    	$conn = connect();
		  $sql = "select * from schedule where id=$id";
		  $result = $conn->query($sql);
		  
		  if ($result->num_rows > 0) {
		  	$row = mysqli_fetch_assoc($result);
		  	return $row;
		  } else {
		  	return false;
		  }
    }
    
    //Inserts a new schedule item.
    function createSchedule($activityId, $userId, $startDate) {
    	$conn = connect();
		  $sql = "insert into schedule (activityId, userId, startDate) values ('$activityId', '$userId', '$startDate')";
		  $result = $conn->query($sql);
		  
		  if ($result === TRUE) {
		  	return true;
		  } else {
		  	return $conn->error;
		  }
		  disconnect($conn);
    }
    
    //Updates a schedule item.
    function updateSchedule($activityId, $startDate, $id) {
    	$conn = connect();
    	$sql = "update schedule set activityId='$activityId', startDate='$startDate' where id=$id";
    	
    	$result = $conn->query($sql);
    	
    	if ($result === TRUE) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    //Deletes a schedule item.
    function deleteSchedule($id) {
    	$conn = connect();
    	$sql = "delete from schedule where id = $id";
    	
    	$result = $conn->query($sql);
    	
    	if ($result === TRUE) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    //--------------------------------------------------------------------
    
    //Retrieves all the records for a specified user.
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
    
    //Get all records for a user for a date.
    function db_getRecord($userId, $date) {
    	$conn = connect();
		  $sql = "SELECT * FROM `record` WHERE userId = " . $userId . " and DATE(date) = '" . $date . "'";
		  $result = $conn->query($sql);
		  $resultArray = array();
		  if ($result->num_rows > 0) {
		  	
		  	while($row = $result->fetch_assoc()) {
		  		array_push($resultArray, $row);
		  	}
		  	return $resultArray;
		  } else {
		  	echo false;
		  }
		  disconnect($conn);
    }
    
    //Inserts a new record.
    function createRecord($userId, $activityId, $status, $date) {
    	$conn = connect();
		  //$sql = "insert into record (userId, activityId, status, date) values ('$userId', '$activityId', '$status', '$date')";
		  $sql = "insert into record (userId, activityId, status, date) values ('$userId', '$activityId', '$status', NOW())";
		  $result = $conn->query($sql);
		  
		  if ($result === TRUE) {
		  	return true;
		  } else {
		  	return $conn->error;
		  }
		  disconnect($conn);
    }
    
    //Updates a record.
    function updateRecord($userId, $recordId, $status) {
    	$conn = connect();
    	$sql = "update record set status='$status', date=NOW() where id=$recordId and userId = $userId";
    	
    	$result = $conn->query($sql);
    	
    	if ($result === TRUE) {
    		return true;
    	} else {
    		return false;
    	}
    }

?> 
