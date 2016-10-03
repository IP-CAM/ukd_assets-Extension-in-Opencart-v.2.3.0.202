<?php

class ModelExtensionShippingUkdCorreios extends Model
{
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

        $cep = $this->config->get('ukd_correios_cep');
        $login = $this->config->get('ukd_correios_login');
        $senha = $this->config->get('ukd_correios_senha');

        $cep_dest = $address['postcode'];

        include 'catalog/view/ukd_assets/php/get_shipping_cost.inc.php';

        if (!isset($frete['pac']) && !isset($frete['sedex'])) {
            $status = false;
            return $method_data = [
              'code' => 'ukd_correios',
              'title' => 'Não é possível entregar neste endereço.',
              'quote' => [],
              'sort_order' => $this->config->get('ukd_correios_sort_order'),
              'error' => false,
            ];
        }elseif(isset($frete['error'])){
          $status = false;
          return $method_data = [
            'code' => 'ukd_correios',
            'title' => 'Erro na conexão',
            'quote' => [],
            'sort_order' => $this->config->get('ukd_correios_sort_order'),
            'error' => true,
          ];

        }


        $method_data = [];

        if ($status) {
            $quote_data = [];

            if (isset($frete['pac'])) {

                $cost = round( (float)str_replace(',', '.', $frete['pac']['valor']) );

                $quote_data['pac'] = [
                  'code' => 'ukd_correios.pac',
                  'title' => 'PAC',
                  'cost' => $cost,
                  'tax_class_id' => $this->config->get('ukd_correios_tax_class_id'),
                  'text' => $this->currency->format($cost, $this->session->data['currency']),
                ];
            }

            if (isset($frete['sedex'])) {

                $cost = round( (float)str_replace(',', '.', $frete['sedex']['valor']) );

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
}
