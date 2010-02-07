$.fn.ptPill = function(){
	return this.each(function(){
		var $select = $(this).hide(),
			$options = $('option', $select),
			$ul = $('<ul class="ptPill" tabindex="0" />').insertAfter($select),
			$selected;

		$options.each(function(){
			var $option = $(this),
				$li = $('<li />').appendTo($ul).html($option.html());

			$li.click(function(event, testing){
				if ($li == $selected) return;

				if ($selected) {
					$selected.removeClass('selected');
					$select.val($option.val()).change();
				}

				$selected = $li.addClass('selected');
			});

			if ($option.attr('selected')) {
				$li.click();
			}
		});

		if (!$selected) {
			$('li:first', $ul).click();
		}

		$ul.keydown(function(event){
			switch(event.keyCode) {
				case 37: $selected.prev().click(); break;
				case 39: $selected.next().click(); break;
				default: return;
			}

			event.preventDefault();
		});

		$select.focus(function(){
			$ul.focus();
		});
	});
};
