<?php
class ControllerExtensionModuleprostofilter extends Controller {
	public function index() {
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		$category_id = end($parts);
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
        } else {
            $filter = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int)$this->request->get['limit'];
        } else {
            $limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

		$category_info = $this->model_catalog_category->getCategory($category_id);
		if ($category_info) {
			$this->load->language('extension/module/prostofilter');

			$url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

            $data['categories'] = array();

            $results = $this->model_catalog_category->getCategories($category_id);

            foreach ($results as $result) {
                $filter_data = array(
                    'filter_category_id'  => $result['category_id'],
                    'filter_sub_category' => true
                );

                $data['categories'][] = array(
                    'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
                );
            }

            $products = $this->model_catalog_product->getTotalProducts($data);
            if($products == 0) {
                $this->data['display'] = 'none';
            }

            $data['products'] = array();

            $filter_data = array(
                'filter_category_id' => $category_id,
                'filter_filter'      => $filter,
                'sort'               => $sort,
                'order'              => $order,
                'start'              => ($page - 1) * $limit,
                'limit'              => $limit
            );

            $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = false;
                }

                $cart = false;
                foreach ($this->cart->getProducts() as $product) {
                    if ($product['product_id'] == $result['product_id']) {
                        $cart = true;
                        break;
                    }
                }

                $data['products'][] = array(
                    'product_id'  => $result['product_id'],
                    'name'        => $result['name'],
                    'price'       => $price,
                    'cart'       => $cart,
                    'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
                );
            }

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['sorts'] = array();

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
            );

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $data['limits'] = array();
            $limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 9, 15, 30));

            sort($limits);

            foreach($limits as $value) {
                $data['limits'][] = array(
                    'text'  => $value,
                    'value' => $value,
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
                );
            }

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

            if ($page == 1) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id']), 'canonical');
            } else {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. $page), 'canonical');
            }

            if ($page > 1) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
            }

            if ($limit && ceil($product_total / $limit) > $page) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page + 1)), 'next');
            }

            $data['sort'] = $sort;
            $data['order'] = $order;
            $data['limit'] = $limit;

			$data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url));

			if (isset($this->request->get['filter'])) {
				$data['prostofilter_category'] = explode(',', $this->request->get['filter']);
			} else {
				$data['prostofilter_category'] = array();
			}

			$this->load->model('catalog/product');

			$data['prostofilter_groups'] = array();

			$prostofilter_groups = $this->model_catalog_category->getCategoryFilters($category_id);

			if ($prostofilter_groups) {
				foreach ($prostofilter_groups as $prostofilter_group) {
					if ($prostofilter_group['name'] == 'Цена') {
						if ($prostofilter_group['filter'][0]['filter_id'] == $prostofilter_group['filter'][0]['name']) {
							continue;
						}
					}
					$childen_data = array();

					foreach ($prostofilter_group['filter'] as $prostofilter) {
						$prostofilter_data = array(
							'filter_category_id' => $category_id,
							'filter_filter'      => $prostofilter['filter_id']
						);

						$childen_data[] = array(
							'prostofilter_id' => $prostofilter['filter_id'],
							'name'      => $prostofilter['name']
						);
					}

					$data['prostofilter_groups'][] = array(
						'prostofilter_group_id' => $prostofilter_group['filter_group_id'],
						'name'            => $prostofilter_group['name'],
						'prostofilter'          => $childen_data
					);
				}
				if (count($data['prostofilter_groups']) > 0) {
					return $this->load->view('extension/module/prostofilter', $data);
				}
			}
		}else {
            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }
        }

	}
}