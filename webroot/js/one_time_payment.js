define(function(require) {
    // Dependencies
    var $ = require('jquery');
    
    
    Stripe.setPublishableKey(PublishableKey);
    
    $('#card-number').payment('formatCardNumber');
    $('#card-expiry').payment('formatCardExpiry');
    $('#card-cvv').payment('formatCardCVC');
    
    
    $.fn.toggleInputError = function(erred) {
      this.parent('.form-group').toggleClass('has-error', erred);
      return this;
    };
    
    
    
    $('#payment-form .form-control').on('blur', function(e) {
    	 var cardType = $.payment.cardType($('#card-number').val());
         
         $('#card-holder-email').toggleInputError(!IsEmail($('#card-holder-email').val()));
         $('#card-number').toggleInputError(!$.payment.validateCardNumber($('#card-number').val()));
         $('#card-expiry').toggleInputError(!$.payment.validateCardExpiry($('#card-expiry').payment('cardExpiryVal')));
         
         if(cardType != null){
         	$('#card-cvv').toggleInputError(!$.payment.validateCardCVC($('#card-cvv').val(), cardType));
         	$('.cc-brand').text(cardType);
         } else {
         	$('#card-cvv').toggleInputError(true);
         }
    });

    $('#payment-form').on('submit', function(e) {
        e.preventDefault();
       
        if($('.has-error').length)
        	return;
        
        charge();
    });

    function charge() {
        var chargeAmount = $('#amount').val() * 100;
        var expiry = $('#card-expiry').val().split(' / ');
        
        Stripe.createToken({
            number: parseInt($('#card-number').val().replace(/ /g,'')), 
            name: $('#card-holder-email').val(),
            cvc: parseInt($('#cvc').val()),
            exp_month: expiry[0],
            exp_year: expiry[1],
        }, chargeAmount, stripeResponseHandler);

    }

    function stripeResponseHandler(status, response) {
    	if(response['id']) {
    		var form$ = $("#payment-form");
	        var token = response['id'];
	        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
	        form$.get(0).submit();
    	} else {
    		 $("#payment-validations").html(response['error']['message']);
    	}
    }
    

    function IsEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}
    
    /*$('#buyModal').on('hidden.bs.modal', function () {
    	$('#payment-form')[0].reset();
    });*/
    
});
