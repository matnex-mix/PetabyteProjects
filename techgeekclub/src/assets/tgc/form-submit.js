var Result = {
	Response: "",
	Html: "",
	status: ""
};

function FormSubmit( file ){
	//alert("Started");

	xhttp = new XMLHttpRequest();
	/*xhttp.onreadystatechange = function() {
	    if (this.readyState == 4) {
		    if (this.status == 200) {Resultset(this.responseText, "success");this.abort();}
		    if (this.status == 404) {Resultset("Page not found.", "danger");}
		    remove the attribute, and call this function once more:
		    //FormSubmit( file );
			//alert(Result);
		}
		//alert(Result);
	}*/
	xhttp.open("GET", file, false);
	xhttp.send();
	//alert(Result);
	//Result += " Too Good";
	//console.log( Result.Response );
	Resultset(xhttp.responseText, "success");

	return Result;

}

function Resultset( text, type ){
	Result.Response = text;
	Result.Html = text;
	Result.status = type;
	//console.log( Result.Response );
}