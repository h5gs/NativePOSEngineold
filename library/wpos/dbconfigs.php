<?php



$dbConfig = [
    'host'=>'localhost',
    'port'=>'3306',
    'database'=>"default",
    'user'=>'root',
    'pass'=>'root'
];


 if (isset($_REQUEST['a']) && $_REQUEST['a'] == "auth" ) {
    $data = json_decode($_REQUEST['data']);
    // echo "<pre>";
    // print_r($data);die;
    if (!filter_var($data->username, FILTER_VALIDATE_EMAIL)) {

    $userName = explode(".",$data->username);
    $_SESSION['database']= isset($userName[0]) && isset($userName[1]) ? $userName[0] : $_SESSION['database'];


    if(isset($userName[1]) && $userName[1]=="install" && $data->password===hash('sha256','pepoapp')){

	    $conn = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass']);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		}

		// Create database
		$sql = "CREATE DATABASE ".$_SESSION['database'];
		if ($conn->query($sql) === TRUE) {
		    //echo "Database created successfully";
		    header("Location:/installer/index.php?install");
		} else {
		    echo "Error creating database: " . $conn->error;
		}
		
		$conn->close();
	}

}
	

   
}
if(isset($_SESSION['database']))
	$dbConfig['database'] = $_SESSION['database'];

