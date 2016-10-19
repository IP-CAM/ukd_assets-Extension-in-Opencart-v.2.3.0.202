<?php
//after -> foreach ($custom_fields as $custom_field) line 250

require_once 'catalog/view/ukd_assets/php/validaCPF.php';

if ($custom_field['name'] == 'CPF') {
    if(isset($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])){
    $val = $this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']];
    if (!validaCPF($val)) {
        $json['error']['custom_field'.$custom_field['custom_field_id']] = 'CPF inválido!';
        if($this->error)$this->error['custom_field'][$custom_field['custom_field_id']] = 'CPF inválido!';
    }
  }
}