<?php
//line 216

$isValidate = debug_backtrace()[1]['function'] == 'validate' ? true : false;

if (utf8_strlen(trim($this->request->post['address_2'])) > 128) {
  $json['error']['address_2'] = $this->language->get('error_address_1');
  if($isValidate)$this->error['address_2'] = $this->language->get('error_address_1');
}

if ((utf8_strlen(trim($this->request->post['postcode'])) != 8)) {
  $json['error']['postcode'] = $this->language->get('error_postcode');
  if($isValidate)$this->error['postcode'] = $this->language->get('error_postcode');
}

if (isset($this->request->post['telephone']) && (utf8_strlen($this->request->post['telephone']) < 13 || utf8_strlen($this->request->post['telephone']) > 32)) {
  $json['error']['telephone'] = 'Telefone inválido';
  if($isValidate)$this->error['telephone'] ='Telefone inválido';
}

if ((utf8_strlen(trim($this->request->post['postcode'])) != 8)) {
  $json['error']['postcode'] = $this->language->get('error_postcode');
  if($isValidate)$this->error['postcode'] = $this->language->get('error_postcode');
}

if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
  $json['error']['zone'] = $this->language->get('error_zone');
  if($isValidate)$this->error['zone_id'] = $this->language->get('error_zone');
}


//[11,12,13,14,15,16,17,18,19 21,22,24,27,28,31,32,33,34 35,37,38,41,42,43,44,45,46 47,48,49,51,53,54,55,61,62 63,64,65,66,67,68,69,71,73 74,75,77,79,81,82,83,84,85 86,87,88,89,91,92,93,94,95 96,97,98,99]