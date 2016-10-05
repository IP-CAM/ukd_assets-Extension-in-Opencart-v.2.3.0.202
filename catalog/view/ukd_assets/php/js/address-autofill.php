//$('<?php echo $form_name ?> select[name=\'zone_id\']').val(<?php echo $zone_id ?>);

readonly();

address_autofill($('<?php echo $form_name ?> input[name=\'postcode\']'));

$('<?php echo $form_name ?> input[name=\'postcode\']').keyup(function(event) {

    address_autofill($(this));

}).attr('maxlength', 8);

function address_autofill(el) {
    if (el.val().length == 8) {
        $.ajax({
            url: 'https://viacep.com.br/ws/' + el.val() + '/json/',
            dataType: 'json',
            beforeSend: function(resp) {
                $('<?php echo $form_name ?> input[name=\'address_1\']').attr('readonly', true);
                $('<?php echo $form_name ?> input[name=\'address_2\']').attr('readonly', true);
                readonly();
            },
            success: function(json) {

                if (json['logradouro']) {
                    $('<?php echo $form_name ?> input[name=\'address_1\']').val(json['logradouro']);
                }
                if (json['bairro']) {
                    $('<?php echo $form_name ?> input[name=\'address_2\']').val(json['bairro']);
                }

                if (json['localidade']) {
                    $('<?php echo $form_name ?> input[name=\'city\']').val(json['localidade']).attr('readonly', true);
                } else {
                    $('<?php echo $form_name ?> input[name=\'city\']').attr('readonly', false);
                }

                if (json['uf']) {
                    $('<?php echo $form_name ?> select[name=\'zone_id\']').attr('readonly', true).find('option[data-sigla=' + json['uf'] + ']').prop('selected', true);
                } else {
                    $('<?php echo $form_name ?> select[name=\'zone_id\']').attr('readonly', false);
                }

            },
            complete: function(resp) {
                $('<?php echo $form_name ?> input[name=\'address_1\']').attr('readonly', false);
                $('<?php echo $form_name ?> input[name=\'address_2\']').attr('readonly', false);
                //$('<?php echo $form_name ?> input[name=\'city\']').attr('readonly', false);
                //$('<?php echo $form_name ?> input[name=\'zone_id\']').attr('readonly', false)
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                $('<?php echo $form_name ?> input[name=\'address_1\']').attr('readonly', false);
                $('<?php echo $form_name ?> input[name=\'address_2\']').attr('readonly', false);
                $('<?php echo $form_name ?> input[name=\'city\']').attr('readonly', false);
                $('<?php echo $form_name ?> select[name=\'zone_id\']').attr('readonly', false)
                    //load();
            }
        })
    } else {
        // $('<?php echo $collapse_name ?> input[name=\'address_1\']').val('');
        // $('<?php echo $collapse_name ?> input[name=\'address_2\']').val('');
        $('<?php echo $form_name ?> input[name=\'city\']').val('').attr('readonly', false);
        $('<?php echo $form_name ?> select[name=\'zone_id\']').attr('readonly', false).find('option[data-sigla=none]').prop('selected', true);
    }
}

function readonly() {
    //$('<?php echo $form_name ?> input[name=\'address_1\']').attr('readonly', true);
    //$('<?php echo $form_name ?> input[name=\'address_2\']').attr('readonly', true);
    $('<?php echo $form_name ?> input[name=\'city\']').attr('readonly', true);
    $('<?php echo $form_name ?> select[name=\'zone_id\']').attr('readonly', true);
}