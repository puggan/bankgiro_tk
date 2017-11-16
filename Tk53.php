<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-16
	 * Time: 13:17
	 */

	namespace Spiro\Puggan\Bankgiro;

	/**
	 * Class Tk53
	 * @package Spiro\Puggan\Bankgiro
	 *
	 * @property int $Transaktionskod
	 * @property string $Information
	 */
	class Tk53 extends Tk
	{
		public function __construct($data = NULL)
		{
			parent::__construct(53, $data);
		}
	}