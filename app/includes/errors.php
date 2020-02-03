<?php 
	function display_error($input)
	{
		if(isset($_SESSION['input_errors'][$input])) {
			echo '<small style="color:red; margin:0;">' . $_SESSION['input_errors'][$input] . '</small>';
			unset($_SESSION['input_errors'][$input]);
		}
	}
?>