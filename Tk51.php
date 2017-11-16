<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-16
	 * Time: 13:17
	 */

	namespace Spiro\Puggan\Bankgiro;

	/**
	 * Class Tk51
	 * @package Spiro\Puggan\Bankgiro
	 *
	 * @property int $Transaktionskod
	 * @property int $Skrivdag
	 * @property int $Clearingnummer
	 * @property int $Bankgironummer
	 * @property string $Innehåll
	 */
	class Tk51 extends Tk
	{
		public function __construct($data = NULL)
		{
			parent::__construct(51, $data);
		}
	}