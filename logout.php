<?php
	session_start();
	session_unset();
	header("location: Anteprima.php");
	session_destroy();
?>