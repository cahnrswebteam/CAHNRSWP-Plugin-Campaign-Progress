jQuery(document).ready(function($) {

	var campaignTotal    = parseInt( $('.campaign-progress-indicator').attr('data-total') ),
			campaignProgress = parseInt( $('.campaign-progress-indicator').attr('data-progress') ),
			percent = ~~( ( campaignProgress / campaignTotal  ) * 100 );

	$('.campaign-progress-indicator').animate( { height: percent + "%" }, 2500 );

	$({ countNum: $('.campaign-progress-amount').text() }).animate( { countNum: campaignProgress }, {
		duration: 2500,
		easing: 'linear',
		step: function() {
			$('.campaign-progress-amount').text( Math.floor( this.countNum ) );
		},
		complete: function() {
    	$('.campaign-progress-amount').text( campaignProgress );
  	}
	});

});