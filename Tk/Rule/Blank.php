<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-17
	 * Time: 19:30
	 */

	namespace Spiro\Puggan\Bankgiro\Tk\Rule;

	use Spiro\Puggan\Bankgiro\Tk\Rule;

	class Blank extends Rule
	{
		public function __construct($length)
		{
			parent::__construct('A', $length, '', '/^ *$/');
		}

		public function clean($org_input)
		{
			return str_repeat(' ', $this->length);
		}
	}