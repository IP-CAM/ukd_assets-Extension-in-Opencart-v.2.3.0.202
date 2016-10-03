<?php

class ModelExtensionPaymentUkdPagseguro extends Model
{
    public function getMethod($address, $total)
    {
        $this->load->language('extension/payment/ukd_pagseguro');

        if ($total > $this->config->get('ukd_pagseguro_min_amount')) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
							'code' => 'ukd_pagseguro',
							'title' => 'Ukd Pagseguro',
							'terms' => '',
							'sort_order' => $this->config->get('ukd_pagseguro_sort_order')
						];
        }

        return $method_data;
    }
}
