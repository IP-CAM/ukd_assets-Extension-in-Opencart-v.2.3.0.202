<?php

class ControllerExtensionShippingUkdCorreios extends Controller
{
    public function index()
    {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/success');

        return $this->load->view('extension/shipping/ukd_correios', $data);
    }

    public function confirm()
    {
        if ($this->session->data['shipping_method']['code'] == 'ukd_correios') {
            $this->load->model('checkout/order');

            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('ukd_correios_order_status_id'));
        }
    }
}