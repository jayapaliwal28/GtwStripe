define(function(require) {
    // Dependencies
    var $ = require('jquery');

    $('#payment-form').on('submit', function(e) {
        e.preventDefault();
        charge();
    });

    function charge() {
        var chargeAmount = $('#amount').val() * 100;
        Stripe.createToken({
            number: parseInt($('#card-number').val()),
            name: $('#card-holder-email').val(),
            cvc: parseInt($('#cvc').val()),
            exp_month: $('#card-expiry-month').val(),
            exp_year: $('#card-expiry-year').val(),
        }, chargeAmount, stripeResponseHandler);

    }

    Stripe.setPublishableKey(PublishableKey);

    function stripeResponseHandler(status, response) {

        var form$ = $("#payment-form");
        var token = response['id'];
        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
        form$.get(0).submit();
    }
});
