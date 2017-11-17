<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-16
	 * Time: 11:37
	 */

	namespace Spiro\Puggan\Bankgiro\Tk;

	/**
	 * Class Rule
	 * Deinfiton, validation, and cleaning up TK fileds
	 *
	 * @package Spiro\Puggan\Bankgiro\Tk
	 *
	 * @property string enum N: numeric, A: alfanumeric, Date, P/Org-nr
	 * @property int length of filed
	 * @property string default value to use if not spicifed
	 * @property string validation
	 */
	class Rule
	{
		public $type, $length, $default, $regexp;

		public function __construct($type, $length, $default = NULL, $regexp = NULL)
		{
			$this->type = $type;
			$this->length = $length;
			$this->default = $default;
			$this->regexp = $regexp;

			if($this->type == 'Date' AND !$this->default) $this->default = 'today';
		}

		public function clean($org_input)
		{
			// default for unset values
			if($org_input === NULL) {
				$input = $this->default;
			}
			else
			{
				$input = $org_input;
			}

			// Pre validate cleanup
			switch($this->type)
			{
				case 'Date':
				{
					$input = date("Ymd", strtotime($input));
					break;
				}
				case 'P/Org-nr':
				case 'N':
				{
					$input = preg_replace("/[^0-9]+/", '', $input);
					break;
				}
				default:
				{
					$input = trim($input);
				}
			}

			// Validate
			if($this->regexp)
			{
				if(!preg_match($this->regexp, $input)) {
					$input = $this->default;
				}
			}

			// Type validation
			switch($this->type)
			{
				case 'Date':
				{
					if($input === '19700101') throw new \Exception("Unparsable date: {$org_input}");
					break;
				}
				case 'P/Org-nr':
				{
					if(strlen($input) > 10 AND trim(substr($input, 0, -10), '0') == '')
					{
						$input = substr($input, -10);
					}

					if(strlen($input) == 10) {
						// TODO validate org-nr
					}
					else {
						// TODO validate person-nr
					}
				}
			}

			// Pre validate cleanup
			switch($this->type)
			{
				case 'Date':
				case 'P/Org-nr':
				case 'N':
				{
					return mb_substr(str_repeat('0', $this->length) . $input, -$this->length);
				}

				case 'AN':
				{
					if(is_numeric(trim($input)))
					{
						return mb_substr(str_repeat('0', $this->length) . $input, -$this->length);
					}
					else
					{
						return mb_substr($input . str_repeat(' ', $this->length), 0, $this->length);
					}

				}

				case 'A':
				default:
				{
					return mb_substr($input . str_repeat(' ', $this->length), 0, $this->length);
				}
			}
		}
	}