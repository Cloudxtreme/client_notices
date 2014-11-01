<?php
/**
 * Client Notices Plugin
 * 
 * @package blesta
 * @subpackage blesta.plugins.ClientNotices
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
class ClientWidget extends ClientNoticesController {

	/**
	 * Pre-action
	 */
	public function preAction() {
		parent::preAction();

		$this->requireLogin();
		
		$this->uses(array("Clients", "ClientNotices.ClientNotices"));
		
		// Restore structure view location of the admin portal
		$this->structure->setDefaultView(APPDIR);
		$this->structure->setView(null, $this->orig_structure_view);
		
		$this->client_id = $this->Session->read("blesta_client_id");
	
	}


	/**
	 * View client profile ticket widget
	 */
	public function index() {
	
		// Ensure a valid client was given
		if (!$this->isAjax()) {
			header($this->server_protocol . " 401 Unauthorized");
			exit();
		}
				
		$notice = $this->ClientNotices->getByClientId((int)$this->client_id);
			
		$this->set("notice",$notice);
		
		if ($this->isAjax())
			return $this->renderAjaxWidgetIfAsync();
	
	}


}
?>