<?php

class ControllerExtensionPaymentUkdPagseguro extends Controller
{
    private $error = [];

    private $regards;

    public function index()
    {
        $prefix = 'ukd_pagseguro_';

        $data = $this->load->language('extension/payment/ukd_pagseguro');

    		#SET DEFAULT OPENED TAB
    		$data['current_tab'] = 'config_tab';

        $this->regards = $this->language->get('regards');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $data['current_tab'] = $this->request->post['current_tab'];

            if ($this->validate()) {
                $this->model_setting_setting->editSetting('ukd_pagseguro', $this->request->post);

								#DATA BACKUP
								$backup = str_replace('ukd_pagseguro', 'ukd_pagseguro_backup', json_encode($this->request->post));
                $this->model_setting_setting->editSetting('ukd_pagseguro_backup', json_decode($backup));

                $this->session->data['success'] = $this->language->get('text_success');

								#SAVE CONFIG FILE
                $path = str_replace('\\', DIRECTORY_SEPARATOR, '..\\system\\library\\ukd_pagseguro_app\\');

                $config_file = realpath($path.'config.php');

                if (file_exists($config_file)) {
                    $content = file_get_contents($config_file);

                    $env = $this->request->post['ukd_pagseguro_sandbox_enabled'];

                    if($env){
                      $env = 'sandbox';
                    }else{
                      $env = 'production';     
                    }

                    $content = str_replace('$enviroment', $env, $content);

                    if ($env == 'sandbox') {
                        $content = str_replace('$pagseguro_email', $this->request->post['ukd_pagseguro_sandbox_email'], $content);
                    } else {
                        $content = str_replace('$pagseguro_email', $this->request->post['ukd_pagseguro_production_email'], $content);
                    }

                    $content = str_replace('$production_token', $this->request->post['ukd_pagseguro_production_token'], $content);

                    $content = str_replace('$sandbox_token', $this->request->post['ukd_pagseguro_sandbox_token'], $content);

                    $path = str_replace('\\', DIRECTORY_SEPARATOR, '..\\system\\library\\PagSeguroLibrary\\config\\');

                    $dest_file = realpath($path.'PagSeguroConfigWrapper.php');

                    try {
                        unlink($dest_file);
                        $f = fopen($dest_file, 'w+');
                        fwrite($f, utf8_encode($content));
                        fclose($f);
                    } catch (Exception $e) {

										    #echo 'Caught exception: ',  $e->getMessage(), "\n";
                    }
                }

							#$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
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
    			'button_cancel'
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

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
    			'text' => $this->language->get('text_home'),
    			'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], true)
    		];

        $data['breadcrumbs'][] = [
    			'text' => $this->language->get('text_extension'),
    			'href' => $this->url->link('extension/extension', 'token='.$this->session->data['token'].'&type=payment', true)
    		];

        $data['breadcrumbs'][] = [
    			'text' => $this->language->get('heading_title'),
    			'href' => $this->url->link('extension/payment/ukd_pagseguro', 'token='.$this->session->data['token'], true)
    		];

        $data['action'] = $this->url->link('extension/payment/ukd_pagseguro', 'token='.$this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token='.$this->session->data['token'].'&type=payment', true);

    		#CONFIG TABS FIELDS
    		$config_tab_fields = ['status', 'sandbox_enabled', 'sort_order', 'order_status_id', 'min_amount', 'geo_zone_id'];

        foreach ($config_tab_fields as $f) {
            if (isset($this->request->post['ukd_pagseguro_'.$f])) {
                $data['ukd_pagseguro_'.$f] = $this->request->post['ukd_pagseguro_'.$f];
            } else {
                $data['ukd_pagseguro_'.$f] = $this->config->get('ukd_pagseguro_'.$f);
            }

            $err = 'error_'.$f;
            if (isset($this->error[$err])) {
                $data[$err] = $this->language->get($err);
            } else {
                $data[$err] = false;
            }
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    		#PRODUCTION & SANDBOX FILEDS
    		$form_fields = [
    			#config'=>['status','sort_order','order_status_id'],
    			'production' => ['email', 'token'],
    			'sandbox' => ['email', 'token']
    		];

        foreach ($form_fields as $key => $field) {
            foreach ($field as $f) {
                $field = $prefix.$key.'_'.$f;
                if (isset($this->request->post[$field])) {
                    $data[$field] = $this->request->post[$field];
                } else {
                    $data[$field] = $this->config->get($field);
                }

                $err = 'error_'.$key.'_'.$f;
                if (isset($this->error[$err])) {
                    $data[$err] = $this->language->get($err);
                } else {
                    $data[$err] = false;
                }
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/ukd_pagseguro', $data));
    }

    protected function validate()
    {
        $this->regards = '';

        unset($this->session->data['success']);

        if (!$this->user->hasPermission('modify', 'extension/payment/ukd_pagseguro')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

    		/* Error Min Amount */
    		if (!filter_var($this->request->post['ukd_pagseguro_min_amount'], FILTER_VALIDATE_FLOAT)) {
    		    $this->error['error_min_amount'] = $this->language->get('error_min_amount');
    		}

    		/* Error Email */
    		if (!filter_var($this->request->post['ukd_pagseguro_production_email'], FILTER_VALIDATE_EMAIL)) {
    		    $this->error['error_production_email'] = $this->language->get('error_production_email');
    		}
        if (!filter_var($this->request->post['ukd_pagseguro_sandbox_email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['error_sandbox_email'] = $this->language->get('error_sandbox_email');
        }

    		/* Error Token */
    		$len = strlen($this->request->post['ukd_pagseguro_production_token']);
        if ($len != 32) {
            $this->error['error_production_token'] = $this->language->get('error_production_token');
        }

        $len = strlen($this->request->post['ukd_pagseguro_sandbox_token']);
        if ($len != 32) {
            $this->error['error_sandbox_token'] = $this->language->get('error_sandbox_token');
        }

        return !$this->error;
    }

    public function install()
    {
        $this->load->model('setting/setting');

        $restore = $this->model_setting_setting->getSetting('ukd_pagseguro_backup');

        $restore = json_decode(str_replace('ukd_pagseguro_backup', 'ukd_pagseguro', json_encode($restore)));

        $this->model_setting_setting->editSetting('ukd_pagseguro', $restore);

		      //$this->session->data['success'] = 'ok';
    }

    public function uninstall()
    {
        //$this->load->model('setting/setting');
		    //$this->model_setting_setting->editSetting('ukd_pagseguro_backup', [] );
    }
}
