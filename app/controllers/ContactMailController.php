<?php

if (!isset($_SESSION))
{
	session_start();
}

require_once(CONTROLLERS_ROOT . '/Sanitizer.php');
require_once('Validator.php');


class ContactMailController
{
	private static $name;
	private static $email;
	private static $subject;
	private static $message;
	private static $headers;
	
	public static function send($request)
	{
		print_r($request);
		
		if (self::validate($request)) {			
			list(self::$name,
				self::$email, 
				self::$subject, 
				self::$message) = array_values($request);

			self::prepareHeaders();
			self::prepareSubject();
			self::createView();
			
			if (self::sendMail()) {
				$_SESSION['last_action']['success'] = 'Your email has been sent successfully.';
			}
			else {
				$_SESSION['last_action']['error'] = 'Your email wasn\'t sent.';
			}
			
			header('Location: ' . HOME);
		}
		else {
			header('Location: ' . BASE_URL . '/contact');
		}
	}
	
	private static function Validate($request)
	{
		$rules = [
			'name' => ['required', 'between:2,80', /*'regex:[0-9a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ_ \-]'*/],
			'email' => ['required', 'between:8,120'],
			'subject' => ['required', 'between:1,120'],
			'message' => ['required', 'min:3'],
		];
		$validator = new Validator;
		
		if ($validator->validate($request, $rules)) {
			return true;
		}
		else {
			$_SESSION['input_errors'] = $validator->errors;
			$_SESSION['submitted_data'] = $request;
			
			return false;
		}
	}
	
	public static function prepareHeaders()
	{
		$eol = PHP_EOL;
		$headers = "From: " . strip_tags(self::$name) . $eol;
		$headers .= "Reply-To: " . strip_tags(self::$email) . $eol;
		$headers .= "CC: 1234567890localhost@gmail.com" . $eol;
		$headers .= "MIME-Version: 1.0" . $eol;
		$headers .= "Content-Type: text/html; charset=UTF-8" . $eol;
		
		self::$headers = $headers;
	}
	
	private static function prepareSubject()
	{
		self::$subject = APP_NAME . ' - ' . self::$subject;
	}
	
	private static function insertData($buffer)
	{
		$buffer = preg_replace('/\$subject/', self::$subject, $buffer, -1);
		$buffer = preg_replace('/\$message/', self::$message, $buffer, -1);
		
		return $buffer;
	}
	
	public static function createView()
	{
		$email = file_get_contents(VIEW['CONTACT_MAIL']);
		$email = self::insertData($email);
		self::$message = $email;
	}
	
	public static function sendMail()
	{
		if (mail(strip_tags(self::$email), 
				 self::$subject, 
				 self::$message,
				 self::$headers)) {
			return true;
		}
		else {
			return false;
		}
	}
}