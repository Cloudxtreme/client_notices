<?php
/**
 * Client Notices Plugin
 * 
 * @package blesta
 * @subpackage blesta.plugins.ClientNotices
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
class AdminMain extends ClientNoticesController {
	
	/**
	 * Setup
	 */ 
	public function preAction() {
		parent::preAction();
		
		$this->requireLogin();
		
		$this->uses(array("Clients", "ClientNotices.ClientNotices"));
		
		// Restore structure view location of the admin portal
		$this->structure->setDefaultView(APPDIR);
		$this->structure->setView(null, $this->orig_structure_view);
		
		$this->staff_id = $this->Session->read("blesta_staff_id");
	}
	
	/**
	 * Show notices listing
	 */
	public function index() {	

		$sort = (isset($this->get['sort']) ? $this->get['sort'] : "date_added");
		$order = (isset($this->get['order']) ? $this->get['order'] : "desc");

		// $this->set("client", $client);
		$this->set("notices", $this->ClientNotices->getAll(array($sort => $order)));
		$this->set("sort", $sort);
		$this->set("order", $order);
		$this->set("negate_order", ($order == "asc" ? "desc" : "asc"));
		
		return $this->renderAjaxWidgetIfAsync();
	}
	
	/**
	 * Creates a new document
	 */
	public function add() {
		// Get client or redirect if not given
		if (!isset($this->get[0]) || !($client = $this->Clients->get((int)$this->get[0])))
			$this->redirect($this->base_uri . "clients/");

		$vars = array();

		$notice = $this->ClientNotices->getByClientId((int)$this->get[0]);
		
		// print_r($notice);
		
		if (!empty($this->post)) {
			$this->post['client_id'] = $client->id;
			$this->ClientNotices->add($this->post);
			
			if (($errors = $this->ClientNotices->errors())) {
				// Error, reset vars
				$this->setMessage("error", $errors, false, null, false);
				$vars = (object)$this->post;
			}
			else {
				// Success
				$this->flashMessage("message", Language::_("AdminMain.!success.notice_added", true), null, false);
				$this->redirect($this->base_uri . "clients/view/" . $client->id);
			}
		}
		
		$this->set("notice", $notice);
		$this->set("client", $client);
		$this->set("vars", (object)$vars);
		
		// Include WYSIWYG
		$this->Javascript->setFile("ckeditor/ckeditor.js", "head", VENDORWEBDIR);
		$this->Javascript->setFile("ckeditor/adapters/jquery.js", "head", VENDORWEBDIR);
		
	}
	
	/**
	 * Creates a new document
	 */
	public function edit() {
		// Get client or redirect if not given //$notice = $this->get($notice_id)
		if (!isset($this->get[0]) || !($notice = $this->ClientNotices->get((int)$this->get[0])))
			$this->redirect($this->base_uri . "plugin/client_notices/admin_main/index/");

		$vars = (object)$notice;
		
		$client = $this->Clients->get((int)$notice->client_id) ;

		if (!empty($this->post)) {
			// $this->post['client_id'] = $client->id;
			$this->ClientNotices->edit($this->post);
			
			if (($errors = $this->ClientNotices->errors())) {
				// Error, reset vars
				$this->setMessage("error", $errors, false, null, false);
				$vars = (object)$this->post;
			}
			else {
				// Success
				$this->flashMessage("message", Language::_("AdminMain.!success.notice_edited", true), null, false);
				$this->redirect($this->base_uri . "plugin/client_notices/admin_main/index/");
			}
		}
		
		$this->set("client", $client);
		$this->set("vars", (object)$vars);
		
		// Include WYSIWYG
		$this->Javascript->setFile("ckeditor/ckeditor.js", "head", VENDORWEBDIR);
		$this->Javascript->setFile("ckeditor/adapters/jquery.js", "head", VENDORWEBDIR);
		
	}
	
	/**
	 * Deletes a document
	 */
	public function delete() {
		// Get document and client or redirect if not given
		if (!isset($this->post['id']) || !($notice = $this->ClientNotices->get($this->post['id'])) || !($client = $this->Clients->get($notice->client_id)))
			$this->redirect($this->base_uri . "clients/");
		
		$this->ClientNotices->delete($notice->id);
		
		if (($errors = $this->ClientNotices->errors()))
			$this->flashMessage("error", $errors, null, false);
		else
			$this->flashMessage("message", Language::_("AdminMain.!success.notice_deleted", true), null, false);
			
		$this->redirect($this->base_uri . "plugin/client_notices/admin_main/index/");
	}

}
?>