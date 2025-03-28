document.addEventListener("DOMContentLoaded", (event) => {
	//---------
	document.getElementById("ws247-aio-ct-button-show-all-icon").addEventListener("click", function(e){ 
		var eicons = document.getElementById("ft-contact-icons");

		if (eicons.classList.contains('active')) {
		    document.getElementById("ft-contact-icons").classList.remove('active');
			document.getElementById("ws247-aio-ct-button-show-all-icon").classList.remove('hide-me');
		}else{
			document.getElementById("ft-contact-icons").classList.add('active');
			document.getElementById("ws247-aio-ct-button-show-all-icon").classList.add('hide-me');
		}
		return;
	});


	//---------
	document.getElementById("js-hide-all-icon-e").addEventListener("click", function(){ 
		document.getElementById("ws247-aio-ct-button-show-all-icon").click();
		return;
	});
});