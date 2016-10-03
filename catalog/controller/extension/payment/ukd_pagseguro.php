<?php

class ControllerExtensionPaymentUkdPagseguro extends Controller
{
    public function index()
    {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/success');

        if ($this->config->get('ukd_pagseguro_sandbox_enabled')) {
            $data['email'] = $this->config->get('ukd_pagseguro_sandbox_email');
            $data['token'] = $this->config->get('ukd_pagseguro_sandbox_token');
            $data['directpayment'] = 'https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js';
            $data['transactions'] = 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions';
            $data['url'] = 'https://sandbox.pagseguro.uol.com.br';
        } else {
            $data['email'] = $this->config->get('ukd_pagseguro_production_email');
            $data['token'] = $this->config->get('ukd_pagseguro_production_token');
            $data['directpayment'] = 'https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js';
            $data['transactions'] = 'https://ws.pagseguro.uol.com.br/v2/transactions';
            $data['url'] = 'https://pagseguro.uol.com.br';
        }

        $data['img_url'] = 'https://stc.pagseguro.uol.com.br';
        $data['products'] = $this->cart->getProducts();
        $data['total'] = $this->cart->getTotal();

        $data['shipping_method'] = false;
        $data['shipping_address'] = false;

        if (isset($this->session->data['shipping_method'])) {
            $data['shipping_method'] = $this->session->data['shipping_method'];
            $data['shipping_address'] = $this->session->data['shipping_address'];
        }
        $data['payment_method'] = $this->session->data['payment_method'];
        $data['payment_address'] = $this->session->data['payment_address'];

        return $this->load->view('extension/payment/ukd_pagseguro', $data);
    }

    public function confirm()
    {
        if ($this->session->data['payment_method']['code'] == 'ukd_pagseguro') {
            $this->load->model('checkout/order');

            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('ukd_pagseguro_order_status_id'));
        }
    }
}
