let modalComponent = function(params) {
	document.getElementById('multiUsageModalTitle').innerHTML = params.title;
	document.getElementById('multiUsageModalMessage').innerHTML = params.message;

	$('#multiUsageModal').modal('show');

	$('#modalSubmitter').click(function() {
		params.onSubmit.call(this);
	});

	function hide() {
		$('#multiUsageModal').modal('hide');
	}

	return {
		hide
	}
}