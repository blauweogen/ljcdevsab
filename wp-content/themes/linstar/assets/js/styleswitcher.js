function setActiveStyleSheet(title) {
  var i, a, main;
  for(i=0; (a = document.getElementById('style-selector').getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
}

function getActiveStyleSheet() {
  var i, a;
  for(i=0; (a = document.getElementById('style-selector').getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") && !a.disabled) return a.getAttribute("title");
  }
  return null;
}

function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}


window.onunload = function(e) {
  var layout = 'wide';
  if( $('#navRadio02').get(0).checked == true ){
	  layout = 'boxed';
  }
  createCookie("layout", layout, 365);
}

jQuery(document).ready(function($){  

	$('#style-selector').animate({left: -220});
	$('#switcher-style-button').click(function(e){
	  if( $('#style-selector').css('left') == '0px' ){
		  $('#style-selector').animate({left: -220});
	  }else{
		  $('#style-selector').animate({left: 0});
	  }
	  e.preventDefault();
	});
	
	$('#list-style-colors a').click(function(e){
		var title = $(this).attr('title').toLowerCase().replace(/ /g,'');
		setActiveStyleSheet( title );
		e.preventDefault();
	});
	
	$('#layouts-style-colors input').change(function(){
		$('#main').removeClass('layout-wide').removeClass('layout-boxed').addClass('layout-'+this.value);
		$('.ls-wp-container,.flexslider').resize();
	});
	
	$('#style-switcher-bg li span').click(function(e){
		if( $('#navRadio01').get(0).checked == true ){
			alert('Please set layout as Boxed first');
			return;
		}
		$('body').css({'background' : $(this).css('background-image') });
		e.preventDefault();
	});
	
  	var cookie = readCookie("style");
  	var cookieLayout = readCookie("layout");
	if( cookie ){
	  setActiveStyleSheet(cookie);
	}
	if(cookieLayout){
		if( cookieLayout != 'boxed' ){
			return;
		}
		$('#main').removeClass('layout-wide').removeClass('layout-boxed').addClass('layout-'+cookieLayout);
		if( cookieLayout == 'boxed' ){
			$('#navRadio02').get(0).checked = true;
		}
	}
});



