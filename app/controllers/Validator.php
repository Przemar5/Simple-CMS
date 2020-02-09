<?php

require_once(CONNECTION);


class Validator
{
    private $currentKey;
    public $errors;
    public $currentError;

	
    public function __construct()
    {
        //
    }

	
    public function validate($request, $rules)
    {
        $this->errors = [];
		$this->currentKey = null;
        $valid = true;
		
		if (is_array($rules) || is_object($rules)) {
			foreach ($rules as $key => $value) {
				if (array_key_exists($key, $request) && isset($request[$key])) {
					$this->currentKey = $key;
					$rulesArray = $rules[$key];

					if (!$this->compose($request[$key], $rulesArray)) {
						if ($this->errors[$key] = $this->currentError) {
							$valid = false;
						}
					}
				}

				$this->currentError = null;
			}
        	
			return $valid;
		}
		
		return false;
    }
	
	
	private function prettyPrint($data)
	{
		echo '<ul>';
		foreach($data as $key => $value) {
			echo '<li>'.$data[$key].'</li>';
		}
		echo '</ul>';
	}
	
	
	private function extractData($data)
	{
		return preg_split('/(\:|\,)/', $data);
	}
	
	
	private function compose($value, $funcArray)
	{
		if (!count($funcArray)) {
			return true;
		}

		$funcVals = Array_shift($funcArray);
		$funcVals = $this->extractData($funcVals);
		$func = Array_shift($funcVals);

		if (call_user_func_array([$this, $func], [$value, $funcVals])) {
			return $this->compose($value, $funcArray);
		}
		else {
			return false;
		}
	}
	
	
	private function executeCondition($condition, $errorMsg)
	{
        if ($condition) {
            return true;
        }
        else {
            $this->currentError = $errorMsg;

            return false;
        }
	}

	
    public function required($value)
    {
		$condition = isset($value);
		$errorMsg = "This value is required.";
		
		return $this->executeCondition($condition, $errorMsg);
    }

	
    public function min($value, $params)
    {
        $condition = strlen($value) >= $params[0];
        $errorMsg = "This value must be $params[0] characters minimum.";
		
        return $this->executeCondition($condition, $errorMsg);
    }

	
    public function max($value, $params)
    {
        $condition = strlen($value) <= $params[0];
        $errorMsg = "This value must be $params[0] characters maximum.";

        return $this->executeCondition($condition, $errorMsg);
    }

	
    public function between($value, $params)
    {
        $condition = strlen($value) >= $params[0] && strlen($value) <= $params[1];
        $errorMsg = "This value must be between $params[0] and $params[1] characters.";

        return $this->executeCondition($condition, $errorMsg);
    }

	
    public function numeric($value)
    {
        $condition = is_numeric($value);
        $errorMsg = "This value must be numeric.";

        return $this->executeCondition($condition, $errorMsg);
    }

	
    public function regex($value, $params)
    {
		$pattern = '/^' . $params[0] . '*$/';
        $condition = preg_match($pattern, $value);
        $errorMsg = "This value contains illegal characters.";

        return $this->executeCondition($condition, $errorMsg);
    }

	
    public function unique($value, $params)
    {
        $db = Connection::getInstance();
        $key = $this->currentKey;
		
		if (sizeof($params) === 2) {
			$sql = "SELECT id FROM $params[0] WHERE ($key = :$key AND id != :id)";
			$params = [
				$key => $value,
				'id' => $params[1],
			];
		}
		else if (sizeof($params) === 1) {
			$sql = "SELECT id FROM $params[0] WHERE $key = :$key";
			$params = [
				$key => $value,
			];
		}
		
		$stmt = $db->prepare($sql);
		$stmt->execute($params);
        $rows = $stmt->rowCount();

        if ($rows === 0) {
            return true;
        }
        else {
            $this->currentError = "This value already exists in database.";

            return false;
        }
    }
	
	
	public function exists($value, $params)
	{
		$db = Connection::getInstance();

		$key = $this->currentKey;
        $sql = "SELECT id FROM $params[0] WHERE $params[1] = :$params[1]";
        $sqlParams = [
			$params[1] => $value,
		];
		
		$stmt = $db->prepare($sql);
		$stmt->execute($sqlParams);
        $rows = $stmt->rowCount();

        if ($rows !== 0) {
            return true;
        }
        else {
            $this->currentError = "There is no such record in database.";

            return false;
        }
	}
	
	
	public function exists_or($value, $params)
	{
		if ($value == $params[2]) {
            return true;
        }
        else {
			return $this->exists($value, $params);
		}
	}
}