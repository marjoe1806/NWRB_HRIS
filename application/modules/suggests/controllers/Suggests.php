<?php

class Suggests extends MX_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->module('session');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		$post = $this->input->post();
		$model = 'tbl' . $post['model'];
		$param_val = @$post['param_val'];
		$param_type = @$post['param_type'];

		$limit = 10;
		if (isset($param_type) && $param_type == 'person' && $model == 'tblemployees') {
			$where = "DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id) LIKE '%" . $param_val . "%' OR DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id) LIKE '%" . $param_val . "%'";
			$select = array(
				"id",
				"DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) as employee_number",
				"DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id) as first_name",
				"DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id) as last_name"
			);
			$order_by[] = array('first_name', 'ASC');
			$order_by[] = array('last_name', 'ASC');
		} else if(isset($param_type) && $param_type == 'person') {
			$where = "`first_name` LIKE '%" . $param_val . "%' OR `last_name` LIKE '%" . $param_val . "%'";
			$select = array('first_name', 'last_name');
			$order_by[] = array('first_name', 'ASC');
		} else {
			$where = " `name` LIKE '%" . $param_val . "%'";
			$select = array('name', 'id');
			$order_by[] = array('name', 'ASC');
		}


		$this->db->select(implode(', ', $select));
		$this->db->from($model);
		$this->db->where($where);
		foreach($order_by as $key => $value) {
			$this->db->order_by($value[0], $value[1]);
		}
		$this->db->limit($limit);

		$results = $this->db->get()->result_array();

		exit(json_encode($results));
	}
}
