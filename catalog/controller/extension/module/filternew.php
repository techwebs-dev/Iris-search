<?php
class ControllerExtensionModulefilternew extends Controller {
	public function index($setting)
    {
        $this->load->language('extension/module/filternew');

        $this->load->model('extension/module/filternew');

        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string)$this->request->get['path']);
        } else {
            $parts = array();
        }

        $category_id = end($parts);

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

            $this->document->addStyle('/catalog/view/javascript/jquery/pricefilter/jquery.nstSlider.min.css');
            $data['heading_title'] = $this->language->get('heading_title');

            if (isset($this->request->get['path'])) {
                $url = '';

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['limit'])) {
                    $url .= '&limit=' . $this->request->get['limit'];
                }

                $path = '';

                $parts = explode('_', (string)$this->request->get['path']);

                $category_id = (int)array_pop($parts);

                foreach ($parts as $path_id) {
                    if (!$path) {
                        $path = (int)$path_id;
                    } else {
                        $path .= '_' . (int)$path_id;
                    }

                    $category_info = $this->model_catalog_category->getCategory($path_id);

                    if ($category_info) {
                        $this->data['breadcrumbs'][] = array(
                            'text' => $category_info['name'],
                            'href' => $this->url->link('product/category', 'path=' . $path . $url),
                            'separator' => $this->language->get('text_separator')
                        );
                    }
                }
            } else {
                $category_id = 0;
            }

            $data = array(
                'filter_category_id' => $category_id,
            );

            $data['prices'] = $this->model_catalog_product->getProductsPrice($data);
            $products = $this->model_catalog_product->getTotalProducts($data);
            if ($products == 0) {
                $this->data['display'] = 'none';
            }
            $data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url));

            if (isset($this->request->get['p_low'])) {
                $data['p_low'] = $this->request->get['p_low'];
            } else {
                $data['p_low'] = $data['prices']['minp'];
            }

            if (isset($this->request->get['p_high'])) {
                $data['p_high'] = $this->request->get['p_high'];
            } else {
                $data['p_high'] = $data['prices']['maxp'];
            }

            /* filter end */
            return $this->load->view('extension/module/filternew', $data);
    }
}