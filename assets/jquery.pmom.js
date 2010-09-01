$(document).ready(function(){
	$('#notice').click(function(){
		window.location = './';
	});
	//send user back one page of their history if js on
	$('#controls .back').click(function(){
		history.go(-1);
		return false;
	});
	//reload just the list of posts if js on
	$('#controls .reload').click(function(){
		$('#content-inner').empty().load('index.php #content-inner');
		return false;
	});
	
	/** Cookie Monster Code **/
	//check for cookie on page load and initialise if it is not there
	if($.cookie('pmom') == null)
	{
		console.log('empty cookie jar, baking one now');
		//set default cookie
		$.cookie('pmom', '{"readInfo":false,"readItems":[]}', {expires : 7})
	}
	console.log('cookie flavour: ' + $.cookie('pmom'));
	//create some useful variables for handling the cookie
	var cookieData = $.parseJSON($.cookie('pmom'));
	
	//check if we need to hide anything
	if(cookieData.readInfo == true)
	{
		$('#intro').hide();
	}
	if(cookieData.readItems.length > 0)
	{
		$.each(cookieData.readItems, function(i, item){
			$('.country[rel='+item+']').fadeTo('slow', 0.3, function(){
				$(this).slideToggle('slow', function(){
					$(this).appendTo('#content').slideToggle('slow');
				});
			});
		});
	}
	
	
	$('#intro').click(function(){
		//set readInfo as true
		cookieData.readInfo = true;
		//turn data back into json
		var json = JSON.stringify(cookieData);
		//debug code to check it worked
		console.log(json);
		//set cookie with new info
		$.cookie('pmom', json, {expires : 0.083});
		$(this).slideUp('slow');
	});
	
	$('.country[rel!=read]').live('click', function(){
		//check that cookie does not contain this ingredient
		if(cookieData.readItems.length > 0)
		{
			var foundDuplicate = false;
			$.each(cookieData.readItems, function(i, item){
				if(item == $(this).attr('rel'))
				{
					foundDuplicate = true;
				}
			})
			if(!foundDuplicate)
			{
				//update cookie data
				cookieData.readItems.push($(this).attr('rel'));
				//turn data back into json
				var json = JSON.stringify(cookieData);
				//debug code to check it worked
				console.log(json);
				//set cookie with new info
				$.cookie('pmom', json, {expires : 0.083});
			}
		}
		else
		{
			//update cookie data
			cookieData.readItems.push($(this).attr('rel'));
			//turn data back into json
			var json = JSON.stringify(cookieData);
			//debug code to check it worked
			console.log(json);
			//set cookie with new info
			$.cookie('pmom', json, {expires : 0.083});
		}
		//animate then link to article if not previously clicked
		$('.country[rel='+$(this).attr('rel')+']').attr('rel', 'read').fadeTo('slow', 0.3, function(){
			$(this).slideToggle('slow', function(){
				$(this).appendTo('#content').slideToggle('slow', function(){
					window.location = $(this).find('a').attr('href');
					return false;
				});
			});
		});
		return false;
	});
	$('.country[rel=read]').live('click', function(){
		window.location = $(this).find('a').attr('href');
		return false;
	});
});
