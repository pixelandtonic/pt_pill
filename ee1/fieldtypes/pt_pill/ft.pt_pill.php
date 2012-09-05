<?php if (! defined('EXT')) exit('Invalid file request');


/**
 * P&T Pill Fieldtype Class for EE1
 *
 * @package   P&T Pill
 * @author    Brandon Kelly <brandon@pixelandtonic.com>
 * @copyright Copyright (c) 2011 Pixel & Tonic, Inc
 */
class Pt_pill extends Fieldframe_Fieldtype {

	var $info = array(
		'name'             => 'P&T Pill',
		'version'          => '1.0.3',
		'versions_xml_url' => 'http://pixelandtonic.com/ee/versions.xml'
	);

	/**
	 * P&T Pill Constructor
	 */
	function Pt_pill()
	{
		$this->default_field_settings = $this->default_cell_settings = array(
			'options' => array()
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
			$this->_theme_url = $theme_folder_url.'third_party/pt_pill/';
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
		global $DSP, $LANG;

		return array('rows' => array(array(
			$DSP->qdiv('defaultBold', $LANG->line('pt_pill_options'))
				. $DSP->qdiv('itemWrapper',
					$LANG->line('field_list_instructions') . '<br /><br />'
					. $LANG->line('option_setting_examples')
				),
			'<textarea id="pt_pill_options" name="options" cols="90" rows="6" style="width: 99%">'.$this->_options_setting($data).'</textarea>'
		)));

		return array('rows' => $rows);
	}

	/**
	 * Display Cell Settings
	 */
	function display_cell_settings($data)
	{
		global $LANG;

		return array(array(
			$LANG->line('pt_pill_options'),
			'<textarea class="matrix-textarea" name="options" rows="4">'.$this->_options_setting($data).'</textarea>'
		));
	}

	/**
	 * Display Var Settings
	 */
	function display_var_settings($data)
	{
		global $LANG;

		return array(array(
			$LANG->line('pt_pill_options') . '<br /><br />'
			. $LANG->line('option_setting_examples'),

			'<textarea id="pt_pill_options" name="pt_pill_options" cols="90" rows="6" style="width: 99%">'.$this->_options_setting($data).'</textarea>'
		));
	}

	/**
	 * Options Setting Value
	 */
	private function _options_setting($settings)
	{
		$r = '';

		foreach($settings['options'] as $name => $label)
		{
			if ($r !== '') $r .= "\n";
			$r .= $name;
			if ($name !== $label) $r .= ' : '.$label;
			if (isset($settings['default']) && $settings['default'] == $name) $r .= ' *';
		}

		return $r;
	}

	// --------------------------------------------------------------------

	/**
	 * Save Field Settings
	 */
	function save_field_settings($data)
	{
		return $this->_save_settings($data['options']);
	}

	/**
	 * Save Cell Settings
	 */
	function save_cell_settings($data)
	{
		return $this->_save_settings($data['options']);
	}

	/**
	 * Save Var Settings
	 */
	function save_var_settings($data)
	{
		global $IN;

		$options = $IN->GBL('pt_pill_options', 'POST');

		return $this->_save_settings($options);
	}


	/**
	 * Save Options Setting
	 */
	private function _save_settings($options = '')
	{
		$r = array('options' => array());

		$options = preg_split('/[\r\n]+/', $options);
		foreach($options as &$option)
		{
			// default?
			if ($default = (substr($option, -1) == '*')) $option = substr($option, 0, -1);

			$option_parts = preg_split('/\s:\s/', $option, 2);
			$option_name  = (string) trim($option_parts[0]);
			$option_label = isset($option_parts[1]) ? (string) trim($option_parts[1]) : $option_name;

			$r['options'][$option_name] = $option_label;
			if ($default) $r['default'] = $option_name;
		}

		return $r;
	}

	// --------------------------------------------------------------------

	/**
	 * Display Field
	 */
	function display_field($field_name, $data, $settings, $cell = FALSE)
	{
		$this->_include_theme_css('styles/pt_pill.css');
		$this->_include_theme_js('scripts/pt_pill.js');

		$field_id = str_replace(array('[', ']'), array('_', ''), $field_name);

		// -------------------------------------------
		//  Insert the JS
		// -------------------------------------------

		if (! $cell)
		{
			$this->insert_js('new ptPill(jQuery("#'.$field_id.'"));');
		}

		// default?
		if (! $data && isset($settings['default'])) $data = $settings['default'];

		$SD = new Fieldframe_SettingsDisplay();

		return '<select id="'.$field_id.'" name="'.$field_name.'">'
			. $SD->_select_options($data, $settings['options'])
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
	 * Display variable field
	 */
	function display_var_field($cell_name, $data, $settings)
	{
		return $this->display_field($cell_name, $data, $settings);
	}

	// --------------------------------------------------------------------

	/**
	 * Label tag
	 */
	function label($params, $tagdata, $data, $settings)
	{
		if (isset($settings['options'][$data]))
		{
			return $settings['options'][$data];
		}
	}
}
