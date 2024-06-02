<?php
class Mypagination extends CI_Model {	
	
	public function getConfig($base_url,$total_row,$suffix = null) {
		$config = array();
		$config["base_url"] = $base_url;//base_url() . "temparchives/temparchive/index"
		$config["total_rows"] = $total_row;
		$config['suffix'] = $suffix;
		$config["per_page"] = 10;
		$config["uri_segment"] = 4;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] = 2;
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		$config['first_url'] = $base_url.$suffix;
		return $config;
	}
	public function getUserConfig($base_url,$total_row,$suffix = null) {
		$config = array();
		$config["base_url"] = $base_url;//base_url() . "temparchives/temparchive/index"
		$config["total_rows"] = $total_row;
		$config['suffix'] = $suffix;
		$config["per_page"] = 12;
		$config["uri_segment"] = 4;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] = 2;
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		$config['first_url'] = $base_url.$suffix;
		return $config;
	}
}
?>