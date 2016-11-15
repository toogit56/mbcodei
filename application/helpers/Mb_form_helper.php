<?php

if ( ! function_exists('form_transaction_token'))
{
	/**
	 * Submit Button アクションの一部を操作可能
	 *
	 * @param	string
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	function form_transaction_token($form_name = '')
	{
		$hidden_name = "tstoken";
		if($form_name != null) {
			$hidden_name = $hidden_name . "_$form_name";
		}

		mt_srand((double)microtime() * 1000000);
		$token = md5((string)mt_rand() . "zxTHstFSytDYHA");

		$CI =& get_instance();
		if(!isset($CI->session)) {
			mbexception("Invalid session", "Invalid session for transaction token");
		}

		$CI->session->set_userdata($hidden_name, $token);

		return '<input type="hidden" name="'.$hidden_name.'" value="'.html_escape($token)."\" />\n";
	}
}


if ( ! function_exists('form_submit_action_replace'))
{
	/**
	 * Submit Button アクションの一部を操作可能
	 *
	 * @param	string
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	function form_submit_action_replace($replace_from, $replace_to, $data = '', $value = '', $extra = '')
	{
	
		$defaults = array(
			'type' => 'submit',
			'name' => is_array($data) ? '' : $data,
			'value' => $value,
			'onclick' => 'this.form.action = this.form.action.replace(\'' . $replace_from . '\', \'' . $replace_to . '\');return true;'
		);

		return '<input '._mb_parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";
	}
}


if ( ! function_exists('_mb_parse_form_attributes'))
{
	/**
	 * Parse the form attributes
	 *
	 * Helper function used by some of the form helpers
	 *
	 * @param	array	$attributes	List of attributes
	 * @param	array	$default	Default values
	 * @return	string
	 */
	function _mb_parse_form_attributes($attributes, $default)
	{
		if (is_array($attributes))
		{
			foreach ($default as $key => $val)
			{
				if (isset($attributes[$key]))
				{
					$default[$key] = $attributes[$key];
					unset($attributes[$key]);
				}
			}

			if (count($attributes) > 0)
			{
				$default = array_merge($default, $attributes);
			}
		}

		$att = '';

		foreach ($default as $key => $val)
		{
			if ($key === 'value')
			{
				$val = html_escape($val);
			}
			elseif ($key === 'name' && ! strlen($default['name']))
			{
				continue;
			}

			$att .= $key.'="'.$val.'" ';
		}

		return $att;
	}
}