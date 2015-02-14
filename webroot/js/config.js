/*
    Gintonic Web
    Author:    Philippe Lafrance
    Link:      http://gintonicweb.com
*/

requirejs.config({

    baseUrl: '/js/',
    urlArgs: "bust=56",
    
    paths: {
        app:        '/js/app',
        basepath:   baseUrl+'GtwRequire/js/basepath',
        
        // Libs
        jquery:             '//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min',
        bootstrap:          '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min',
        jqueryvalidate:     '//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min',
        stripe:  baseUrl+'GtwStripe/js',
    },
    
    shim: {
        bootstrap : ["jquery"],
        jqueryvalidate : ["jquery"],        
        stripe : ["jquery"],
    },
    
    optimize: "none"
    
});
