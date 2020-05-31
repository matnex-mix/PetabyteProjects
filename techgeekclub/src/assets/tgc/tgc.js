function inView( elm ){
	var docTop = $(window).scrollTop();
	var docView = docTop + $(window).height();

	var elmTop = $(elm).offset().top;
	var elmBottom = elmTop + $(elm).height();

	return ((elmBottom <= docView) && (elmTop >= docTop));
}

function objIndexOf($haystack, $needle){

	for($eachH = 0;$eachH < $haystack.length;$eachH++){

		//console.log("1-\t"+(JSON.stringify($haystack[$eachH]) === JSON.stringify($needle)));

		//console.log("-\t"+JSON.stringify($needle));

		if(JSON.stringify($haystack[$eachH]) === JSON.stringify($needle)){

			return $eachH;
			
		}

	}

	return -1;

}