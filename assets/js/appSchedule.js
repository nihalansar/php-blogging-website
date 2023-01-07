$(document).ready(function(){
	setTimeout(function () {
         $('.loader').addClass('hidden');
     }, 2000);
});

var spinner = $('#loader');
$(function() {
  $('form').submit(function(e) {
    spinner.show();
  });
});

var currentDate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
var day = currentDate.getDate()
var month = currentDate.getMonth() + 1
var year = currentDate.getFullYear()
tomorrow = day + "-" + month + "-" + year;

ii = 0
$(".menubar").click(function(){
	if(ii%2==0){
		$('.menu-open').removeClass('hidden');
	}else{
		$('.menu-open').addClass('hidden');
	}
	ii++;
});