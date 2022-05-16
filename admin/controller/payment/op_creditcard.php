<?php 
class ControllerPaymentOPCreditCard extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/op_creditcard');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('op_creditcard', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token='. $this->session->data['token']);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_pay'] = $this->language->get('text_pay');
		$this->data['text_test'] = $this->language->get('text_test');	
		$this->data['text_pay_iframe'] = $this->language->get('text_pay_iframe');
		$this->data['text_pay_redirect'] = $this->language->get('text_pay_redirect');
		$this->data['text_pay_iframe'] = $this->language->get('text_pay_iframe');
		$this->data['text_select_currency'] = $this->language->get('text_select_currency');
		$this->data['text_3d_on'] = $this->language->get('text_3d_on');
		$this->data['text_3d_off'] = $this->language->get('text_3d_off');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_logs_true'] = $this->language->get('text_logs_true');
		$this->data['text_logs_false'] = $this->language->get('text_logs_false');
	
		
		$this->data['entry_account'] = $this->language->get('entry_account');
		$this->data['entry_terminal'] = $this->language->get('entry_terminal');
		$this->data['entry_securecode'] = $this->language->get('entry_securecode');
		$this->data['entry_3d'] = $this->language->get('entry_3d');
		$this->data['entry_3d_terminal'] = $this->language->get('entry_3d_terminal');
		$this->data['entry_3d_securecode'] = $this->language->get('entry_3d_securecode');
		$this->data['entry_currencies'] = $this->language->get('entry_currencies');
		$this->data['entry_currencies_value'] = $this->language->get('entry_currencies_value');
		$this->data['entry_countries'] = $this->language->get('entry_countries');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
		$this->data['entry_pay_mode'] = $this->language->get('entry_pay_mode');
		
		$this->data['entry_default_order_status'] = $this->language->get('entry_default_order_status');	
		$this->data['entry_success_order_status']=$this->language->get('entry_success_order_status');
		$this->data['entry_failed_order_status']=$this->language->get('entry_failed_order_status');
		$this->data['entry_pending_order_status']=$this->language->get('entry_pending_order_status');
		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['entry_location'] = $this->language->get('entry_location');
		$this->data['entry_locations'] = $this->language->get('entry_locations');
		$this->data['entry_entity'] = $this->language->get('entry_entity');
		$this->data['entry_entitys'] = $this->language->get('entry_entitys');
		$this->data['entry_logs'] = $this->language->get('entry_logs');
		
		$this->data['text_show'] = $this->language->get('text_show');
		$this->data['text_hide'] = $this->language->get('text_hide');

		$this->data['text_shows'] = $this->language->get('text_shows');
		$this->data['text_hides'] = $this->language->get('text_hides');




 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['account'])) {
			$this->data['error_account'] = $this->error['account'];
		} else {
			$this->data['error_account'] = '';
		}

		if (isset($this->error['terminal'])) {
			$this->data['error_terminal'] = $this->error['terminal'];
		} else {
			$this->data['error_terminal'] = '';
		}		
		
 		if (isset($this->error['securecode'])) {
			$this->data['error_securecode'] = $this->error['securecode'];
		} else {
			$this->data['error_securecode'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token='. $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token='. $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/op_creditcard&token='. $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/op_creditcard&token='. $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token='. $this->session->data['token'];
		
		if (isset($this->request->post['op_creditcard_account'])) {
			$this->data['op_creditcard_account'] = $this->request->post['op_creditcard_account'];
		} else {
			$this->data['op_creditcard_account'] = $this->config->get('op_creditcard_account');
		}
		
		if (isset($this->request->post['op_creditcard_terminal'])) {
			$this->data['op_creditcard_terminal'] = $this->request->post['op_creditcard_terminal'];
		} else {
			$this->data['op_creditcard_terminal'] = $this->config->get('op_creditcard_terminal');
		}
		
		if (isset($this->request->post['op_creditcard_securecode'])) {
			$this->data['op_creditcard_securecode'] = $this->request->post['op_creditcard_securecode'];
		} else {
			$this->data['op_creditcard_securecode'] = $this->config->get('op_creditcard_securecode');
		}
		

		if (isset($this->request->post['op_creditcard_3d'])) {
			$this->data['op_creditcard_3d'] = $this->request->post['op_creditcard_3d'];
		} else {
			$this->data['op_creditcard_3d'] = $this->config->get('op_creditcard_3d');
		}
		
		if (isset($this->request->post['op_creditcard_3d_terminal'])) {
			$this->data['op_creditcard_3d_terminal'] = $this->request->post['op_creditcard_3d_terminal'];
		} else {
			$this->data['op_creditcard_3d_terminal'] = $this->config->get('op_creditcard_3d_terminal');
		}
		
		if (isset($this->request->post['op_creditcard_3d_securecode'])) {
			$this->data['op_creditcard_3d_securecode'] = $this->request->post['op_creditcard_3d_securecode'];
		} else {
			$this->data['op_creditcard_3d_securecode'] = $this->config->get('op_creditcard_3d_securecode');
		}
		
		$this->load->model('localisation/currency');
		$results = $this->model_localisation_currency->getCurrencies();
		foreach ($results as $result) {
			$this->data['currencies'][] = $result['code'];
		}
		
		
		if (isset($this->request->post['op_creditcard_currencies_value'])) {
			$this->data['op_creditcard_currencies_value'] = $this->request->post['op_creditcard_currencies_value'];
		} else {
			$this->data['op_creditcard_currencies_value'] = $this->config->get('op_creditcard_currencies_value');
		}
		
		
		

		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		if (isset($this->request->post['op_creditcard_country_array'])) {
			$this->data['op_creditcard_country_array'] = $this->request->post['op_creditcard_country_array'];
		} elseif ($this->config->has('op_creditcard_country_array')) {
			$this->data['op_creditcard_country_array'] = $this->config->get('op_creditcard_country_array');
		} else {
			$this->data['op_creditcard_country_array'] = array();
		}
		
		
		
		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/op_creditcard/callback';

		
		if (isset($this->request->post['op_creditcard_transaction'])) {
			$this->data['op_creditcard_transaction'] = $this->request->post['op_creditcard_transaction'];
		} else {
			$this->data['op_creditcard_transaction'] = $this->config->get('op_creditcard_transaction');
		}
		
		if (isset($this->request->post['op_creditcard_pay_mode'])) {
			$this->data['op_creditcard_pay_mode'] = $this->request->post['op_creditcard_pay_mode'];
		} else {
			$this->data['op_creditcard_pay_mode'] = $this->config->get('op_creditcard_pay_mode');
		}
		
		if (isset($this->request->post['op_creditcard_logs'])) {
			$this->data['op_creditcard_logs'] = $this->request->post['op_creditcard_logs'];
		} else {
			$this->data['op_creditcard_logs'] = $this->config->get('op_creditcard_logs');
		}
		
		if (isset($this->request->post['op_creditcard_default_order_status_id'])) {
			$this->data['op_creditcard_default_order_status_id'] = $this->request->post['op_creditcard_default_order_status_id'];
		} else {
			$this->data['op_creditcard_default_order_status_id'] = $this->config->get('op_creditcard_default_order_status_id'); 
		} 
		/* add status */
		if (isset($this->request->post['op_creditcard_success_order_status_id'])) {
			$this->data['op_creditcard_success_order_status_id'] = $this->request->post['op_creditcard_success_order_status_id'];
		} else {
			$this->data['op_creditcard_success_order_status_id'] = $this->config->get('op_creditcard_success_order_status_id'); 
		} 
		if (isset($this->request->post['op_creditcard_failed_order_status_id'])) {
			$this->data['op_creditcard_failed_order_status_id'] = $this->request->post['op_creditcard_failed_order_status_id'];
		} else {
			$this->data['op_creditcard_failed_order_status_id'] = $this->config->get('op_creditcard_failed_order_status_id'); 
		} 
		if (isset($this->request->post['op_creditcard_pending_order_status_id'])) {
			$this->data['op_creditcard_pending_order_status_id'] = $this->request->post['op_creditcard_pending_order_status_id'];
		} else {
			$this->data['op_creditcard_pending_order_status_id'] = $this->config->get('op_creditcard_pending_order_status_id');
		}
		
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['op_creditcard_geo_zone_id'])) {
			$this->data['op_creditcard_geo_zone_id'] = $this->request->post['op_creditcard_geo_zone_id'];
		} else {
			$this->data['op_creditcard_geo_zone_id'] = $this->config->get('op_creditcard_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		

		if (isset($this->request->post['op_creditcard_status'])) {
			$this->data['op_creditcard_status'] = $this->request->post['op_creditcard_status'];
		} else {
			$this->data['op_creditcard_status'] = $this->config->get('op_creditcard_status');
		}
		
		if (isset($this->request->post['op_creditcard_sort_order'])) {
			$this->data['op_creditcard_sort_order'] = $this->request->post['op_creditcard_sort_order'];
		} else {
			$this->data['op_creditcard_sort_order'] = $this->config->get('op_creditcard_sort_order');
		}
		
		if (isset($this->request->post['op_creditcard_location'])) {
          $data['op_creditcard_location'] = $this->request->post['op_creditcard_location'];
      } else {
          $data['op_creditcard_location'] = $this->config->get('op_creditcard_location');
      }

      if (isset($this->request->post['op_creditcard_locations'])) {
          $data['op_creditcard_locations'] = $this->request->post['op_creditcard_locations'];
      } else {
          $data['op_creditcard_locations'] = $this->config->get('op_creditcard_locations');
      }

      if (isset($this->request->post['op_creditcard_entity'])) {
          $data['op_creditcard_entity'] = $this->request->post['op_creditcard_entity'];
      } else {
          $data['op_creditcard_entity'] = $this->config->get('op_creditcard_entity');
      }

      if (isset($this->request->post['op_creditcard_entitys'])) {
          $data['op_creditcard_entitys'] = $this->request->post['op_creditcard_entitys'];
      } else {
          $data['op_creditcard_entitys'] = $this->config->get('op_creditcard_entitys');
      }

	  //存入session
      $this->session->data['op_creditcard_location'] = $data['op_creditcard_location'];
      $this->session->data['op_creditcard_locations'] = $data['op_creditcard_locations'];
      $this->session->data['op_creditcard_entity'] = $data['op_creditcard_entity'];
      $this->session->data['op_creditcard_entitys'] = $data['op_creditcard_entitys'];


		
		$this->template = 'payment/op_creditcard.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/op_creditcard')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['op_creditcard_account']) {
			$this->error['account'] = $this->language->get('error_account');
		}

		if (!$this->request->post['op_creditcard_terminal']) {
			$this->error['terminal'] = $this->language->get('error_terminal');
		}		
		
		if (!$this->request->post['op_creditcard_securecode']) {
			$this->error['securecode'] = $this->language->get('error_securecode');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>
