(function($){

$.fn.ptSwitch = function(){
	return this.each(function(){
		var $select = $(this).hide(),
			offVal = this[0].value,
			offLabel = this[0].text,
			onVal = this[1].value,
			onLabel = this[1].text,
			$ul = $('<ul class="pt-switch" tabindex="0" />').insertAfter($select),
			$off = $('<li class="'+offVal+'">'+offLabel+'</li>').appendTo($ul),
			$toggle = $('<li class="toggle" />').appendTo($ul),
			$on = $('<li class="'+onVal+'">'+onLabel+'</li>').appendTo($ul),
			selected = $select.val() == onVal;

		// set initial bg position
		$toggle.css({ backgroundPosition: (selected ? 0 : 100) + '% 0' });

		var select = function(){
			selected = true;
			$select.val('y');
			$toggle.stop().animate({
				backgroundPosition: "0% 0"
			}, 'fast');
		};

		var deselect = function(){
			selected = false;
			$select.val('n');
			$toggle.stop().animate({
				backgroundPosition: '100% 0'
			}, 'fast');
		};

		var toggle = function(){
			if (selected) deselect();
			else select();
		};

		$off.click(deselect);
		$on.click(select);

		$toggle.mousedown(function(event){
			var width = $toggle.width(),
				pageX = event.pageX,
				pageY = event.pageY,
				percent = selected ? 100 : 0;

			$(document).bind('mousemove.pt-switch', function(event){
				percent = (selected ? 100 : 0) + 100 * (event.pageX - pageX) / width;
				if (percent > 100) percent = 100;
				else if (percent < 0) percent = 0;
				$toggle.css('background-position', (100-percent)+'% 0');
			});

			$(document).bind('mouseup.pt-switch', function(event){
				$(document).unbind('.pt-switch');

				// just toggle if it was a single click
				if (pageX == event.pageX && pageY == event.pageY) {
					toggle();
				} else {
					if (percent < 50) deselect();
					else select();
				}
			});
		});

		$ul.keydown(function(event){
			switch(event.keyCode) {
				case 32: toggle(); break;
				case 37: deselect(); break;
				case 39: select(); break;
				default: return;
			}

			event.preventDefault();
		});

		$select.focus(function(){
			$ul.focus();
		});
	});
};


$('select.pt-switch').ptSwitch();

})(jQuery);
