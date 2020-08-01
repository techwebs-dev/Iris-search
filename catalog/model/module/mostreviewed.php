<?php
class ModelModuleMostReviewed extends Model {
	
	public function getMostReviewed($settings) {		
		
		$limit = $settings['limit'];		
		$product_data = array();
		
		if(!isset($limit) || $limit<0) return $product_data;
		
		$cachestring = 'product.mostreviewed' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.L' . (int)$limit;
				
		$product_data = $this->cache->get($cachestring);
		
		if (!$product_data) {
			$product_data = array();

			$sql = "SELECT r.product_id, COUNT(r.product_id) as TotalReviews FROM ";
			$sql = $sql . " " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id=p.product_id) ";
			$sql = $sql . " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) ";
			$sql = $sql . " WHERE r.`status` = '1' AND p.`status` = '1' AND p.date_available <= NOW() ";
			$sql = $sql . " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$sql = $sql . " GROUP BY r.product_id ORDER BY TotalReviews DESC LIMIT " . (int)$limit;
			
			$query = $this->db->query($sql);			
			
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
			}
			
			$this->cache->set($cachestring, $product_data);				
		}

		return $product_data;
	}
}
