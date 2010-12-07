<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


require_once PATH_THIRD.'pt_switch/config.php';


/**
 * P&T Switch Fieldtype Class for EE2
 *
 * @package   P&T Switch
 * @author    Brandon Kelly <brandon@pixelandtonic.com>
 * @copyright Copyright (c) 2010 Pixel & Tonic, LLC
 */
class Pt_switch_ft extends EE_Fieldtype {

	var $info = array(
		'name'    => PT_SWITCH_NAME,
		'version' => PT_SWITCH_VER
	);

	/**
	 * Fieldtype Constructor
	 */
	function Pt_switch_ft()
	{
		parent::EE_Fieldtype();

		/** ----------------------------------------
		/**  Prepare Cache
		/** ----------------------------------------*/

		if (! isset($this->EE->session->cache['pt_switch']))
		{
			$this->EE->session->cache['pt_switch'] = array('includes' => array());
		}
		$this->cache =& $this->EE->session->cache['pt_switch'];
	}

	// --------------------------------------------------------------------

	/**
	 * Theme URL
	 */
	private function _theme_url()
	{
		if (! isset($this->cache['theme_url']))
		{
			$theme_folder_url = $this->EE->config->item('theme_folder_url');
			if (substr($theme_folder_url, -1) != '/') $theme_folder_url .= '/';
			$this->cache['theme_url'] = $theme_folder_url.'third_party/pt_switch/';
		}

		return $this->cache['theme_url'];
	}

	/**
	 * Include Theme CSS
	 */
	private function _include_theme_css($file)
	{
		if (! in_array($file, $this->cache['includes']))
		{
			$this->cache['includes'][] = $file;
			$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.$this->_theme_url().$file.'" />');
		}
	}

	/**
	 * Include Theme JS
	 */
	private function _include_theme_js($file)
	{
		if (! in_array($file, $this->cache['includes']))
		{
			$this->cache['includes'][] = $file;
			$this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->_theme_url().$file.'"></script>');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Insert JS
	 */
	private function _insert_js($js)
	{
		$this->EE->cp->add_to_foot('<script type="text/javascript">'.$js.'</script>');
	}

	// --------------------------------------------------------------------

	/**
	 * Display Field Settings
	 */
	function display_settings($data)
	{
		$rows = $this->_field_settings($data);

		foreach ($rows as $row)
		{
			$this->EE->table->add_row($row[0], $row[1]);
		}
	}

	/**
	 * Display Cell Settings
	 */
	function display_cell_settings($data)
	{
		return $this->_field_settings($data, 'class="matrix-textarea"');
	}

	/**
	 * Display LV Settings
	 */
	function display_var_settings($data)
	{
		return $this->_field_settings($data);
	}

	/**
	 * Field Settings
	 */
	private function _field_settings($data, $attr = '')
	{
		// load the language file
		$this->EE->lang->loadfile('pt_switch');

		// merge in default field settings
		$data = array_merge(
			array(
				'off_label' => 'NO',
				'off_val'   => '',
				'on_label'  => 'YES',
				'on_val'    => 'y',
				'default'   => 'off'
			),
			$data
		);

		return array(
			// OFF Label
			array(
				lang('pt_switch_off_label', 'pt_switch_off_label'),
				form_input('pt_switch[off_label]', $data['off_label'], $attr)
			),

			// OFF Value
			array(
				lang('pt_switch_off_val', 'pt_switch_off_val'),
				form_input('pt_switch[off_val]', $data['off_val'], $attr)
			),

			// ON Label
			array(
				lang('pt_switch_on_label', 'pt_switch_on_label'),
				form_input('pt_switch[on_label]', $data['on_label'], $attr)
			),

			// ON Value
			array(
				lang('pt_switch_on_val', 'pt_switch_on_val'),
				form_input('pt_switch[on_val]', $data['on_val'], $attr)
			),

			// Default
			array(
				lang('pt_switch_default', 'pt_switch_default'),
				form_dropdown('pt_switch[default]', array('off' => 'OFF', 'on' => 'ON'), $data['default'])
			),
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Save Field Settings
	 */
	function save_settings($settings)
	{
		$settings = $this->EE->input->post('pt_switch');

		// cross the T's
		$settings['field_fmt'] = 'none';
		$settings['field_show_fmt'] = 'n';
		$settings['field_type'] = 'pt_switch';

		return $settings;
	}

	/**
	 * Save Cell Settings
	 */
	function save_cell_settings($settings)
	{
		return $settings['pt_switch'];
	}

	/**
	 * Save LV Settings
	 */
	function save_var_settings($settings)
	{
		return $this->EE->input->post('pt_switch');
	}

	// --------------------------------------------------------------------

	/**
	 * Display Field
	 */
	function display_field($data, $cell = FALSE)
	{
		$this->_include_theme_css('styles/pt_switch.css');
		$this->_include_theme_js('scripts/pt_switch.js');

		$field_name = $cell ? $this->cell_name : $this->field_name;
		$field_id = str_replace(array('[', ']'), array('_', ''), $field_name);

		if ($cell)
		{
			$new = (! isset($this->row_id));
		}
		else
		{
			$new = (! $this->EE->input->get('entry_id'));
			$this->_insert_js('new ptSwitch(jQuery("#'.$field_id.'"));');
		}

		$options = array(
			$this->settings['off_val'] => $this->settings['off_label'],
			$this->settings['on_val'] => $this->settings['on_label']
		);

		if ($new)
		{
			if (! isset($this->settings['default'])) $this->settings['default'] = 'off';
			$data = $this->settings[$this->settings['default'].'_val'];
		}

		return form_dropdown($field_name, $options, $data, 'id="'.$field_id.'"');
	}

	/**
	 * Display Cell
	 */
	function display_cell($data)
	{
		$this->_include_theme_js('scripts/matrix2.js');

		return $this->display_field($data, TRUE);
	}

	/**
	 * Display Var
	 */
	function display_var_field($data)
	{
		return $this->display_field($data);
	}
}
