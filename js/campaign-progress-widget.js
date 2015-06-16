jQuery(document).ready(function($) {

	var campaignTotal    = parseInt( $('.campaign-progress-indicator').attr('data-total') ),
			campaignProgress = parseInt( $('.campaign-progress-indicator').attr('data-progress') ),
			percent = ~~( ( campaignProgress / campaignTotal  ) * 100 );

	$( '.campaign-progress-indicator' ).animate( { height: percent + "%" }, 2500 );

/*
	$({ countNum: $('#progress-amount').text() }).animate({
		countNum: dollars
		},
		{ duration: 2500, easing:'linear', step: function() {
			$('#progress-amount').text( Math.floor( this.countNum ) );
		}
	});
*/
});