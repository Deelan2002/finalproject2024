<?php 

//var ulr
$base_url = 'http://localhost/ciwe';

//var database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ciwe';


//connect database
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die('connection failed');

// prefix session
define('WP', 'ciwe2024');

?>
