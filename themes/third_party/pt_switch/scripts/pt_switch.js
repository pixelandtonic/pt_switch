var ptSwitch;

(function($){

var $document = $(document),
	selectedLeft = 30;

// --------------------------------------------------------------------

/**
 * P&T Switch
 */

ptSwitch = function($select){

	$select.hide();

	var obj = this,
		offVal = $select[0][0].value,
		offLabel = $select[0][0].text,
		onVal = $select[0][1].value,
		onLabel = $select[0][1].text,
		$ul = $('<ul class="pt-switch" tabindex="0" />').insertAfter($select),
		$off = $('<li class="n">'+offLabel+'</li>').appendTo($ul),
		$toggle = $('<li class="toggle" />').appendTo($ul),
		$handle = $('<div />').appendTo($toggle),
		$on = $('<li class="y">'+onLabel+'</li>').appendTo($ul),
		selected = $select.val() == onVal;

	// set initial position
	if (selected) {
		$handle.css('left', selectedLeft);
	}

	var select = function(){
		selected = true;
		$select.val(onVal);

		$handle.stop().animate({ left: selectedLeft }, 'fast');
	};

	var deselect = function(){
		selected = false;
		$select.val(offVal);

		$handle.stop().animate({ left: 0 }, 'fast');
	};

	var toggle = function(){
		if (selected) deselect();
		else select();
	};

	// prevent focus when clicking on the labels
	$off.mousedown(function(event){ event.preventDefault(); });
	$on.mousedown(function(event){ event.preventDefault(); });

	$off.click(deselect);
	$on.click(select);

	$toggle.mousedown(function(event){
		event.preventDefault();

		var toggleWidth = $toggle.width(),
			handleWidth = $handle.width(),
			width = toggleWidth - handleWidth,
			pageX = event.pageX,
			pageY = event.pageY,
			percent = selected ? 1 : 0;

		$document.bind('mousemove.pt-switch', function(event){
			percent = (selected ? 1 : 0) + (event.pageX - pageX) / width;
			if (percent > 1) percent = 1;
			else if (percent < 0) percent = 0;
			$handle.css('left', percent * width);
		});

		$document.bind('mouseup.pt-switch', function(event){
			$document.unbind('.pt-switch');

			// just toggle if it was a single click
			if (pageX == event.pageX && pageY == event.pageY) {
				toggle();
			} else {
				if (percent < 0.5) deselect();
				else select();
			}
		});
	});

	$ul.keydown(function(event){
		switch(event.keyCode) {
			case 32: toggle();   break; // spacebar
			case 37: deselect(); break; // left arrow
			case 39: select();   break; // right arrow
			default: return;
		}

		event.preventDefault();
	});

	$select.focus(function(){
		$ul.focus();
	});
};

})(jQuery);
