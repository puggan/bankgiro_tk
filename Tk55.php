<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-16
	 * Time: 13:17
	 */

	namespace Spiro\Puggan\Bankgiro;

	/**
	 * Class Tk55
	 * @package Spiro\Puggan\Bankgiro
	 *
	 * @property int $Transaktionskod
	 * @property string $Rad3
	 * @property string $Rad4
	 */
	class Tk55 extends Tk
	{
		public function __construct($data = NULL)
		{
			parent::__construct(55, $data);
		}
	}