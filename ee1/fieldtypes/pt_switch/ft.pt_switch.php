<?php if (! defined('EXT')) exit('Invalid file request');


/**
 * P&T Switch Fieldtype Class for EE1
 *
 * @package   P&T Switch
 * @author    Brandon Kelly <brandon@pixelandtonic.com>
 * @copyright Copyright (c) 2011 Pixel & Tonic, Inc
 */
class Pt_switch extends Fieldframe_Fieldtype {

	var $info = array(
		'name'             => 'P&T Switch',
		'version'          => '1.0.4',
		'versions_xml_url' => 'http://pixelandtonic.com/ee/versions.xml'
	);

	/**
	 * P&T Switch Constructor
	 */
	function Pt_switch()
	{
		$this->default_field_settings = $this->default_cell_settings = array(
			'off_label' => 'NO',
			'off_val'   => '',
			'on_label'  => 'YES',
			'on_val'    => 'y',
			'default'   => 'off'
		);
	}

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
		global $LANG;

		$SD = new Fieldframe_SettingsDisplay();

		return array(
			// OFF Label
			array(
				$LANG->line('pt_switch_off_label'),
				'<input type="text" name="off_label" value="'.$data['off_label'].'" '.$attr.' />'
			),

			// OFF Value
			array(
				$LANG->line('pt_switch_off_val'),
				'<input type="text" name="off_val" value="'.$data['off_val'].'" '.$attr.' />'
			),

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

			// Default
			array(
				$LANG->line('pt_switch_default'),
				$SD->select('default', $data['default'], array('off' => 'OFF', 'on' => 'ON'))
			)
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Save LV Settings
	 */
	function save_var_settings($settings)
	{
		global $IN;

		foreach ($settings AS $key => &$val)
		{
			$val = ($IN->GBL($key, 'POST') !== FALSE) ? $IN->GBL($key, 'POST') : $val;
		}

		return $settings;
	}

	// --------------------------------------------------------------------

	/**
	 * Display Field
	 */
	function display_field($field_name, $data, $settings, $cell = FALSE)
	{
		global $FF, $IN;

		$this->_include_theme_css('styles/pt_switch.css');
		$this->_include_theme_js('scripts/pt_switch.js');

		$field_id = str_replace(array('[', ']'), array('_', ''), $field_name);

		if ($cell)
		{
			$new = (! (isset($FF->row['entry_id']) ? $FF->row['entry_id'] : $IN->GBL('entry_id'))) || ($field_name == '{DEFAULT}');
		}
		else
		{
			$new = (! (isset($FF->row['entry_id']) ? $FF->row['entry_id'] : $IN->GBL('entry_id')));
			$this->insert_js('new ptSwitch(jQuery("#'.$field_id.'"));');
		}

		// Pretend it's a new entry if $data isn't set to one of the values
		if ($data != $settings['off_val'] && $data != $settings['on_val'])
		{
			$new = TRUE;
		}

		$options = array(
			$settings['off_val'] => $settings['off_label'],
			$settings['on_val']  => $settings['on_label']
		);

		if ($new)
		{
			$data = $settings[$settings['default'].'_val'];
		}

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
		$this->_include_theme_js('scripts/matrix2.js');

		return $this->display_field($cell_name, $data, $settings, TRUE);
	}

	/**
	 * Display Var
	 */
	function display_var_field($cell_name, $data, $settings)
	{
		return $this->display_field($cell_name, $data, $settings);
	}

	// --------------------------------------------------------------------

	/**
	 * Label
	 */
	function label($params, $tagdata, $data, $settings)
	{
		if ($data == $settings['on_val'])
		{
			return $settings['on_label'];
		}
		else
		{
			return $settings['off_label'];
		}
	}
}
