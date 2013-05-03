<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
require_once dirname(__FILE__) . '/ktable.php'; 
/**
 * Users Table class
 */
class UvelirTableOrder_statuses extends UvelirTableKtable
{
    
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__uvelir_order_statuses', 'id', $db);
	}
}
