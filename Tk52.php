<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-16
	 * Time: 13:17
	 */

	namespace Spiro\Puggan\Bankgiro;

	/**
	 * Class Tk52
	 * @package Spiro\Puggan\Bankgiro
	 *
	 * @property int $Transaktionskod
	 * @property int $Bankgironummer
	 * @property int $Betalarnummer
	 * @property int $Bankkontonummer
	 * @property int $Personnummer
	 * @property int $Meddelandetyp
	 */
	class Tk52 extends Tk
	{
		public function __construct($data = NULL)
		{
			parent::__construct(52, $data);
		}
	}