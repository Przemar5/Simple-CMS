<?php

function submitted_data($input)
{
	if (!empty($_SESSION['submitted_data'][$input])) {
		echo $_SESSION['submitted_data'][$input];
		unset($_SESSION['submitted_data'][$input]);
	}
}