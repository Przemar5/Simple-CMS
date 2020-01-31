<?php 
	function displayError(&$error)
	{
		if(isset($error)) {
			echo '<small style="color:red; margin:0;">' . $error . '</small>';
			unset($error);
		}
	}
?>