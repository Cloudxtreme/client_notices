<?php
/**
 * Client Notices Plugin
 * 
 * @package blesta
 * @subpackage blesta.plugins.ClientNotices
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
class ClientNotices extends ClientNoticesModel {
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		
		Language::loadLang("client_notices", null, PLUGINDIR . "client_notices" . DS . "language" . DS);
	}
	
	/**
	 * Adds a new notice
	 */
	public function add(array $vars) {
		
		$vars['date_added'] = date("c");
		
		$this->Input->setRules($this->getRules($vars));
		
		if ($this->Input->validates($vars)) {
		
			$fields = array("client_id", "notice_name", "notice_body", "date_added");
			$this->Record->insert("client_notices", $vars, $fields);
			return $this->Record->lastInsertId();
			
		}
	}
	
	/**
	 * Edit a notice
	 */
	public function edit(array $vars) {
		
		$vars['date_added'] = date("c");
		
		$this->Input->setRules($this->getRules($vars , $edit = true));
		
		if ($this->Input->validates($vars)) {
			
			$fields = array("client_id", "notice_name", "notice_body", "date_added");
			$this->Record->where("id", "=", $vars['id'])->update("client_notices", $vars, $fields);

			return $this->Record->lastInsertId();
			
		}
	}	
	
	
	/**
	 * Deletes a notice
	 */
	public function delete($notice_id) {
		$notice = $this->get($notice_id);
		
		if ($notice) {
			// Remove from DB
			$this->Record->from("client_notices")->
				where("client_notices.id", "=", $notice_id)->delete();
		}
	}
	
	/**
	 * Retrieves a notice
	 */
	public function get($notice_id) {
		return $this->Record->select()->from("client_notices")->
			where("client_notices.id", "=", $notice_id)->fetch();
	}
	
	/**
	 * Retrieves a notice by client_id
	 */
	public function getByClientId($client_id) {
		return $this->Record->select()->from("client_notices")->
			where("client_notices.client_id", "=", $client_id)->fetch();
	}	
	/**
	 * Retrieves all notices 
	 *
	 */
	public function getAll($order_by = array('date_added' => "desc")) {
		return $this->Record->select()->from("client_notices")->
			order($order_by)->
			fetchAll();
	}
	
	/**
	 * Return rules required for validating notices
	 */
	private function getRules(array $vars, $edit = false) {
		$rules = array(
			'client_id' => array(
				'exists' => array(
					'rule' => array(array($this, "validateExists"), "id", "clients"),
					'message' => Language::_("ClientNotices.!error.client_id.exists", true)
				)
			),
			'notice_name' => array(
				'valid' => array(
					'rule' => "isEmpty",
					'negate' => true,
					'message' => Language::_("ClientNotices.!error.name.valid", true)
				)
			),
			'notice_body' => array(
				'valid' => array(
					'rule' => "isEmpty",
					'negate' => true,
					'message' => Language::_("ClientNotices.!error.body.valid", true)
				)
			),			
			'date_added' => array(
				'valid' => array(
					'rule' => "isDate",
					'post_format' => array(array($this, "dateToUtc")),
					'message' => Language::_("ClientNotices.!error.date_added.valid", true)
				)
			)
		);
		
		if ($edit) {
			// No need to validate client on edit; client can't be changed
			unset($rules['client_id']);
		}
		
		return $rules;
	}

}
?>