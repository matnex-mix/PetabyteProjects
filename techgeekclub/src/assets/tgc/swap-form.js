function swap(_pos_el){
	if(true){
		/*_el.classList.toggle("btn-light");
		_el.classList.toggle("btn-white-4");
		_el.classList.toggle("border-secondary");
		_el.classList.toggle("text-dark");
		//alert(Number(!_pos_el));
		_el = document.getElementsByClassName("control")[0].getElementsByClassName("btn")[Number(!_pos_el)];
		_el.classList.toggle("btn-white-4");
		_el.classList.toggle("btn-light");
		_el.classList.toggle("border-secondary");
		_el.classList.toggle("text-dark");*/
		$(".swap-form-control .col-12").hide();
		_el = document.getElementsByClassName("swap-form-control")[0].getElementsByClassName("col-12")[_pos_el];
		_el.style.display = "block";
	}
}