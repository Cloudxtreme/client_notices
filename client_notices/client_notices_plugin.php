<?php
/**
 * Client Notices Plugin
 * 
 * @package blesta
 * @subpackage blesta.plugins.ClientNotices
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
class ClientNoticesPlugin extends Plugin {
	
	public function __construct() {
		Language::loadLang("client_notices_plugin", null, dirname(__FILE__) . DS . "language" . DS);
		$this->loadConfig(dirname(__FILE__) . DS . "config.json");
	}
	
	/**
	 * Performs any necessary bootstraping actions
	 *
	 * @param int $plugin_id The ID of the plugin being installed
	 */
	public function install($plugin_id) {
		
		if (!isset($this->Record))
			Loader::loadComponents($this, array("Record"));
		
		// Add tables, *IFF* not already added
		try {
			// download_files
			$this->Record->
				setField("id", array('type'=>"int", 'size'=>10, 'unsigned'=>true, 'auto_increment'=>true))->
				setField("client_id", array('type'=>"int", 'size'=>10, 'unsigned'=>true))->
				setField("notice_name", array('type'=>"varchar", 'size'=>255))->
				setField("notice_body", array('type'=>"mediumtext"))->
				setField("date_added", array('type'=>"datetime"))->
				setKey(array("id , client_id"), "primary")->
				setKey(array("id"), "index")->
				create("client_notices", true);				

		}
		catch (Exception $e) {
			// Error adding... no permission?
			$this->Input->setErrors(array('db'=> array('create'=>$e->getMessage())));
			return;
		}
	}
	
	/**
	 * Performs any necessary cleanup actions
	 *
	 * @param int $plugin_id The ID of the plugin being uninstalled
	 * @param boolean $last_instance True if $plugin_id is the last instance across all companies for this plugin, false otherwise
	 */
	public function uninstall($plugin_id, $last_instance) {
		
		if ($last_instance) {
			if (!isset($this->Record))
				Loader::loadComponents($this, array("Record"));

			$this->Record->drop("client_notices");
		}
	}
	
	/**
	 * Returns all actions to be configured for this widget (invoked after install() or upgrade(), overwrites all existing actions)
	 *
	 * @return array A numerically indexed array containing:
	 * 	- action The action to register for
	 * 	- uri The URI to be invoked for the given action
	 * 	- name The name to represent the action (can be language definition)
	 * 	- options An array of key/value pair options for the given action
	 */
	public function getActions() {
		return array(
			// Client Nav
			array(
				'action'=>"widget_client_home",
				'uri'=>"plugin/client_notices/client_widget/",
				'name'=>Language::_("ClientNoticesPlugin.widget_client_home.index", true)
			),
			//  Staff Nav
            array(
                'action' => "nav_secondary_staff",
                'uri' => "plugin/client_notices/admin_main/index/",
                'name' => Language::_("ClientNoticesPlugin.nav_secondary_staff.index", true),
                'options' => array(
					'parent' => "clients/"
				)
            ),
			// Client Profile Action Link
			array(
				'action' => "action_staff_client",
				'uri' => "plugin/client_notices/admin_main/add/",
				'name' => Language::_("ClientNoticesPlugin.action_staff_client.add", true),
				'options' => array(
					'class' => "invoice"
				)
			)
		);
	}
}
?>