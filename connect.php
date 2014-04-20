<?php
     //error_reporting(0);
     define('DB_HOST','127.12.46.130');
     define('DB_USER','adminn7WWkxp');
     define('DB_PASS','gorgonzola7!');
     define('DB_NAME','cmspages');        
 
     // Create a MySQLi resource object called $db.
     $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); 
     
     // If an error occurs we can look here for more info:
     $connection_error = mysqli_connect_errno();
     $connection_error_message = mysqli_connect_error();
 ?>