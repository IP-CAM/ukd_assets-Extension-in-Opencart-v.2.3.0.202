require.config({
    baseUrl: "./catalog/view/javascript/",
    urlArgs: null,
    paths: {
        jquery: "jquery/jquery-2.1.1.min",
        moment: "jquery/datetimepicker/moment",
        datetimepicker: "jquery/datetimepicker/bootstrap-datetimepicker.min",
        magnificpopup: "jquery/magnific/jquery.magnific-popup.min",
        owlcarousel: "jquery/owl-carousel/owl.carousel.min",
        mask: "jquery/mask/jquery.mask.min",
        bootstrap: "bootstrap/js/bootstrap.min",
        checkout: "ukd_pagseguro/checkout",
        guest: "ukd_pagseguro/guest",
        guest_shipping: "ukd_pagseguro/guest_shipping",
        payment_method: "ukd_pagseguro/payment_method",
        common: "common"
    },
    shim: {
        "bootstrap": {
            deps: ["jquery"]
        },
        "common": {
            deps: ["bootstrap"]
        },
        "datetimepicker": {
            deps: ["moment"]
        },
        "mask": {
            deps: ["jquery"]
        },
    }
})

require(["common"], function() {

    var p = [];

    $('#scripts option').each(function() {
        p.push($(this).val());
        //console.log($(this).val());
    });

    //console.log( window.ukd_fn);

    require(p, function() {
        for (i in window.ukd_fn) window.ukd_fn[i]();
        window.ukd_fn = [];
    });

});
