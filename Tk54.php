<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-16
	 * Time: 13:17
	 */

	namespace Spiro\Puggan\Bankgiro;

	/**
	 * Class Tk54
	 * @package Spiro\Puggan\Bankgiro
	 *
	 * @property int $Transaktionskod
	 * @property string $Rad1
	 * @property string $Rad2
	 */
	class Tk54 extends Tk
	{
		public function __construct($data = NULL)
		{
			parent::__construct(54, $data);
		}
	}