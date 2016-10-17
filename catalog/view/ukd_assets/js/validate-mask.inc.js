require(["mask"], function() {
    $('input[name=telephone').mask('(00) 000000000', {placeholder: "(DDD) Número do telefone"});
    $('input[name=postcode]').mask('00000000', {placeholder: "Somente números. Ex.: 01031970"});
    $('input[placeholder=CPF]').mask('00000000000');
    getAddressByPostcode(form_id);
});