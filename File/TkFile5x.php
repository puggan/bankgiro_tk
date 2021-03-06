<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-16
	 * Time: 14:03
	 */

	namespace Spiro\Puggan\Bankgiro\File;
	use Spiro\Puggan\Bankgiro;

	class TkFile5x
	{
		/** @var int|string */
		private $bankgironummer;
		/** @var string[][]|int[][] */
		private $customers = [];

		/** @var string CRLF or other separator */
		public $row_break;

		/**
		 * TkFile5x constructor.
		 *
		 * @param int|string $bankgironummer
		 * @param string $row_break
		 */
		public function __construct($bankgironummer, $row_break = "\r\n")
		{
			$this->bankgironummer = $bankgironummer;
			$this->row_break = $row_break;
		}

		/**
		 * @param string[]|int[] $customer
		 *
		 * @throws \Exception
		 */
		public function add_customer($customer)
		{
			if(!isset($customer['Betalarnummer']) AND isset($customer['Kundnummer']))
			{
				$customer['Betalarnummer'] = $customer['Kundnummer'];
			}
			foreach(['Betalarnummer', 'Bankkontonummer', 'Personnummer'] as $required_key)
			{
				if(!isset($customer[$required_key]))
				{
					throw new \Exception('Missing ' . $required_key);
				}
			}
			$this->customers[] = $customer;
		}

		/**
		 * @param string[]|int[] $customer
		 *
		 * @return string[]
		 */
		public function customer_to_tks($customer)
		{
			$tks = [];

			$tks[] = new Bankgiro\Tk52(['Bankgironummer' => $this->bankgironummer] + $customer);
			if(isset($customer['Information']))
			{
				$tks[] = new Bankgiro\Tk53($customer);
			}
			if(isset($customer['Rad1']))
			{
				$tks[] = new Bankgiro\Tk54($customer);
			}
			if(isset($customer['Rad3']))
			{
				$tks[] = new Bankgiro\Tk55($customer);
			}
			if(isset($customer['Postnummer']))
			{
				$tks[] = new Bankgiro\Tk56($customer);
			}

			return $tks;
		}

		/**
		 * @return string
		 */
		public function __toString()
		{
			$tks = [];
			$tks[] = new Bankgiro\Tk51(['Bankgironummer' => $this->bankgironummer]);
			foreach($this->customers as $customer)
			{
				$tks = array_merge($tks, $this->customer_to_tks($customer));
			}
			$tks[] = new Bankgiro\Tk59(['Antal' => count($tks) - 1]);
			return implode($this->row_break, $tks);
		}
	}