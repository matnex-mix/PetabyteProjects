<?php

	namespace Handular\Commands;

	class Commands {
		protected $commands = array();

		public function __construct( $Name='', $Class='' ){
			if( $Name && $Class )
				$this->command($Name, $Class);
		}

		public function push( $Name, $Class ){
			$this->commands[$Name] = $Class;
			print_r($this->commands);
		}

		public function get( $Name ){
			return $this->commands[$Name];
		}
	}