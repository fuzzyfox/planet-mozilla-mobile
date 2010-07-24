$(document).ready(function(){
	
	if($.cookie('mpmo') == 'true')
	{
		$('#intro').hide();
	}
	
	$('.country').click(function(){
		window.location = $(this).find("a").attr("href");
		return false;
	});
	$('#intro').click(function(){
		$(this).slideUp('slow');
		$.cookie('mpmo', 'true', {expires : 7});
	});
	$('#notice').click(function(){
		window.location = 'http://www.mozhunt.com/planet/';
	});
	$('#controls .back').click(function(){
		history.go(-1);
		return false;
	});
});