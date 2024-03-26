<?php

/*
CS 174 Assignment #4 - PHP + MySQL

Kyle Hamilton -- March 24, 2024

This PHP file configures the database that the transaction application will use
to store user data. It includes creating a database and table if not already created
as well as an error message helper function.
*/

$hn = 'localhost';
$un = 'username';
$pw = 'password';
$db = 'transactions_db';

$conn = new mysqli($hn, $un, $pw);

if ($conn->connect_error) die(mysql_fatal_error("OOPS", $conn));

$create_db = "CREATE DATABASE IF NOT EXISTS $db";
if (!$conn->query($sql_create_db)) die(mysql_fatal_error("OOPS", $conn));

$conn->select_db($db);

$create_table = "CREATE TABLE IF NOT EXISTS transactions (
    Email VARCHAR(64) NOT NULL,
    Balance FLOAT DEFAULT 0
)";
if (!$conn->query($sql_create_table)) die(mysql_fatal_error("OOPS", $conn));

$conn->close();

function mysql_fatal_error($msg, $conn)
{
	$msg2 = mysqli_error($conn);
	echo <<< _END
We are sorry, but it was not possible to complete
the requested task. The error message we got was:

    <p>$msg: $msg2</p>

Please click the back button on your browser
and try again. If you are still having problems,
please <a href="mailto:admin@server.com">email
our administrator</a>. Thank you.
_END;
}



?>