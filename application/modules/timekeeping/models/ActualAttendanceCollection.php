<?php
	class ActualAttendanceCollection extends Helper {

		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}

	}
?>