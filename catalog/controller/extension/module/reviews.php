<?php
class ControllerExtensionModuleReviews extends Controller {
    public function index($setting) {
        $this->load->language('extension/module/reviews');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $this->load->model('catalog/reviews');
        
        $data['module'] = 'reviews';

        $data['module_header'] = $setting['module_header'][$this->config->get('config_language_id')];

        $data['reviews'] = array();

        $limit = $setting['limit'] > 0 ? $setting['limit'] : 4;

        $text_limit = $setting['text_limit'] > 0 ? $setting['text_limit'] : 50;

        if ($setting['category_sensitive'] && !empty($this->request->get['path'])){
            $categories = explode('_', $this->request->get['path']);
            $category_id = (int) array_pop($categories);
        } else {
            $category_id = 0;
        }

        if ($setting['order_type'] == 'last') {
            $results = $this->model_catalog_reviews->getLatestReviews($limit, $category_id);
        } else {
            $results = $this->model_catalog_reviews->getRandomReviews($limit, $category_id);
        }

        foreach ($results as $result) {
            if ($this->config->get('config_review_status')) {
                $rating = $result['rating'];
            } else {
                $rating = false;
            }

            $product_id = false;
            $product = false;
            $prod_thumb = false;
            $prod_name = false;
            $prod_model = false;
            $prod_href = false;

            if ($result['product_id']) {
                $product = $this->model_catalog_product->getProduct($result['product_id']);
                if ($product['image']) {
                    $prod_thumb = $this->model_tool_image->resize($product['image'], $setting['width'], $setting['height']);
                }
                $product_id = $product['product_id'];
                $prod_name = $product['name'];
                $prod_model = $product['model'];
                $prod_href = $this->url->link('product/product', 'product_id=' . $product['product_id']);
            }

            $data['reviews'][] = array(
                'review_id'   => $result['review_id'],
                'rating'      => $rating,
                'description' => mb_substr($result['text'], 0, $text_limit,'utf-8') . ' ...',
                'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'href'        => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                'author'      => $result['author'],
                'product_id'  => $product_id,
                'prod_thumb'  => $prod_thumb,
                'prod_name'   => $prod_name,
                'prod_model'  => $prod_model,
                'prod_href'   => $prod_href
            );
        }

        $data['link_all_reviews'] = $this->url->link('product/reviews');
        $data['text_all_reviews'] = $this->language->get('text_all_reviews');
        $data['show_all_button']  = $setting['show_all_button'];

        $template_name = $this->getTemplateName($setting['layout']);
		
		return $this->load->view('extension/module' . $template_name, $data);
    }

    private function getTemplateName($layout) {
    	if ($layout === 'vertical') {
    		return '/reviews_v';
    	} elseif ($layout === 'slider') {
    		return '/reviews_s';
    	} else {
    		return '/reviews_h';
    	}
    }
}