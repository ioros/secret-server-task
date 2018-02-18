<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Secret_model extends CI_Model {
	/**
	 * GET
	 */
	function result_after_get($hash) {
		$result = array();
		if ($hash) {
			$this->db->where('hash', $hash);
			$this->db->where('remainingViews > 0');
			$this->db->where('expiresAt > now()');
			$q = $this->db->get('secrets');
			if ($q->num_rows() > 0) {
				$r = $q->row();
				$result['status'] = 200;
				$result['response'] = array(
					'hash' => $r->hash,
					'secretText' => $r->secretText,
					'createdAt' => $r->createdAt,
					'expiresAt' => $r->expiresAt,
					'remainingViews' => $r->remainingViews,
				);
				//megtekintések csökkentése
				$this->db->query('update secrets set remainingViews = remainingViews - 1 where hash = ?', array($hash));
			} else {
				$result['status'] = 404;
				$result['response'] = array('message'=>"Secret not found");
			}
		} else {
			$result['status'] = 405;
			$result['response'] = array('message'=>"Invalid input");
		}
		return $result;
	}
	
	/**
	 * POST
	 */
	function result_after_put($input) {
		$result = array();
		if (!isset($input['secret']) || !isset($input['expireAfterViews']) || !isset($input['expireAfter'])) {
			$result['status'] = 405;
			$result['response'] = array('message'=>"Invalid input");
		} else {
			$secret = $input['secret'];
			$expireAfterViews = $input['expireAfterViews'];
			$expireAfter = $input['expireAfter'];
			if (is_int($expireAfterViews) == false) {
				$result['status'] = 405;
				$result['response'] = array('message'=>"Invalid input");
			} elseif ($expireAfterViews <= 0) {
				$result['status'] = 405;
				$result['response'] = array('message'=>"Invalid input");
			} elseif (is_int($expireAfter) == false) {
				$result['status'] = 405;
				$result['response'] = array('message'=>"Invalid input");
			} elseif ($expireAfter < 0) {
				$result['status'] = 405;
				$result['response'] = array('message'=>"Invalid input");
			} else {
				$hash = uniqid();
				$now = time();
				$response = array(
					'hash' => $hash,
					'secretText' => $secret,
					'createdAt' => date('Y-m-d H:i:s', $now),
					'expiresAt' => $expireAfter ? date('Y-m-d H:i:s', mktime(date('H', $now), date('i', $now) + $expireAfter, date('s', $now), date('n', $now), date('j', $now), date('y', $now))) : "9999-12-31 23:59:59",
					'remainingViews' => $expireAfterViews,
				);
				$this->db->insert('secrets', $response);
				$result['status'] = 200;
				$result['response'] = $response;
			}
		}
		return $result;
	}
}