<?php
	$config=array(
		'DB_HOST'=>'localhost',
		'DB_USERNAME'=>'root',
		'DB_PASSWORD'=>'Admin@123#',
		'DB_DATABASE'=>'himachal_stay'
	);

	// global $dbconnection;
	$dbconnection = new MySQLi($config['DB_HOST'],$config['DB_USERNAME'], $config['DB_PASSWORD'], $config['DB_DATABASE']);


	 
	// if($dbconnection==true) {
	//     echo "<b>Stage 1: </b>Successfully Connect to Database <br/><br/>";
	// }

	// create database
	// if ($dbconnection->query ("DESCRIBE userRecords")) {
	//     echo "<b>Table Exists</b> <br/><br/>";
	// } else {
	//     echo "<b>Table doesn't Exists</b> <br/><br/>";
	    
	//     // query for create table
	//     $tableQuery = "CREATE TABLE userRecords (
	// 		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	// 		fullname VARCHAR(30) NOT NULL,
	// 		email VARCHAR(50),
	// 		reg_date TIMESTAMP,
	// 		image VARCHAR(100)
	// 	)";

	// 	if ($dbconnection->query($tableQuery) === TRUE) {
	// 	    echo "<b>Table Created</b> <br/><br/>";
	// 	} else {
	// 		echo "<b>Error while creating table</b>" . $dbconnection->error ."<br/><br/>";
	// 	}
	// }
	// ends here ~ create database
	

	// table for user
	// CREATE TABLE `users` (
	//   `id` int(11) NOT NULL,
	//   `firstname` char(50) NOT NULL,
	//   `lastname` char(50) NOT NULL,
	//   `email` varchar(100) NOT NULL,
	//   `password` varchar(150) NOT NULL,
	//   `mobile` bigint(20),
	//   `address` varchar(255),
	//   `gender` enum('male','female','other') NOT NULL,
	//   `district` varchar(50) NOT NULL,
	//   `state` varchar(50) NOT NULL,
	//   `country` varchar(50) NOT NULL,
	//   `user_type` int(11) NOT NULL,
	//   `user_verify` int(11) NOT NULL,
	//   `dob` date NOT NULL
	// );
	// ALTER TABLE `users`
	//   ADD PRIMARY KEY (`id`);
	// ALTER TABLE `users`
	// 	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
	// ends here ~ table for user

?>