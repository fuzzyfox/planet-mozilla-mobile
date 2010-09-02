/*
 Lets first create the cookiemonster. The bit that reads, and writes to the
 cookie.
 
 Additional: We will also have a delete cookie function for the option
 to reset the cookie.
*/

var cookiemonster = {};

/**
 * This function creates the cookie.
 * 
 * @param flavour string The name of the cookie
 * @param ingredients json The data to be stored in the cookie.
 * @param bestBefore int The number of hours to keep the cookie
 * @param jar string The path for the cookie, default is '/'
 */
cookiemonster.bake = function(flavour, ingredients, bestBefore, jar)
{
	//set the bestBefore to correct format
	if(bestBefore)
	{
		var date = new Date();
		date.setTime(date.getTime() + (bestBefore * 60 * 60 * 1000));
		bestBefore = '; expires=' + date.toUTCString();
	}
	else
	{
		bestBefore = '';
	}
	
	//stringify the ingredients and then encode them.
	ingredients = (typeof ingredients == '')? encodeURIComponent('{}')
		: encodeURIComponent(JSON.stringify(ingredients));
	
	//check that a cookiejar exists and make one before baking the cookie
	jar = (typeof jar == 'undefined')? '; path=/' : '; path=' + jar;
	
	//bake the cookie
	document.cookie = flavour + '=' + ingredients + bestBefore + jar;
}

/**
 * This function reads the cookie and returns its contents
 *
 * @param flavour string The name of the cookie to read
 * @returns object The json object that the cookie contains if it is found, false if it is not
 */
cookiemonster.sniff = function(flavour)
{
	/*
	 set the return value to false so that if we don't find the cookie we want
	 it is easy to tell when the function is used
	*/
	var ingredients = false;
	
	//we want to search for the name of the cookie followed by an '='
	flavour += '=';
	
	//these are all the cookies we need to search to find ours
	var cookies = document.cookie.split(';');
	
	//loop through cookies till we find our cookie
	for(var i = 0; i < cookies.length; i++)
	{
		//this is the cookie currently being checked
		var cookie = cookies[i];
		//trim all whitespace from the the ends of the cookie
		cookie = cookie.trim();
		//check the name of the cookie agains what we are looking for
		if(cookie.indexOf(flavour) == 0)
		{
			//decode the cookie and parse the json
			ingredients = JSON.parse(decodeURIComponent(cookie.substring(flavour.length, cookie.length)));
		}
	}
	
	//return the results
	return ingredients;
}

/**
 * This function deletes a cookie.
 *
 * @param flavour string The name of the cookie to delete
 * @returns true if the cookie is deleted, false if it is not
 */
cookiemonster.nom = function(flavour)
{
	//delete the cookie
	cookiemonster.bake(flavour, '', -1);
	
	//check that it is gone and report back
	return (cookiemonster.sniff(flavour) == false)? true : false;
}

/*
 Now to work on the jQuery that controls the UI
*/
$(document).ready(function(){
	/*
	 set the actions of the control bar
	*/
	//back button
	$('#controls .back').click(function(){
		history.go(-1);
		return false;
	});
	//reload button
	$('#controls .reload').click(function(){
		//do some ajax grabbing of only the article list
		$('#content').empty().load('index.php #content');
	});
	
	/*
	 code that controls the list of read and unread articles
	*/
	//check for the cookie used to store read articles
	if(cookiemonster.sniff('pmom') == false)
	{
		//the cookie has not been created, lets do so now.
		cookiemonster.bake('pmom', {"showDisclaimer":true,"readArticles":[]}, 24);
	}
	
	//assign the cookie contents to a variable for speedy use
	var cookie = cookiemonster.sniff('pmom');
	
	//check if there are read articles in the cookie
	if(cookie.readArticles.length > 0)
	{
		//move all read articles to the bottom of the list
		$.each(cookie.readArticles, function(i, item){
			$('.country[rel='+item+']').fadeTo('slow', 0.3, function(){
				$(this).slideToggle('slow', function(){
					$(this).appendTo('#content').slideToggle('slow');
					//prevent it being added to the cookie again
					$(this).attr('rel', 'read');
				});
			});
		});
	}
	
	//mark an article as read when it is clicked
	$('.country[rel!=read]').live('click', function(){
		//add this cookie to the list of read ones
		cookie.readArticles.push($(this).attr('rel'));
		cookiemonster.bake('pmom', cookie, 24);
		
		//animate then link to the article
		$(this).attr('rel', 'read').fadeTo('slow', 0.3, function(){
			$(this).slideToggle('slow', function(){
				$(this).appendTo('#content').slideToggle('slow', function(){
					window.location = $(this).find('a').attr('href');
					return false;
				});
			});
		});
	});
	
	//make read articles touch friendly
	$('.country[rel=read]').live('click', function(){
		window.location = $(this).find('a').attr('href');
		return false;
	});
	
	/*
	 make the disclaimer clickable and disapear if read
	*/
	//check if disclaimer has been read on page load
	if(cookie.showDisclaimer == false)
	{
		$('#disclaimer').hide();
	}
	
	//mark disclaimer read and hide it
	$('#disclaimer').click(function(){
		//mark disclaimer as read
		cookie.showDisclaimer = false;
		cookiemonster.bake('pmom', cookie, 24);
		//hide disclaimer
		$(this).slideUp('slow');
	});
	
	/*
	 make notices touch friendly
	*/
	$('.notice').click(function(){
		window.location = $(this).find('a').attr('href');
		$(this).slideUp('slow');
	});
	
	/*
	 useful debug information
	*/
	if(typeof console != 'undefined')
	{
		console.log('cookie data: '+JSON.stringify(cookie));
		//(cookiemonster.nom('pmom') == true)?console.log('cookie deleted'):console.log('cookie not deleted');
	}
});