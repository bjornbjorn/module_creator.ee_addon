<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module Creator CP
 *
 * @package		Module Creator
 * @subpackage	ThirdParty
 * @category	Modules
 * @author		Bjorn Borresen
 * @link		http://ee.bybjorn.com/module_creator
 */
class Module_creator_mcp 
{
	var $base;			// the base url for this module			
	var $form_base;		// base url for forms
	var $module_name;	

	function Module_creator_mcp( $switch = TRUE )
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
		$this->module_name = strtolower(str_replace('_mcp', '', get_class($this)));
		$this->base	 	 = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name;
		$this->form_base = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name;

/*		$this->EE->cp->set_right_nav(array(
				'openid_report'			=> $this->base.AMP.'method=report',
				'openid_list_members'	=> $this->base.AMP.'method=list_members',
				'openid_unit_tests'		=> $this->base.AMP.'method=unit_tests',
				'openid_settings'		=> BASE.AMP.'C=addons_extensions&M=extension_settings&file=openid',
			));	
*/		
		
		//  Onward!
	}

	function index() 
	{
		return $this->content_wrapper('index', 'create_module');
	}
	
	function create_module()
	{
		$new_module_name = strtolower($this->EE->input->post('module_name'));
		$module_human_name = $this->EE->input->post('module_human_name');
		$module_description = $this->EE->input->post('module_description');
		$module_author = $this->EE->input->post('module_author');
		$module_link = $this->EE->input->post('module_link');
		$has_backend = $this->EE->input->post('has_backend');
		
		if(!file_exists(PATH_THIRD.'/'.$new_module_name))
		{
			if($new_module_name != '' && $module_human_name != '' && $module_description != '')
			{
				mkdir(PATH_THIRD.'/'.$new_module_name);
				mkdir(PATH_THIRD.'/'.$new_module_name.'/language');
				mkdir(PATH_THIRD.'/'.$new_module_name.'/language/english');
				
				$replace_arr = array(
					'UCFIRST_MODULE_NAME' => ucfirst($new_module_name),					
					'MODULE_NAME' => $new_module_name,
					'MODULE_HUMAN_NAME' => $module_human_name,
					'MODULE_DESCRIPTION' => $module_description,
					'MODULE_HAS_BACKEND' => $has_backend,
					'MODULE_AUTHOR' => $module_author,
					'MODULE_LINK' => $module_link, 
				);
				
				$this->writeFile(PATH_THIRD.'/'.$new_module_name.'/language/english/lang.'.$new_module_name.'.php', $replace_arr, PATH_THIRD.$this->module_name.'/prototype/language/english/lang.php');
				$this->writeFile(PATH_THIRD.'/'.$new_module_name.'/upd.'.$new_module_name.'.php', $replace_arr, PATH_THIRD.$this->module_name.'/prototype/upd.php');
				$this->writeFile(PATH_THIRD.'/'.$new_module_name.'/mcp.'.$new_module_name.'.php', $replace_arr, PATH_THIRD.$this->module_name.'/prototype/mcp.php');
				$this->writeFile(PATH_THIRD.'/'.$new_module_name.'/mod.'.$new_module_name.'.php', $replace_arr, PATH_THIRD.$this->module_name.'/prototype/mod.php');

				if($has_backend == 'y')
				{
					mkdir(PATH_THIRD.'/'.$new_module_name.'/views');					
					$this->writeFile(PATH_THIRD.'/'.$new_module_name.'/views/_wrapper.php', $replace_arr, PATH_THIRD.$this->module_name.'/prototype/views/_wrapper.php');
					$this->writeFile(PATH_THIRD.'/'.$new_module_name.'/views/index.php', $replace_arr, PATH_THIRD.$this->module_name.'/prototype/views/index.php');					
				}
				
				
				return $this->content_wrapper('finito', 'module_created');
			}
			else
			{
				show_error("Information missing; you must fill out the required fields");	
			}						
		}
		else
		{
			show_error("A module by that name already exists, delete it or choose another name.");
		}
	}
	
	function writeFile($target_file, $replace_arr, $template_file )
	{
		if(file_exists($template_file))
		{
			$tpl_str = file_get_contents($template_file);
			foreach($replace_arr as $key => $value)
			{
				$tpl_str = str_replace($key, $value, $tpl_str);
			}
			
			if(!file_put_contents($target_file, $tpl_str ))
			{
				show_error("Could not write to file: $target_file");
			}
			
		}
		else
		{
			show_error("Could not find template: ". $template_file);
		}		
		
	}

	
	function content_wrapper($content_view, $lang_key, $vars = array())
	{
		$vars['content_view'] = $content_view;
		$vars['_base'] = $this->base;
		$vars['_form_base'] = $this->form_base;
		$this->EE->cp->set_variable('cp_page_title', lang($lang_key));
		$this->EE->cp->set_breadcrumb($this->base, lang('module_creator_module_name'));

		return $this->EE->load->view('_wrapper', $vars, TRUE);
	}
	
}

/* End of file mcp.module_creator.php */ 
/* Location: ./system/expressionengine/third_party/module_creator/mcp.module_creator.php */ 