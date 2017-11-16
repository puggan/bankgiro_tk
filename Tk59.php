<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-16
	 * Time: 13:17
	 */

	namespace Spiro\Puggan\Bankgiro;

	/**
	 * Class Tk59
	 * @package Spiro\Puggan\Bankgiro
	 *
	 * @property int $Transaktionskod
	 * @property int $Skrivdag
	 * @property int $Clearingnummer
	 * @property int $Antal
	 */
	class Tk59 extends Tk
	{
		public function __construct($data = NULL)
		{
			parent::__construct(59, $data);
		}
	}