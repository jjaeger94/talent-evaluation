(function( $ ) {
	'use strict';

	$(function() {
		$('[data-toggle="popover"]').popover({
			content: function(popover) {
				return $(popover).data('content');
			}
		});

		$(window).bind('popstate',  function(event) {
			console.log('popstae', event);
			if($('.modal.fade.show')[0]){
				$('.modal.fade.show').modal('hide');
			}
		});

		// Event-Listener hinzufügen, um das Popover zu schließen, wenn außerhalb geklickt wird
		$(document).on('click', function (e) {
			// Überprüfen, ob das geklickte Element ein Popover ist oder innerhalb des Popovers liegt
			if (!$(e.target).closest('.popover').length && !$(e.target).closest('[data-toggle="popover"]').length) {
				// Schließen Sie alle geöffneten Popovers
				$('[data-toggle="popover"]').popover('hide');
			}
		});

		setTimeout(function() {
			// Elementor "Umschalter" Accordion immer ZU:
			$('.elementor-tab-title').removeClass('elementor-active');
			$('.elementor-tab-content').css('display', 'none');
	
			// ULTIMATE Accordion immer ZU:
			$('.uael-accordion-title').removeClass('uael-title-active');
			$('.uael-accordion-content').css('display', 'none');
		}, 100);

		
	})

})( jQuery );
