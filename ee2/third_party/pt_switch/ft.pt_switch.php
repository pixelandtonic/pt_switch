<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Pt_switch_ft extends EE_Fieldtype {

	var $info = array(
		'name'    => 'P&amp;T Switch',
		'version' => '1.0'
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
			$this->EE->session->cache['pt_switch'] = array();
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
			$this->cache['theme_url'] = $theme_folder_url.'pt_switch/';
		}

		return $this->cache['theme_url'];
	}

	/**
	 * Include Theme CSS
	 */
	private function _include_theme_css($file)
	{
		$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.$this->_theme_url().$file.'" />');
	}

	/**
	 * Include Theme JS
	 */
	private function _include_theme_js($file)
	{
		$this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->_theme_url().$file.'"></script>');
	}

	// --------------------------------------------------------------------

	/**
	 * Display Field Settings
	 */
	function display_settings($settings)
	{
		// merge in default field settings
		$settings = array_merge(array(
			'on_val' => 'y',
			'on_label' => 'YES',
			'off_val' => 'n',
			'off_label' => 'NO'
		), $settings);

		// load the language file
		$this->EE->lang->loadfile('pt_switch');

		// ON Value
		$this->EE->table->add_row(
			lang('pt_switch_on_val', 'pt_switch_on_val'),
			form_input('pt_switch[on_val]', $settings['on_val'], 'id="pt_switch_on_val"')
		);

		// ON Label
		$this->EE->table->add_row(
			lang('pt_switch_on_label', 'pt_switch_on_label'),
			form_input('pt_switch[on_label]', $settings['on_label'], 'id="pt_switch_on_label"')
		);

		// OFF Value
		$this->EE->table->add_row(
			lang('pt_switch_off_val', 'pt_switch_off_val'),
			form_input('pt_switch[off_val]', $settings['off_val'], 'id="pt_switch_off_val"')
		);

		// OFF Label
		$this->EE->table->add_row(
			lang('pt_switch_off_label', 'pt_switch_off_label'),
			form_input('pt_switch[off_label]', $settings['off_label'], 'id="pt_switch_off_label"')
		);

	}

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

	// --------------------------------------------------------------------

	/**
	 * Display Field
	 */
	function display_field($data)
	{
		$this->_include_theme_css('styles/pt_switch.css');
		$this->_include_theme_js('scripts/pt_switch.js');

		return form_dropdown(
			$this->settings['field_name'],
			array(
				$this->settings['off_val'] => $this->settings['off_label'],
				$this->settings['on_val'] => $this->settings['on_label']
			),
			$data,
			'class="pt-switch"'
		);
	}
}
