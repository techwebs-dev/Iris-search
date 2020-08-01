<?php
class ControllerExtensionModulesearches extends Controller {
    public function index() {
        $this->load->language('product/search');

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        $this->load->language('extension/module/searches');
        $this->load->model('extension/module/searches');
        $real_url = $_SERVER['REQUEST_URI'];

        if (isset($this->request->get['search'])) {
            $data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['search'];
        } else {
            $data['heading_title'] = $this->language->get('heading_title');
        }

        $data['href'] = HTTP_SERVER . 'index.php?route=product/category';
        $data['lat_letters'] = array();
        $data['cyr_letters'] = array();
        $data['numbers'] = '';

        if (isset($this->request->get['route'])) {
            $route = $this->request->get['route'];
        } else {
            $route = '';
        }

            if ((isset($this->request->get['path']) && $id = $this->request->get['path']) || (isset($this->request->get['category']) && $id = $this->request->get['category'])) {
                $data['href'] .= '&category='.$id;
                $parts = explode('_', $id);
                $category_id = array_pop($parts);
            } else {
                $category_id = false;
            }
            if ((isset($this->request->get['manufacturer_id']) && $id = $this->request->get['manufacturer_id']) || (isset($this->request->get['manufacturer']) && $id = $this->request->get['manufacturer'])) {
                $data['href'] .= '&manufacturer='.$id;
                $manufacturer_id = $id;
            } else {
                $manufacturer_id = false;
            }
            if (isset($this->request->get['filter_name'])) {
                $data['href'] .= '&filter_name='.$this->request->get['filter_name'];
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = false;
            }
            if (isset($this->request->get['search'])) {
                $data['href'] .= '&search='.$this->request->get['search'];
                $filter_name = $this->request->get['search'];
            } else {
                $filter_name = false;
            }
            if (isset($this->request->get['filter_tag'])) {
                $data['href'] .= '&filter_tag='.$this->request->get['filter_tag'];
                $filter_tag = $this->request->get['filter_tag'];
            } else {
                $filter_tag = false;
            }

            //dlya poiska
            if (isset($this->request->get['description'])) {
                $data['href'] .= '&description=' . $this->request->get['description'];
                $filter_description = $this->request->get['description'];
            } else {
                $filter_description = false;
            }
            if (isset($this->request->get['category_id'])) {
                $data['href'] .= '&category_id=' . $this->request->get['category_id'];
                $category_id = $this->request->get['category_id'];
            }
            if (isset($this->request->get['sub_category'])) {
                $data['href'] .= '&sub_category=' . $this->request->get['sub_category'];
                $filter_sub_category = $this->request->get['sub_category'];
            } else {
                $filter_sub_category = false;
            }

            if ($route == 'product/special' || isset($this->request->get['special'])) {
                $data['href'] = $data['href'].'&special=1';
                $special = true;
            } else {
                $special = false;
            }

        if (isset($this->request->get['sort'])) {
            $data['href'] .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $data['href'] .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['limit'])) {
            $data['href'] .= '&limit=' . $this->request->get['limit'];
        }

        $data['href'] .= '&char=';
                $data['lat_letters'] = array(
                    'A', 'B', 'C', 'D', 'E', 'F', 'G',
                    'H', 'I', 'J', 'K', 'L', 'M', 'N',
                    'O', 'P', 'Q', 'R', 'S', 'T', 'U',
                    'W', 'X', 'Y', 'Z'
                );
                $data['cyr_letters'] = array(
                    'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж',
                    'З', 'И', 'К', 'Л', 'М', 'Н', 'О',
                    'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х',
                    'Ц', 'Ч', 'Ш', 'Щ', 'Э', 'Ю', 'Я'
                );
                $data['numbers'] = '0-9';


        $path = '';
        $parts = explode('_', (string)$this->request->get['path']);

        $category_id = (int)array_pop($parts);

        $data['category'] = $category_id;

        $data['back'] = rtrim( dirname( $real_url ), "/" )."/category&path=".$data['category'];
        return $this->load->view('extension/module/searches', $data);
    }

}