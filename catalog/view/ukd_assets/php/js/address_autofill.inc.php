function save() {
    $('<?php echo $collapse_name ?> input').each(function(index, el) {
        window.
        <?php echo $form_name ?>.input[$(this).attr('name')] = $(this).val();
    });
    $('<?php echo $collapse_name ?> select').each(function(index, el) {
        window.
        <?php echo $form_name ?>.select[$(this).attr('name')] = $(this).find('option:selected').val();
        console.log($(this).find('option:selected').val(), '---------');
    });
}

function load() {

    if (window.<?php echo $form_name ?>.input) {

        for (i in window.<?php echo $form_name ?>.input) {
            $('<?php echo $collapse_name ?> input[name="' + i + '"]').val(window.
                <?php echo $form_name ?>.input[i]);
        }

    }

    if (window.<?php echo $form_name ?>.select) {

        for (i in window.<?php echo $form_name ?>.select) {
            console.log(i, window.<?php echo $form_name ?>.select[i]);
            if (i != 'country_id') {
                $('<?php echo $collapse_name ?> select[name=' + i + ']').attr('disabled', false).find('option[value=' + window.
                    <?php echo $form_name ?>.select[i] + ']').prop('selected', true);
            }
        }

    }

}

load();


$('<?php echo $collapse_name ?> input').blur(function(event) {

    save();

})

$('<?php echo $collapse_name ?> select').blur(function(event) {

    save();

})


$('<?php echo $collapse_name ?> input[name=\'postcode\']').keyup(function(event) {

    window.<?php echo $form_name ?>.restoreAddressFields($(this));

}).attr('maxlength', 8);

window.<?php echo $form_name ?>.restoreAddressFields = function(el, init) {

    if (el.val().length == 8) {
        $.ajax({
            url: 'https://viacep.com.br/ws/' + el.val() + '/json/',
            dataType: 'json',
            beforeSend: function(resp) {
                $('<?php echo $collapse_name ?> input[name=\'address_1\']').attr('disabled', true);
                $('<?php echo $collapse_name ?> input[name=\'address_2\']').attr('disabled', true);
                $('<?php echo $collapse_name ?> input[name=\'city\']').attr('disabled', true);
                $('<?php echo $collapse_name ?> input[name=\'zone_id\']').attr('disabled', true);
            },
            success: function(json) {

                if (!init) {
                    if (json['logradouro']) {
                        $('<?php echo $collapse_name ?> input[name=\'address_1\']').val(json['logradouro']);
                    }
                    if (json['bairro']) {
                        $('<?php echo $collapse_name ?> input[name=\'address_2\']').val(json['bairro']);
                    }
                }

                if (json['localidade']) {
                    $('<?php echo $collapse_name ?> input[name=\'city\']').val(json['localidade']).attr('disabled', true);
                } else {
                    $('<?php echo $collapse_name ?> input[name=\'city\']').attr('disabled', false);
                }

                if (json['uf']) {
                    $('<?php echo $collapse_name ?> select[name=\'zone_id\']').attr('disabled', true).find('option[data-sigla=' + json['uf'] + ']').prop('selected', true);
                } else {
                    $('<?php echo $collapse_name ?> select[name=\'zone_id\']').attr('disabled', false);
                }

            },
            complete: function(resp) {
                $('<?php echo $collapse_name ?> input[name=\'address_1\']').attr('disabled', false);
                $('<?php echo $collapse_name ?> input[name=\'address_2\']').attr('disabled', false);
                //$('<?php echo $collapse_name ?> input[name=\'city\']').attr('disabled', false);
                //$('<?php echo $collapse_name ?> input[name=\'zone_id\']').attr('disabled', false)
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                $('<?php echo $collapse_name ?> input[name=\'address_1\']').attr('disabled', false);
                $('<?php echo $collapse_name ?> input[name=\'address_2\']').attr('disabled', false);
                $('<?php echo $collapse_name ?> input[name=\'city\']').attr('disabled', false);
                $('<?php echo $collapse_name ?> input[name=\'zone_id\']').attr('disabled', false)
                    //load();
            }
        })
    } else {
        // $('<?php echo $collapse_name ?> input[name=\'address_1\']').val('');
        // $('<?php echo $collapse_name ?> input[name=\'address_2\']').val('');
        $('<?php echo $collapse_name ?> input[name=\'city\']').val('').attr('disabled', false);
        $('<?php echo $collapse_name ?> select[name=\'zone_id\']').attr('disabled', false).find('option[data-sigla=none]').prop('selected', true);
    }
}


$(document).delegate('<?php echo $button_name ?>', 'click', function() {

    $('<?php echo $collapse_name ?> .has-error').removeClass('has-error');

})

// $(document).delegate('<?php echo $collapse_name ?> select[name=\'zone_id\']', 'change', function() {
//     $('<?php echo $collapse_name ?> input[name=\'postcode\']').val('');
//     $('<?php echo $collapse_name ?> input[name=\'address_1\']').val('');
//     $('<?php echo $collapse_name ?> input[name=\'address_2\']').val('');
//     $('<?php echo $collapse_name ?> input[name=\'city\']').val('');
// })

window.<?php echo $form_name ?>.restoreAddressFields($('<?php echo $collapse_name ?> input[name=\'postcode\']'), true);