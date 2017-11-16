<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-16
	 * Time: 13:17
	 */

	namespace Spiro\Puggan\Bankgiro;

	/**
	 * Class Tk56
	 * @package Spiro\Puggan\Bankgiro
	 *
	 * @property int $Transaktionskod
	 * @property int $Postnummer
	 * @property string $Postadress
	 */
	class Tk56 extends Tk
	{
		public function __construct($data = NULL)
		{
			parent::__construct(56, $data);
		}
	}