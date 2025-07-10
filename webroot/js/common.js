$(document).ready(function()
{
	// 一定時間経過後、メッセージを閉じる
	setTimeout(function() {
		$('#flashMessage').fadeOut("slow");
	}, 1500);
});


function CommonUtility() {}

CommonUtility.prototype.getHHMMSSbySec = function (sec)
{
	var date = new Date('2000/1/1');
	
	date.setSeconds(sec);
	
	var h = date.getHours();
	var m = date.getMinutes();
	var s = date.getSeconds();
	
	if (h < 10)
		h = '0' + h;
	
	if (m < 10)
		m = '0' + m;
	
	if (s < 10)
		s = '0' + s;
	
	var hms = h + ':' + m + ':' + s;
	
	return hms;
}


var CommonUtil = new CommonUtility();

