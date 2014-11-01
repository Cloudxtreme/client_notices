<?php
/**
 * Client Notices Plugin
 * 
 * @package blesta
 * @subpackage blesta.plugins.ClientNotices
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
class ClientNoticesController extends AppController {
	/**
	 * Setup
	 */
	public function preAction() {
		$this->structure->setDefaultView(APPDIR);
		parent::preAction();
		
		// Auto load language for the controller
		Language::loadLang(array(Loader::fromCamelCase(get_class($this))), null, dirname(__FILE__) . DS . "language" . DS);
		
		// Override default view directory
		$this->view->view = "default";
		$this->orig_structure_view = $this->structure->view;
		$this->structure->view = "default";
	}
}
?>