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
		private $bankgironummer;
		private $customers = [];

		public function __construct($bankgironummer)
		{
			$this->bankgironummer = $bankgironummer;
		}

		public function add_customer($customer)
		{
			if(!isset($customer['Betalarnummer']) AND isset($customer['Kundnummer']))
			{
				$customer['Betalarnummer'] = $customer['Kundnummer'];
			}
			foreach(['Betalarnummer', 'Bankkontonummer', 'Personnummer'] as $required_key)
			if(!isset($customer[$required_key]))
			{
				throw new \Exception('Missing ' . $required_key);
			}
			$this->customers[] = $customer;
		}

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

		public function __toString()
		{
			// TODO: Implement __toString() method.
			$tks = [];
			$tks[] = new Bankgiro\Tk51(['Bankgironummer' => $this->bankgironummer]);
			foreach($this->customers as $customer)
			{
				$tks = array_merge($tks, $this->customer_to_tks($customer));
			}
			$tks[] = new Bankgiro\Tk59(['Antal' => count($tks) - 1]);
			return implode(PHP_EOL, $tks);
		}
	}