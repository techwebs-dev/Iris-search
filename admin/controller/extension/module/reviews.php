<?php
class ControllerExtensionModuleReviews extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/reviews');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('reviews', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}
			
			$this->cache->delete('product');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

        $data['text_order_last'] = $this->language->get('text_order_last');
        $data['text_order_random'] = $this->language->get('text_order_random');

        $data['text_horizontal'] = $this->language->get('text_horizontal');
        $data['text_vertical'] = $this->language->get('text_vertical');
        $data['text_slider'] = $this->language->get('text_slider');
        
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

		$data['entry_name'] = $this->language->get('entry_name');
        $data['entry_order_type'] = $this->language->get('entry_order_type');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_header'] = $this->language->get('entry_header');
		$data['entry_limit'] = $this->language->get('entry_limit');
        $data['entry_text_limit'] = $this->language->get('entry_text_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
        $data['entry_category_sensitive'] = $this->language->get('entry_category_sensitive');
        $data['entry_show_all_button'] = $this->language->get('entry_show_all_button');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

        if (isset($this->error['module_header'])) {
            $data['error_header'] = $this->error['module_header'];
        } else {
            $data['error_header'] = '';
        }

		if (isset($this->error['order_type'])) {
            $data['error_order_type'] = $this->error['order_type'];
        } else {
            $data['error_order_type'] = '';
        }

        if (isset($this->error['layout'])) {
            $data['error_layout'] = $this->error['layout'];
        } else {
            $data['error_layout'] = '';
        }

        if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}
		
		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/reviews', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/reviews', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);			
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/reviews', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/reviews', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

        if (isset($this->request->post['module_header'])) {
            $data['module_header'] = $this->request->post['module_header'];
        } elseif (!empty($module_info)) {
            $data['module_header'] = $module_info['module_header'];
        } else {
            $data['module_header'] = array();
        }

        if (isset($this->request->post['order_type'])) {
            $data['order_type'] = $this->request->post['order_type'];
        } elseif (!empty($module_info)) {
            $data['order_type'] = $module_info['order_type'];
        } else {
            $data['order_type'] = 'last';
        }

        if (isset($this->request->post['layout'])) {
            $data['layout'] = $this->request->post['layout'];
        } elseif (!empty($module_info)) {
            $data['layout'] = $module_info['layout'];
        } else {
            $data['layout'] = 'vertical';
        }

        if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}

        if (isset($this->request->post['text_limit'])) {
            $data['text_limit'] = $this->request->post['text_limit'];
        } elseif (!empty($module_info)) {
            $data['text_limit'] = $module_info['text_limit'];
        } else {
            $data['text_limit'] = 80;
        }

        if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}	
			
		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
		}		

        if (isset($this->request->post['category_sensitive'])){
            $data['category_sensitive'] = $this->request->post['category_sensitive'];
        } elseif (!empty($module_info)) {
            $data['category_sensitive'] = $module_info['category_sensitive'];
        } else {
            $data['category_sensitive'] = 1;
        }

        if (isset($this->request->post['show_all_button'])){
            $data['show_all_button'] = $this->request->post['show_all_button'];
        } elseif (!empty($module_info)) {
            $data['show_all_button'] = $module_info['show_all_button'];
        } else {
            $data['show_all_button'] = 1;
        }

        if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/reviews', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/reviews')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

        if (($this->request->post['order_type'] != 'random') && ($this->request->post['order_type'] != 'last')) {
            $this->error['order_type'] = $this->language->get('error_type');
        }

        if (($this->request->post['layout'] != 'vertical') && ($this->request->post['layout'] != 'horizontal') && ($this->request->post['layout'] != 'slider')) {
            $this->error['layout'] = $this->language->get('error_layout');
        }

//        foreach($this->request->post['module_header'] as $language_id => $value) {
//            if ((utf8_strlen($value) < 3) || (utf8_strlen($value) > 64)) {
//                $this->error['module_header'][$language_id] = $this->language->get('error_header');
//            }
//        }

        if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}
		
		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		return !$this->error;
	}
}