<?php

require_once(MODELS_ROOT . '/Page.php');


class SearchController
{
	public $rowsNumber;
	public $keywords = [];
	public $phrase;
	private $sql;
	private $params = [];
	
	
	public function __construct()
	{
	}
	
	
	public function prepare($phrase)
	{
		$this->phrase = $phrase;
		$this->keywords = explode(' ', $phrase);
		
		if (count($this->keywords)) {
			foreach ($this->keywords as $word) {
				if (!$this->sql) {
					$this->sql = "
						SELECT *
						FROM pages
						WHERE (title
						LIKE :$word
						OR body
						LIKE :$word)";
				}
				else {
					$this->sql .= "
						 OR (title
						LIKE :$word
						OR body
						LIKE :$word)";
				}
				$this->params["$word"] = "%".$word."%";
			}
		}
		
		$this->sql .= "ORDER BY created_at DESC";
	}
	
	
	public function search($phrase)
	{
		$this->prepare($phrase);
		
		$stmt = Page::query($this->sql, $this->params);
		$this->rowsNumber = $stmt->rowCount();
		
		return $stmt;
	}
	
	
	public function getFoundInfo()
	{
		$pattern = '/[ ]+/';
		$this->phrase = preg_replace($pattern, ', ', $this->phrase);
		$pattern = '/(, )[0-9a-zA-Z _\+\=]$/';
		$lastWord = end($this->keywords);
		$this->phrase = preg_replace($pattern, ' and '.$lastWord, $this->phrase);
		
		$pageForm = $this->getWordEnding('page', $this->rowsNumber);
		$wordForm = $this->getWordEnding('word', sizeof($this->keywords));
		
		return "We have found $this->rowsNumber $pageForm with $wordForm $this->phrase.";
	}
	
	
	public function getWordEnding($word, $amount)
	{
		if ($amount === 1) {
			return $word;
		}
		else {
			return $word . "s";
		}
	}
}