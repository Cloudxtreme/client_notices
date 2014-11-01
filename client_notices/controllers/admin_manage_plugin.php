<?php
/**
 * Client Notices Plugin
 * 
 * @package blesta
 * @subpackage blesta.plugins.ClientNotices
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
 
class AdminManagePlugin extends AppController {
	
	/**
	 * Performs necessary initialization
	 */
	private function init() {
		// Require login
		$this->parent->requireLogin();
		
	}
	
	/**
	 * Returns the view to be rendered when managing this plugin
	 */
	public function index() {
		$this->init();
		$this->redirect($this->base_uri . "plugin/client_notices/admin_main/index/");
	}
}	
?>