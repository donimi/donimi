<?php
abstract class Savant3_Filter {
	protected $Savant = null;
	public function __construct($conf = null){
		settype($conf, 'array');
		foreach ($conf as $key => $val) {
			$this->$key = $val;
		}
	}
	
	public static function filter($text){
		return $text;
	}
}