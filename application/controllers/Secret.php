<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Secret extends CI_Controller {

	public function index()
	{
	}
	public function secret_get() {
		$method = $_SERVER['REQUEST_METHOD'];
		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		if ($method == 'GET') {
			$result = $this->secret_model->result_after_get($this->uri->segment(3));
			if (strpos($requestContentType, 'application/json') !== false) {
				json_output($result['status'], $result['response']);
			} elseif (strpos($requestContentType, 'application/xml') !== false) {
				xml_output($result['status'], $result['response']);
			}
		} else {
			if (strpos($requestContentType, 'application/json') !== false) {
				json_output(400, array('message' => 'Bad request'));
			} elseif (strpos($requestContentType, 'application/xml') !== false) {
				xml_output(400, array('message' => 'Bad request'));
			}
		}
	}
	public function secret_put() {
		$method = $_SERVER['REQUEST_METHOD'];
		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		if ($method == 'POST') {
			$result = $this->secret_model->result_after_put($this->input->post());
			if (strpos($requestContentType, 'application/json') !== false) {
				json_output($result['status'], $result['response']);
			} elseif (strpos($requestContentType, 'application/xml') !== false) {
				xml_output($result['status'], $result['response']);
			}
		} else {
			if (strpos($requestContentType, 'application/json') !== false) {
				json_output(400, array('message' => 'Bad request'));
			} elseif (strpos($requestContentType, 'application/xml') !== false) {
				xml_output(400, array('message' => 'Bad request'));
			}
		}
	}
}
