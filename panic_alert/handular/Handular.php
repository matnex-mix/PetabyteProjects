<?php

	namespace Handular;

	use Handular\Commands\Commands;
	use Workers\Message;

	require_once __DIR__ . '/autoload.php';
	require_once __DIR__ . '/../Workers/autoload.php';

	$GLOBALS['commands'] = new Commands();

	class Handular {

		public function __construct(){
			global $commands;
		}

		public function command( $Name, $Runner ){

			$Runner = explode('@', $Runner);
			if( empty($Runner[1]) ){
				$Runner[1] = explode('/', $Runner[0]);
				$Runner[1] = end($Runner[1]);
			}
			
			$Runner[1] = 'Workers\\'.$Runner[1];
			if( class_exists($Runner[1]) ){
				$GLOBALS['commands']->push($Name, new $Runner[1]());
			}
		}

		public function manage( $Message ){
			print_r($GLOBALS['commands']);
			$Object = null;
			$Class_Method_Name = null;

			for ($i=0; $i < strlen($Message); $i++) { 
				if( $Message[$i]!==' ' ){
					$Class_Method_Name .= $Message[$i];
				} else if( $Class_Method_Name ) {
					if( gettype($Object)=='object' ){

					} else {
						$Object = $commands->get($Class_Method_Name);
					}
					$Class_Method_Name = null;
				}
			}
		}

	}

	Handular::manage('message all -m=\'purification is beautiful\'');