<?php
defined('BASEPATH') OR exit('No direct script access allowed');


	function xml_output($statusHeader,$response)
	{
		$ci =& get_instance();
		$ci->output->set_content_type('application/xml');
		$ci->output->set_status_header($statusHeader);
		$xml = new SimpleXMLElement('<secret/>');
		$a = array_flip($response);
		array_walk_recursive($a, array ($xml, 'addChild'));
		$ci->output->set_output($xml->asXML());
	}

