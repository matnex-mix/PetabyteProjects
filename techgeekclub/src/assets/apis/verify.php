<?php
	function strComp($text1, $text2){

		if($text1 == $text2){

			return "true";

		}else {
			return "false";
		}

	}

	function formValidate($ToVerify, $BasedOn){

		if(strtolower($BasedOn) == "text"){

			preg_match('/.*/', $ToVerify, $match);
			if(!isset($match[0]))return "false";
			return strComp($match[0], $ToVerify);


		}else if(strtolower($BasedOn) == "text-abcd"){

			preg_match('/[a-zA-Z]*/', $ToVerify, $match);
			if(!isset($match[0]))return "false";
			return strComp($match[0], $ToVerify);

		}else if(strtolower($BasedOn) == "text-1234"){

			preg_match('/[0-9]*/', $ToVerify, $match);
			if(!isset($match[0]))return "false";
			return strComp($match[0], $ToVerify);

		}else if(strtolower($BasedOn) == "text-lowcase"){

			preg_match('/[a-z]*/', $ToVerify, $match);
			if(!isset($match[0]))return "false";
			return strComp($match[0], $ToVerify);

		}else if(strtolower($BasedOn) == "text-upcase"){

			preg_match('/[A-Z]*/', $ToVerify, $match);
			if(!isset($match[0]))return "false";
			return strComp($match[0], $ToVerify);

		}else if(strtolower($BasedOn) == "url"){

			preg_match('/[http:\/\/w]*.[a-zA-Z0-9]+.[a-zA-Z\/]+/', $ToVerify, $match);
			if(!isset($match[0]))return "false";
			return strComp($match[0], $ToVerify);

		}else if(strtolower($BasedOn) == "email"){

			preg_match('/\w+@\w+.\w+/', $ToVerify, $match);
			if(!isset($match[0]))return "false";
			return strComp($match[0], $ToVerify);

		}else if(strtolower($BasedOn) == "tel"){

			preg_match('/\(*\+*\d*\)*\s*\d*/', $ToVerify, $match);
			if(!isset($match[0]))return "false";
			return strComp($match[0], $ToVerify);

		}
	}

	echo formValidate("+2347059906476","tel");
?>