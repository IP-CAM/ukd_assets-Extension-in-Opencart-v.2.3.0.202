<?php
//line 216

if ((utf8_strlen(trim($this->request->post['address_2'])) < 3) || (utf8_strlen(trim($this->request->post['address_2'])) > 128)) {
  $json['error']['address_2'] = $this->language->get('error_address_1');
}

if ((utf8_strlen(trim($this->request->post['postcode'])) != 8)) {
  $json['error']['postcode'] = $this->language->get('error_postcode');
}

if ((utf8_strlen($this->request->post['telephone']) < 13) || (utf8_strlen($this->request->post['telephone']) > 32)) {
  $json['error']['telephone'] = $this->language->get('error_telephone');
}