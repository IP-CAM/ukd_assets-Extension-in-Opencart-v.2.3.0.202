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

//common functions

window.setReadonly = function(o, s) {
    for (i in o) {
        $('*[name=\'' + i + '\']').attr('disabled', s);
    }
}

window.validaCPF = function(cpf) {
    var numeros, digitos, soma, i, resultado, digitos_iguais;
    digitos_iguais = 1;
    if (cpf.length < 11)
        return false;
    for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1)) {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais) {
        numeros = cpf.substring(0, 9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--)
            soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
        numeros = cpf.substring(0, 10);
        soma = 0;
        for (i = 11; i > 1; i--)
            soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;
        return true;
    } else
        return false;
}