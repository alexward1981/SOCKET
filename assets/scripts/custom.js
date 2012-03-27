//Turn the category navigation into a drop-down filter
	var createFilter = function() {
		var filter = $('.js .mainContent .filter');
		filter.prepend('<li>Select Category</li>');
		var links = filter.find('li').not(':first-child');
		filter.click( function(e) {
			links.toggle();
			$('html').click (function() { 
				links.hide();
			});
			e.stopPropagation();
		})
	}
	
	var profileMenu = function() {
		$('.profileBox a').click(function(e) {
			e.preventDefault();
			var links =	$(this).parent().find('.profileMenu').toggle();	
			$('html').click (function() { 
				links.hide();
			});
			e.stopPropagation();			
		});
	};
	
	var bigRM = function(target) {
		var target = $('body').find(target);
		target.each( function() {
			var theLink = $(this).find('.rm').attr('href');
			if (theLink) {
				$(this).click(function() {
					window.location = theLink;
				});
			}
		})
	};
	
	var clearInput = function(target) {
		var target = $('body').find(target);
		var initVal =  target.attr('value');
		target.focus(function() {
			if ($(this).val() == initVal) $(this).val('');
		});
		target.blur(function() {
				if (!$(this).val()) $(this).val(initVal)
		});
	};
	
	var collapseBanner = function() { 
		var banner = $('#banner');
		var bannerHeight = banner.css('height');
		var container = banner.find('.container');
		var toggleBanner = $('<span class="toggle"></span>').appendTo(container);
		toggleBanner.toggle(
			function() {
				$(this).addClass('off');
				container.find('p').fadeOut('fast');
				banner.animate({
					'height' : '60px'
				}, 'slow');
			},
			function() {
				$(this).removeClass('off');
				banner.animate({
					'height' : bannerHeight
				}, 'slow', function() {
					container.find('p').fadeIn('fast');	
				});
			}
		);
	}

//begin javascript
$(function() {
	$('body').addClass('js'); // Javascript is enabled so let the css know
	profileMenu();
	createFilter();
	bigRM('.mainContent section');
	clearInput('#searchField');
	collapseBanner();
});