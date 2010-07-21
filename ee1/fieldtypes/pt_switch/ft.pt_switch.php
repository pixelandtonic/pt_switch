<?php if (! defined('EXT')) exit('Invalid file request');


/**
 * P&T Switch Fieldtype Class for EE1
 *
 * @package   P&T Switch
 * @author    Brandon Kelly <brandon@pixelandtonic.com>
 * @copyright Copyright (c) 2010 Pixel & Tonic, LLC
 */
class Pt_switch extends Fieldframe_Fieldtype {

	var $info = array(
		'name'             => 'P&amp;T Switch',
		'version'          => '1.0',
		'versions_xml_url' => 'http://pixelandtonic.com/ee/versions.xml'
	);

	// --------------------------------------------------------------------

	/**
	 * Theme URL
	 */
	private function _theme_url()
	{
		if (! isset($this->_theme_url))
		{
			global $PREFS;
			$theme_folder_url = $PREFS->ini('theme_folder_url', 1);
			$this->_theme_url = $theme_folder_url.'third_party/pt_switch/';
		}

		return $this->_theme_url;
	}

	/**
	 * Include Theme CSS
	 */
	private function _include_theme_css($file)
	{
		$this->insert('head', '<link rel="stylesheet" type="text/css" href="'.$this->_theme_url().$file.'" />');
	}

	/**
	 * Include Theme JS
	 */
	private function _include_theme_js($file)
	{
		$this->insert('body', '<script type="text/javascript" src="'.$this->_theme_url().$file.'"></script>');
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
	function display_field_settings($data)
	{
		global $DSP;

		$rows = $this->_field_settings($data);

		foreach ($rows as &$row)
		{
			$row[0] = $DSP->qdiv('defaultBold', $row[0]);  //'<label>'.$row[0].'</label>';
		}

		return array('rows' => $rows);
	}

	/**
	 * Display Cell Settings
	 */
	function display_cell_settings($data)
	{
		return $this->_field_settings($data, 'class="matrix-textarea"');
	}

	/**
	 * Field Settings
	 */
	private function _field_settings($data, $attr = '')
	{
		global $LANG;

		// merge in default field settings
		$data = array_merge(
			array(
				'on_label'  => 'YES',
				'on_val'    => 'y',
				'off_label' => 'NO',
				'off_val'   => ''
			),
			$data
		);

		return array(
			// ON Label
			array(
				$LANG->line('pt_switch_on_label'),
				'<input type="text" name="on_label" value="'.$data['on_label'].'" '.$attr.' />'
			),

			// ON Value
			array(
				$LANG->line('pt_switch_on_val'),
				'<input type="text" name="on_val" value="'.$data['on_val'].'" '.$attr.' />'
			),

			// OFF Label
			array(
				$LANG->line('pt_switch_off_label'),
				'<input type="text" name="off_label" value="'.$data['off_label'].'" '.$attr.' />'
			),

			// OFF Value
			array(
				$LANG->line('pt_switch_off_val'),
				'<input type="text" name="off_val" value="'.$data['off_val'].'" '.$attr.' />'
			)
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Display Field
	 */
	function display_field($field_name, $data, $settings, $cell = FALSE)
	{
		$this->_include_theme_css('Styles/pt_switch.css');
		$this->_include_theme_js('Scripts/pt_switch.js');

		$field_id = str_replace(array('[', ']'), array('_', ''), $field_name);

		if (! $cell)
		{
			$this->insert_js('new ptSwitch(jQuery("#'.$field_id.'"));');
		}

		$options = array(
			$settings['off_val'] => $settings['off_label'],
			$settings['on_val']  => $settings['on_label']
		);

		$SD = new Fieldframe_SettingsDisplay();

		return '<select id="'.$field_id.'" name="'.$field_name.'">'
			. $SD->_select_options($data, $options)
			. '</select>';
	}

	/**
	 * Display Cell
	 */
	function display_cell($cell_name, $data, $settings)
	{
		$this->_include_theme_js('Scripts/matrix2.js');

		return $this->display_field($cell_name, $data, $settings, TRUE);
	}
}
