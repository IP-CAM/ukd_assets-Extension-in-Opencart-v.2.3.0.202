<?php

class ControllerExtensionShippingUkdCorreios extends Controller
{
    private $error = [];

    private $regards;

    public function index()
    {
        $prefix = 'ukd_correios_';

        $data = $this->load->language('extension/shipping/ukd_correios');

    		#SET DEFAULT OPENED TAB
    		$data['current_tab'] = 'config_tab';

        $this->regards = $this->language->get('regards');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $data['current_tab'] = $this->request->post['current_tab'];

            $this->regards = '';

            unset($this->session->data['success']);

            if ($this->validate()) {
                $this->model_setting_setting->editSetting('ukd_correios', $this->request->post);

								#DATA BACKUP
								$backup = str_replace('ukd_correios', 'ukd_correios_backup', json_encode($this->request->post));
                $this->model_setting_setting->editSetting('ukd_correios_backup', json_decode($backup));

                $this->session->data['success'] = $this->language->get('text_success');

                //$this->request->post['ukd_correios_']/

							#$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
            }
        }

    		#SET LANGUAGE VARS
    		$language = [
    			'heading_title',
    			'text_edit',
    			'text_enabled',
    			'text_disabled',
    			'text_all_zones',
    			'entry_order_status',
    			'entry_status',
    			'entry_geo_zone',
    			'entry_sort_order',
    			'help_min_amount',
    			'button_save',
    			'button_cancel',
    		];

        foreach ($language as $text) {
            $data[$text] = $this->language->get($text);
        }

        if (isset($this->session->data['success'])) {
            $data['success_warning'] = $this->language->get('text_success');
            $data['error_warning'] = '';
        } elseif (!$this->regards) {
            $data['success_warning'] = '';
            $data['error_warning'] = $this->language->get('error_warning');
        } else {
            $data['success_warning'] = $this->regards;
            $data['error_warning'] = '';
        }

        #BUTTONS ACTION
        $data['action'] = $this->url->link('extension/shipping/ukd_correios', 'token='.$this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token='.$this->session->data['token'].'&type=shipping', true);

    		#CONFIG TABS FIELDS
    		$config_tab_fields = ['status', 'sort_order', 'login', 'senha', 'cep', 'geo_zone_id'];

        $data = $this->fieldsGenarator($config_tab_fields, $data);

        // $this->load->model('localisation/order_status');
        //
        // $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        //
        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    		#PRODUCTION & SANDBOX FILEDS
    		// $form_fields = [
    		// 	#config'=>['status','sort_order','order_status_id'],
    		// 	'production' => ['email', 'token'],
    		// 	'sandbox' => ['email', 'token']
    		// ];
        //
        // foreach ($form_fields as $key => $field) {
        //     foreach ($field as $f) {
        //         $field = $prefix.$key.'_'.$f;
        //         if (isset($this->request->post[$field])) {
        //             $data[$field] = $this->request->post[$field];
        //         } else {
        //             $data[$field] = $this->config->get($field);
        //         }
        //
        //         $err = 'error_'.$key.'_'.$f;
        //         if (isset($this->error[$err])) {
        //             $data[$err] = $this->language->get($err);
        //         } else {
        //             $data[$err] = false;
        //         }
        //     }
        // }

        #BREADCRUMBS
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
    			'text' => $this->language->get('text_home'),
    			'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], true),
    		];

        $data['breadcrumbs'][] = [
    			'text' => $this->language->get('text_extension'),
    			'href' => $this->url->link('extension/extension', 'token='.$this->session->data['token'].'&type=shipping', true),
    		];

        $data['breadcrumbs'][] = [
    			'text' => $this->language->get('heading_title'),
    			'href' => $this->url->link('extension/shipping/ukd_correios', 'token='.$this->session->data['token'], true),
    		];

        #TEMPLATE PARTS
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/ukd_correios', $data));
    }

    protected function validate()
    {
        /* Error CEP */
    		if (!filter_var($this->request->post['ukd_correios_cep'], FILTER_VALIDATE_INT) || (int)strlen(trim($this->request->post['ukd_correios_cep'])) != 8) {
    		    $this->error['error_cep'] = $this->language->get('error_cep');
    		}
        // if (!$this->user->hasPermission('modify', 'extension/shipping/ukd_correios')) {
        //     $this->error['warning'] = $this->language->get('error_permission');
        // }
        //
    		// /* Error Min Amount */
    		// if (!filter_var($this->request->post['ukd_correios_min_amount'], FILTER_VALIDATE_FLOAT)) {
    		//     $this->error['error_min_amount'] = $this->language->get('error_min_amount');
    		// }
        //
    		// /* Error Email */
    		// if (!filter_var($this->request->post['ukd_correios_production_email'], FILTER_VALIDATE_EMAIL)) {
    		//     $this->error['error_production_email'] = $this->language->get('error_production_email');
    		// }
        // if (!filter_var($this->request->post['ukd_correios_sandbox_email'], FILTER_VALIDATE_EMAIL)) {
        //     $this->error['error_sandbox_email'] = $this->language->get('error_sandbox_email');
        // }
        //
    		// /* Error Token */
    		// $len = strlen($this->request->post['ukd_correios_production_token']);
        // if ($len != 32) {
        //     $this->error['error_production_token'] = $this->language->get('error_production_token');
        // }
        //
        // $len = strlen($this->request->post['ukd_correios_sandbox_token']);
        // if ($len != 32) {
        //     $this->error['error_sandbox_token'] = $this->language->get('error_sandbox_token');
        // }

        return !$this->error;
    }

    public function install()
    {
        $this->load->model('setting/setting');

        $restore = $this->model_setting_setting->getSetting('ukd_correios_backup');

        $restore = json_decode(str_replace('ukd_correios_backup', 'ukd_correios', json_encode($restore)));

        $this->model_setting_setting->editSetting('ukd_correios', $restore);

		      //$this->session->data['success'] = 'ok';
    }

    public function uninstall()
    {
        //$this->load->model('setting/setting');
		    //$this->model_setting_setting->editSetting('ukd_correios_backup', [] );
    }

    protected function fieldsGenarator($fields, $data)
    {
        foreach ($fields as $fieldname) {
            if (isset($this->request->post['ukd_correios_'.$fieldname])) {
                $data['ukd_correios_'.$fieldname] = $this->request->post['ukd_correios_'.$fieldname];
            } else {
                $data['ukd_correios_'.$fieldname] = $this->config->get('ukd_correios_'.$fieldname);
            }

            $err = 'error_'.$fieldname;
            if (isset($this->error[$err])) {
                $data[$err] = $this->language->get($err);
            } else {
                $data[$err] = false;
            }
        }

        return $data;
    }
}
