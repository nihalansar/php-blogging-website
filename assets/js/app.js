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

ii = 0
$(".menubar").click(function(){
	if(ii%2==0){
		$('.menu-open').removeClass('hidden');
	}else{
		$('.menu-open').addClass('hidden');
	}
	ii++;
});