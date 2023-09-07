// NumberFormat
$(document).ready(function () {
	$('#content').on('blur', 'input.currency', function () {
		let element = this;

		if ($(element).val()) {
			$(element).val(getNumber($(element).val()).toLocaleString());
		}
	});

	$('#content').on('focus', 'input.currency', function () {
		let element = this;

		if ($(element).val()) {
			$(element).val(getNumber($(element).val())).select();
		}
	});

	$('input.currency').trigger('blur');

});

function getNumber(str) {
	return Number(str.replace(/(?!-)[^0-9.]/g, ""));
};
