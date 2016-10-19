<?php

class ModelExtensionShippingUkdCorreios extends Model
{
    public $cep;
    public $login;
    public $senha;
    public $cep_dest;
    public $zone_id;
    public $country_id;
    public $weight;
    public $status;

    public function getQuote($address)
    {
        $this->load->language('extension/shipping/ukd_correios');

        //$order_id = $this->session->data['order_id'];

        $products = $this->cart->getProducts();

        $weight = 0;

        foreach ($products as $key => $value) {
            //echo $value['weight'].'<br />';
          $weight += (float) $value['weight'];
        }

        //echo $weight;

        // $query = $this->db->query('SELECT * FROM '.DB_PREFIX."zone_to_geo_zone WHERE geo_zone_id = '".(int) $this->config->get('ukd_correios_geo_zone_id')."' AND country_id = '".(int) $address['country_id']."' AND (zone_id = '".(int) $address['zone_id']."' OR zone_id = '0')");

        // $query = $this->db->query('SELECT SUM(p.weight * op.quantity) AS weight FROM '.DB_PREFIX.'order_product op LEFT JOIN '.DB_PREFIX."product p ON op.product_id = p.product_id WHERE op.order_id = '".(int) $order_id."'");
        //echo $query->row['weight'];

        if (!$this->config->get('ukd_correios_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $status = true;

        // if ($this->cart->getSubTotal() < $this->config->get('free_total')) {
    		// 	$status = false;
    		// }

        $this->cep = $this->config->get('ukd_correios_cep');
        $this->login = $this->config->get('ukd_correios_login');
        $this->senha = $this->config->get('ukd_correios_senha');

        $cep_dest = $address['postcode'];
        $this->zone_id = $address['zone_id'];
        $this->country_id = $address['country_id'];
        $this->weight = $weight;

        $this->status = $status;

        return $this->getCep($cep_dest);
    }

    public function getCep($cep_dest)
    {
        $cep = $this->cep;
        $login = $this->login;
        $senha = $this->senha;

        //$cep_dest = $this->cep_dest;
        $country_id = $this->country_id;
        $zone_id = $this->zone_id;
        $weight = $this->weight;

        $status = $this->status;

        include 'catalog/view/ukd_assets/php/get_shipping_cost.inc.php';


        if (!isset($frete['pac']) && !isset($frete['sedex'])) {
            $status = false;

            $cep_dest = $this->getCepByZoneId($this->zone_id);

            if ($cep_dest) {
                $resp = $this->getCep($cep_dest);
            } else {
                $resp = [
                    'code' => 'ukd_correios',
                    'title' => 'Não é possível entregar neste endereço.',
                    'quote' => [],
                    'sort_order' => $this->config->get('ukd_correios_sort_order'),
                    'error' => false,
                ];
            }

            return $resp;
        }
        else if (isset($frete['error'])) {
            $status = false;

            return $method_data = [
                'code' => 'ukd_correios',
                'title' => 'Correios',
                'quote' => [],
                'sort_order' => $this->config->get('ukd_correios_sort_order'),
                'error' => $frete['error'],
            ];
        }

        $method_data = [];

        if ($status) {
            $quote_data = [];

            if (isset($frete['pac'])) {
                $cost = round((float) str_replace(',', '.', $frete['pac']['valor']));

                $quote_data['pac'] = [
                  'code' => 'ukd_correios.pac',
                  'title' => 'PAC',
                  'cost' => $cost,
                  'tax_class_id' => $this->config->get('ukd_correios_tax_class_id'),
                  'text' => $this->currency->format($cost, $this->session->data['currency']),
                ];
            }

            if (isset($frete['sedex'])) {
                $cost = round((float) str_replace(',', '.', $frete['sedex']['valor']));

                $quote_data['sedex'] = [
                  'code' => 'ukd_correios.sedex',
                  'title' => 'SEDEX',
                  'cost' => $cost,
                  'tax_class_id' => $this->config->get('ukd_correios_tax_class_id'),
                  'text' => $this->currency->format($cost, $this->session->data['currency']),
                ];
            }

            $method_data = [
              'code' => 'ukd_correios',
              'title' => $this->language->get('text_title'),
              'quote' => $quote_data,
              'sort_order' => $this->config->get('ukd_correios_sort_order'),
              'error' => false,
            ];
        }

        return $method_data;
    }

    public function getCepByZoneId($zone_id)
    {
        if ($zone_id == '440') { //AC
            $cep = '69901010';
        } elseif ($zone_id == '441') { //AL
            $cep = '57020970';
        } elseif ($zone_id == '442') { //AP
            $cep = '68906970';
        } elseif ($zone_id == '443') { //AM
            $cep = '69020263';
        } elseif ($zone_id == '444') { //BA
            $cep = '40015970';
        } elseif ($zone_id == '445') { //CE
            $cep = '60030970';
        } elseif ($zone_id == '446') { //DF
            $cep = '70040976';
        } elseif ($zone_id == '447') { //ES
            $cep = '29001970';
        } elseif ($zone_id == '448') { //GO
            $cep = '74001970';
        } elseif ($zone_id == '449') { //MA
            $cep = '69010970';
        } elseif ($zone_id == '450') { //MT
            $cep = '78005970';
        } elseif ($zone_id == '451') { //MS
            $cep = '79002970';
        } elseif ($zone_id == '452') { //MG
            $cep = '30190973';
        } elseif ($zone_id == '453') { //PA
            $cep = '66017970';
        } elseif ($zone_id == '454') { //PB
            $cep = '58071973';
        } elseif ($zone_id == '455') { //PR
            $cep = '80001970';
        } elseif ($zone_id == '456') { //PE
            $cep = '50010970';
        } elseif ($zone_id == '457') { //PI
            $cep = '64001970';
        } elseif ($zone_id == '458') { //RJ
            $cep = '20010974';
        } elseif ($zone_id == '459') { //RN
            $cep = '59010970';
        } elseif ($zone_id == '460') { //RS
            $cep = '90010001';
        } elseif ($zone_id == '461') { //RO
            $cep = '76801974';
        } elseif ($zone_id == '462') { //RR
            $cep = '69301970';
        } elseif ($zone_id == '463') { //SC
            $cep = '88010970';
        } elseif ($zone_id == '464') { //SP
            $cep = '01031970';
        } elseif ($zone_id == '465') { //SE
            $cep = '49001970';
        } elseif ($zone_id == '466') { //TO
            $cep = '77001970';
        } else {
            $cep = false;
        }

        return $cep;
    }
}
