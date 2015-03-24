var checkConfirm = 0;

$('.confirm').on('click', function() {
	checkConfirm = 1;
});

$('form').on('submit', function() {
	if (checkConfirm == 1) {
		checkConfirm = 0;
		return confirm('Are you sure you want to proceed?');
	}
});

$('.confirmLink').on('click',  function(e) {
	var check = confirm('Are you sure you want to proceed?');
	if (check){ } else {
		e.stopPropagation();
		e.preventDefault();
	}
});