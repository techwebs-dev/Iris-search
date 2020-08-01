<?php
class Pagination {
	public $total = 0;
	public $page = 1;
	public $limit = 20;
	public $num_links = 8;
	public $url = '';
	public $text_first = '|&lt;';
	public $text_last = '&gt;|';
	public $text_next = '&gt;';
	public $text_prev = '&lt;';

	public function render() {
		$total = $this->total;

		if ($this->page < 1) {
			$page = 1;
		} else {
			$page = $this->page;
		}

		if (!(int)$this->limit) {
			$limit = 10;
		} else {
			$limit = $this->limit;
		}

		$num_links = $this->num_links;
		$num_pages = ceil($total / $limit);

		$this->url = str_replace('%7Bpage%7D', '{page}', $this->url);

		$output = '<ul class="catalog__pagination  pagination">';

        if ($page > 1) {
            if ($page - 1 === 1) {
                $output .= '<li class="pagination__item  pagination__item--prev"><a href="' . str_replace(array('&amp;page={page}', '?page={page}', '&page={page}'), '', $this->url) . '">' . $this->text_prev . '</a></li>';
            } else {
                $output .= '<li class="ftertert"><a href="' . str_replace('{page}', $page - 1, $this->url) . '">' . $this->text_prev . '</a></li>';
            }
        }
        else{
            $output .= '<li class="pagination__item  pagination__item--prev pagination__item--disabled"><span>' . $this->text_prev . '</span></li>';
        }

		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor($num_links / 2);
				$end = $page + floor($num_links / 2);

				if ($start < 1) {
					$end += abs($start) + 1;
					$start = 1;
				}

				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}

			for ($i = $start; $i <= $end; $i++) {
				if ($page == $i) {
					$output .= '<li class="pagination__item pagination__item--active active"><span>' . $i . '</span></li>';
				} else {
					if ($i === 1) {
						$output .= '<li class="pagination__item four"><a href="' . str_replace(array('&amp;page={page}', '?page={page}', '&page={page}'), '', $this->url) . '">' . $i . '</a></li>';
					} else {
						$output .= '<li class="pagination__item five"><a href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a></li>';
					}
				}
			}
		}

		if ($page < $num_pages) {
			$output .= '<li class="pagination__item pagination__item--next six"><a href="' . str_replace('{page}', $page + 1, $this->url) . '">' . $this->text_next . '</a></li>';
		}
		else{
            $output .= '<li class="pagination__item--disabled pagination__item--next pagination__item"><span>' . $this->text_next . '</span></li>';
        }

		$output .= '</ul>';

		if ($num_pages > 1) {
			return $output;
		} else {
			return '';
		}
	}
}
