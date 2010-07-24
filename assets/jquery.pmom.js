$(document).ready(function(){
	//check for cookie to tell if disclaimer has been shown
	if($.cookie('pmom') == 'true')
	{
		$('#intro').hide();
	}
	//make entire block clickable
	$('.country').click(function(){
		window.location = $(this).find("a").attr("href");
		return false;
	});
	//hide disclaimer and create cookie
	$('#intro').click(function(){
		$(this).slideUp('slow');
		$.cookie('mpmo', 'true', {expires : 7});
	});

	$('#notice').click(function(){
		window.location = 'http://www.mozhunt.com/planet/';
	});
	//send user back one page of their history if js on
	$('#controls .back').click(function(){
		history.go(-1);
		return false;
	});
	//reload just the list of posts if js on
	$('#controls .reload').click(function(){
		$('#content-inner').empty().load('pmom.php #content-inner');
		return false;
	});
});
