//console.log("Google");

var Page = {

};

function page_mine(_URL, DO){
	var _P_URL = new String(location.href);

	if(_P_URL.endsWith("/")){
		_P_URL = _P_URL.substring(0, _P_URL.length - 1);
	}

	var _BASE_URL = _P_URL.substring(_P_URL.lastIndexOf("/") + 1, _P_URL.length);

	//console.log(DO);

	_URL = new String(_URL);

	//console.log(_URL);
	if(_URL.startsWith("/")){
		_URL = new String(_URL.substring(1, _URL.length));
		//console.log(_URL);
	}

	Page.url = _P_URL;

	Page.base_url = _BASE_URL;

	Page.context = function (param){

		var startFrom = _URL.indexOf(param);

		if(startFrom != -1){
			startFrom --;

			//console.log(_BASE_URL.substring(startFrom, _BASE_URL.length));
			return _BASE_URL.substring(startFrom, _BASE_URL.length);
		}else{

			return "No parameter with this Name";

		}

	};

	_STRIPED_URL = _URL.substring(0, _URL.indexOf(":"));

	//console.log(Page.context("id"));
	//console.log(_STRIPED_URL);

	if(_BASE_URL.indexOf(_STRIPED_URL) != -1){

		DO();

	}

}

function Page_context(param){
	return Page.context(param);
}