<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module Creator
 *
 * @package		Module Creator
 * @subpackage	ThirdParty
 * @category	Modules
 * @author		Bjorn Borresen
 * @link		http://ee.bybjorn.com/module_creator
 */
class Module_creator {

	var $return_data;
	
	function Module_creator()
	{		
		$this->EE =& get_instance(); // Make a local reference to the ExpressionEngine super object
	}		
}

/* End of file mod.module_creator.php */ 
/* Location: ./system/expressionengine/third_party/module_creator/mod.module_creator.php */ 