<?php
	
	require_once 'vendor/autoload.php';
	use PHPMailer\PHPMailer\PHPMailer;

	class Apis Extends Api {
		
		private $sql;

		function __construct() {
			session_start();
			$this->sql = new mysqli('localhost', 'admin', 'admin', 'panic_alert');
		}

		public function checkKey( $key ) {
			$session = $this->sql->query("SELECT `user`, `init` FROM `session` WHERE MD5(`id`)='$key' AND `state`=1 AND ".time()."-`init`<=1000");
			if( $session->num_rows == 1 ) {
				$this->sql->query("UPDATE `session` SET `init`=".time()." WHERE MD5(`id`)='$key' AND `state`=1 AND ".time()."-`init`<=1000");
				$session = $session->fetch_assoc();
				$_SESSION["auth_user"] = $session["user"];
				return True;
			} else {
				return $this->key( $key );
			}
		}

		public function addPanic( $props ) {
			if( !$this->logged() ) return;

			$props['user'] = intval($_SESSION['auth_user']);
			$props['state'] = 1;

			$column = []; $data = [];
			foreach ($props as $key => $value) {
				$column[] = "`$key`";
				$data[] = "'$value'";
			}
			$column = implode(',', $column);
			$data = implode(',', $data);

			if( $this->sql->query("INSERT INTO `panics`($column) VALUES($data)") !== TRUE ) {
				$this->errors[] = array('type'=>"SystemError", 'error'=>"an unknown error ocurred");
			}
		}

		public function getPanics() {
			if( !$this->logged() ) return;

			$panics = $this->sql->query("SELECT d1.id, username `user`, categoryname `category`, title, state, time FROM `panics` AS d1 LEFT JOIN (
				    SELECT id,username FROM `users`
				) AS d2 ON d2.id = d1.user LEFT JOIN (
				    SELECT id,title `categoryname` FROM `categories`
				) AS d3 ON d3.id = d1.category LEFT JOIN (
					SELECT id,user,panic,COUNT(id) subscribed FROM `panic_subscription` GROUP BY `id`   
				) AS d4 ON d4.panic = d1.id AND d4.user = ".intval($_SESSION["auth_user"])." WHERE `state`=1 ORDER BY `time` DESC");//AND d1.user!=".intval($_SESSION["auth_user"])." ORDER BY `time` DESC");
			while ($row = $panics->fetch_assoc()) {
				$date1 = Date('Y-m-d H:i:s');
				$date_diff = date_diff(date_create($row['time']), date_create($date1));
				$str = "";

				if($date_diff->y > 0) $str = $date_diff->y." years";
				else if($date_diff->m > 0) $str = $date_diff->m." months";
				else if($date_diff->d > 0) $str = $date_diff->d." days";
				else if($date_diff->h > 0) $str = $date_diff->h." hours";
				else if($date_diff->i > 0) $str = $date_diff->i." mins";
				else if($date_diff->s > 0) $str = $date_diff->s." secs";

				$row['time'] = $str;//." ago";
				$this->data[] = $row;
			}
		}

		public function getSinglePanic( $args ) {
			if( !$this->logged() ) return;

			if( !isset($args["panic"]) ) {
				$this->errors[] = array('type'=>'ArgumentError', 'error'=>'Panic not supplied');
				return;
			}
			$panic = $this->sql->query("SELECT d1.id, d1.user=".intval($_SESSION['auth_user'])." `mine`, username `user`, nicename `user_name`, d2.art `user_art`, categoryname `category`, title, content, arts, subscribed, state, time FROM `panics` AS d1 LEFT JOIN (
				    SELECT id,username,nicename,art FROM `users`
				) AS d2 ON d2.id = d1.user LEFT JOIN (
				    SELECT id,title `categoryname` FROM `categories`
				) AS d3 ON d3.id = d1.category LEFT JOIN (
					SELECT id,user,panic,COUNT(id) subscribed FROM `panic_subscription` GROUP BY `id`   
				) AS d4 ON d4.panic = d1.id AND d4.user = ".intval($_SESSION["auth_user"])." WHERE d1.id=".intval($args["panic"])." LIMIT 1");
			while ($row = $panic->fetch_assoc()) {
				$row['time'] = date_create($row['time'])->format("h:i A \n F Y");
				$row['arts'] = explode('::', $row['arts']);
				$this->data = $row;
			}
		}

		public function setPanic($props) {
			if( !$this->logged() ) return;
			if( empty($props['panic']) ) {
				$this->errors[] = array('type'=>'ArgumentError', 'error'=>'Panic not supplied');
			}

			$panic = $props['panic'];
			unset($props['panic']);

			$key_value = [];
			foreach ($props as $key => $value) {
				$key_value[] = "$key='$value'";
			}
			$key_value = implode(',', $key_value);

			if( $this->sql->query("UPDATE `panics` SET $key_value WHERE `id`=".intval($panic))!==TRUE ) {
				$this->errors[] = array('type'=>'SystemError', 'error'=>'an unknown error ocurred');
			}
		}

		public function getCategory($props) {
			if( !$this->logged() ) return;
			$category = $this->sql->query("SELECT * FROM `categories` WHERE `type`=".$props['type']);
			while ($row = $category->fetch_assoc()) {
				$this->data[] = $row;
			}
		}

		public function unsubscribe( $args ) {
			if( !$this->logged() ) return;

			if( !isset($args["panic"]) ) {
				$this->errors[] = array('type'=>'ArgumentError', 'error'=>'Panic not supplied');
				return;
			}
			if( $this->sql->query("DELETE FROM `panic_subscription` WHERE `user`=".intval($_SESSION["auth_user"])." AND `panic`=".intval($args["panic"])) ) {
				return true;
			} else {
				return false;
			}
		}

		public function login( $args ) {
			if( isset($_SESSION["auth_user"]) ) {
				$this->errors[] = array('type'=>'CommandError', 'error'=>'Session initiated already');
				return;
			}

			if( !isset($args["username"]) || !isset($args["password"]) ) {
				$this->errors[] = array('type'=>'ArgumentError', 'error'=>'Invalid username & password');
				return;
			}
			$result = $this->sql->query( "SELECT * FROM `users` WHERE `key`='".md5($args["username"].'&'.$args["password"])."'" );
			if( $result->num_rows == 1 ) {
				if( $this->sql->query(
					"INSERT INTO `session`(`user`, `init`, `state`) VALUES(".$result->fetch_assoc()['id'].", ".time().", 1)"
				) ) {
					$this->data["auth_id"] = md5($this->sql->insert_id);
				}
				$this->data["auth"] = 1;
			} else {
				$this->errors[] = array('type'=>'ArgumentError', 'error'=>'Incorrect username & password');
			}
		}

		public function addUser( $props ) {
			if( sizeof($props) < 4 ) {
				$this->errors[] = array( 'type'=>"ArgumentError", 'error'=>"required at least 1 argument" );
				return;
			}

			$this->lookupBvn( ['BVN'=>$props['bvn']] );

			if( !empty($this->data['status']) && $this->data['status']===TRUE ){
				if( $this->data['data']['formatted_dob']!==$props['date'] ){
					$this->errors[] = array( 'type'=>"ArgumentError", 'error'=>"bvn confirmation failed" );
					return;
				} else{
					$props['phone'] = $this->data['data']['mobile'];
					$props['nicename'] = strtolower($this->data['data']['first_name'].' '.$this->data['data']['last_name']);
				}
			} else {
				$this->errors[] = array( 'type'=>"ArgumentError", 'error'=>"invalid bvn" );
				return;
			}

			$props["key"] = md5($props['username'].'&'.$props['password']);
			unset($props["password"]);
			unset($props["date"]);

			$column = []; $data = [];
			foreach ($props as $key => $value) {
				$column[] = "`$key`";
				$data[] = "'$value'";
			}
			$column = implode(',', $column);
			$data = implode(',', $data);

			if( $this->sql->query("INSERT INTO `users`($column) VALUES($data)") !== TRUE ) {
				$this->errors[] = array('type'=>"SystemError", 'error'=>"an unknown error ocurred");
			}

			//$this->data = ['dummy'=>''];
		}

		public function getUser() {
			if( !$this->logged() ) return;
			$user = $this->sql->query("SELECT * FROM `users` WHERE `id`=".$_SESSION["auth_user"]);
			if( $user->num_rows==1 ) {
				$this->data = $user->fetch_assoc();
			} else {
				$this->errors[] = array('type'=>'CommandError', 'error'=>'Invalid api key');
			}
		}

		public function setUser( $props ) {
			if( !$this->logged() ) return;
			if( !sizeof($props) ) {
				$this->errors[] = array( 'type'=>"ArgumentError", 'error'=>"required at least 1 argument" );
				return;
			}

			$key_value = [];
			foreach ($props as $key => $value) {
				if( $key == "password" ) {
					$key_value[] = '`key`=MD5(CONCAT(`username`,\'&'.$props['password'].'\'))';
					unset($props["password"]);
				} else if($value!='') {
					$key_value[] = "$key='$value'";
				}
			}
			$key_value = implode(',', $key_value);

			if( $this->sql->query("UPDATE `users` SET $key_value WHERE `id`=".$_SESSION["auth_user"]) !== TRUE ) {
				$this->errors[] = array('type'=>"SystemError", 'error'=>"an unknown error ocurred");
			}
		}

		public function lookupBvn( $props ) {
			if( !isset($props['BVN']) ) {
				return;
			}
			$ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/bank/resolve_bvn/${props['BVN']}");
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        	'Authorization: Bearer sk_test_e42a5ef95cfb6c42720d480048c086e8b1205368'
	        ));
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        $this->data = json_decode(curl_exec($ch), true);
	        curl_close($ch);
		}

		public function addReport( $props ) {
			if( !$this->logged() ) return;
			$query = $this->sql->query("SELECT id FROM `reports` WHERE `panic`=${props['panic']} AND `reporter`=${_SESSION['auth_user']}");
			if( $query->num_rows <= 0 ) {
				$props['reporter'] = intval($_SESSION['auth_user']);

				$column = []; $data = [];
				foreach ($props as $key => $value) {
					$column[] = "`$key`";
					$data[] = "'$value'";
				}
				$column = implode(',', $column);
				$data = implode(',', $data);

				if( $this->sql->query("INSERT INTO `report` ($column) VALUES($data)") !== TRUE ) {
					$this->errors[] = array('type'=>'SystemError', 'error'=>'Incomplete input');
				}
			} else {
				$this->errors[] = array('type'=>'ArgumentError', 'error'=>'your report was sent already');
			}
		}

		public function forgotPassword( $props ){
			if( !isset($props['email']) ){
				$this->errors[] = array( 'type'=>"ArgumentError", 'error'=>"required an email" );
				return;
			}
			$verifyEmail = $this->sql->query("SELECT id,nicename FROM `users` WHERE `email`='${props['email']}'");

			if( $verifyEmail->num_rows>0 ){
				$verifyEmail = $verifyEmail->fetch_assoc();
				
				$timing = [microtime(), time()];
				$token = base64_encode($timing[0]).'_'.md5($timing[1]);

				$html = '
<!DOCTYPE html>
<html>
	<head>
		<style>
			body {
				font-family: "Tahoma";
			}
		</style>
	</head>
	<body>
		<img src="cid:logo_pa" style="display: block; max-width: 100%; margin-right: auto; width: 200px; height: 200px;" />

		<p>
			You requested for a password reset from our system, kindly click the link below to continue <a href="http://localhost/PetabyteProjects/panic_alert/?api=completeForgotPassword&quick_token='.$token.'">http://localhost/PetabyteProjects/panic_alert/?api=completeForgotPassword&quick_token='.$token.'</a>.
		</p>
		<p>
			If you believed this to be staged, kindly report to <a href="mailto:support@panicalert.com.ng">support@panicalert.com.ng</a>.
		</p>
		<p>
			This email was generated by our system, you must not in anyway reply to this email.
		</p>
	</body>
</html>
				';
				
				if( !$this->sendMail($html, $props['email'], $verifyEmail['nicename']) ){
					$this->errors[] = array( 'type'=>'UnknownError', 'error'=>'an unknown error ocurred please try again' );
				} else {
					$this->sql->query("INSERT INTO `tokens` (`user`, `token`, `type`) VALUES( ${verifyEmail['id']}, '$token', 'PASSWORD_RESET' )");
				}
			} else {
				$this->errors[] = array( 'type'=>'ArgumentError', 'error'=>'email does not exist in our records' );
			}
		}

		public function addArts( $props ) {
			$files = [];
			foreach ($_FILES as $value) {
				$tmp_name = $value['tmp_name'];
				$f_name = md5(microtime()).'.'.strtolower(substr($value['name'], strrpos($value['name'], '.')+1));
				move_uploaded_file($tmp_name, "arts/".$f_name);
				$files[] = $f_name;
			}
			die(implode('::', $files));
		}

		public function sendMail( $msg, $email, $name ){
			$mail = new PHPMailer;
			
			$mail->isSMTP();
			$mail->SMTPSecure = 'ssl';
			$mail->SMTPAuth = true;
			$mail->Host = 'smtp.gmail.com';
			$mail->Port = 465;
			$mail->Username = 'jolaoshobatmat@gmail.com';
			$mail->Password = 'jolbatmatnex';

			$mail->From = 'info@mjmltd.com.ng';
			$mail->FromName = 'MJM Ltd Information';
			$mail->AddEmbeddedImage('arts/logo.png', 'logo_pa');

			$mail->addAddress( $email, $name );
			$mail->isHTML(true);

			$mail->Subject = 'PanicAlert Password Recovery';
			$mail->Body = $msg;

			if( !$mail->send() ){
				return FALSE;
			} else {
				return TRUE;
			}
		}

		public function completeForgotPassword( $props ){

			$tokenObj = $this->sql->query("SELECT * FROM `tokens` WHERE `token`='${props['quick_token']}'")->fetch_assoc();
			if( $tokenObj ){
				$state = date_diff(date_create($tokenObj['time']), date_create(Date('Y-m-d H:i:s')));
				if( $state->i > 15 ){
					die( 'Token expired already' );
				} else if(empty($_GET['newp'])) {
					die("
<script>
	function getPasswords()
	{
		\$password = prompt('Please enter a new password');
		\$cpassword = prompt('Please confirm your new password');

		if( !\$password || !\$cpassword || \$password!=\$cpassword ){
			alert( 'Please fill the boxes and make sure your passwords match' );
			getPasswords();
		} else{
			location.href += '&newp='+\$password;
		}
	}

	getPasswords();
</script>
					");
				} else {
					if( $this->sql->query("UPDATE `users` SET `key`=MD5(CONCAT(`username`, '&${_GET['newp']}')) WHERE `id`= ${tokenObj['user']}")===TRUE ){
						$this->sql->query("DELETE FROM `tokens` WHERE `token`='${props['quick_token']}'");
						die( 'Password was changed successfully' );
					}
				}
			} else{
				die( 'Invalid Token' );
			}
			
		}

		public function logout() {
			if( !$this->logged() ) return;
			$this->sql->query( "DELETE FROM `session` WHERE ".time()."-`init`>1000 OR `user`=${_SESSION['auth_user']}" );
			unset($_SESSION["auth_user"]);
		}

		public function logged() {
			if( !isset($_SESSION["auth_user"]) ) {
				$this->errors[] = array( 'type'=>'CommandError', 'error'=>'Invalid Session' );
				return false;
			}
			return true;
		}

	}

	class Api {
		protected $data = array();
		protected $errors = array();

		public function key( $key ) {
			if( md5(floor(time()/60))==$key ) {
				return True;
			} else {
				return False;
			}
		}
		public function formatOutput() {
			$this->data["dummy"] = "";

			return json_encode(array(
				"data" => $this->data,
				"errors" => $this->errors,
				"error" => sizeof($this->errors)
			));
		}

		public function isQuickApi( $method_name ){
			$GLOBALS['QUICKAPIS'][] = $method_name;
		}
	}

	Api::isQuickApi( 'completeForgotPassword' );
	Api::isQuickApi( 'addUser' );