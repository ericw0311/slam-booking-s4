var before = document.getElementById('file_booking_period_before');
var beforeNumber = document.getElementById('file_booking_period_beforeNumber');
var beforeType = document.getElementById('file_booking_period_beforeType');

var after = document.getElementById('file_booking_period_after');
var afterNumber = document.getElementById('file_booking_period_afterNumber');
var afterType = document.getElementById('file_booking_period_afterType');

if (before.checked) {
	beforeNumber.style.visibility = "visible";
	beforeType.style.visibility = "visible";
} else {
	beforeNumber.style.visibility = "hidden";
	beforeType.style.visibility = "hidden";
}

if (after.checked) {
	afterNumber.style.visibility = "visible";
	afterType.style.visibility = "visible";
} else {
	afterNumber.style.visibility = "hidden";
	afterType.style.visibility = "hidden";
}

before.addEventListener('change', function() {
	// alert(before.checked);
	if (before.checked) {
		beforeNumber.style.visibility = "visible";
		beforeType.style.visibility = "visible";
	} else {
		beforeNumber.style.visibility = "hidden";
		beforeType.style.visibility = "hidden";
	}
});

after.addEventListener('change', function() {
	// alert(after.checked);
	if (after.checked) {
		afterNumber.style.visibility = "visible";
		afterType.style.visibility = "visible";
	} else {
		afterNumber.style.visibility = "hidden";
		afterType.style.visibility = "hidden";
	}
});
