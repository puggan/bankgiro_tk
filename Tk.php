<?php

	namespace Spiro\Puggan\Bankgiro;

	class Tk implements \IteratorAggregate, \ArrayAccess
	{
		private $tk_nr;
		private $data = [];

		//<editor-fold desc="Rule Definitions">
		/** @var  Tk\Rule[][] */
		static $tk_definitions;

		static public function load_tk_definitoins()
		{
			if(self::$tk_definitions)
			{
				return self::$tk_definitions;
			}

			self::$tk_definitions = [];

			//<editor-fold desc="Öppningspost (TK51)">
			$tk = 51;
			// Required: Bankgironummer
			self::$tk_definitions[$tk]['Transaktionskod'] = new Tk\Rule('N', 2, $tk, '/^' . $tk . '$/');
			self::$tk_definitions[$tk]['Skrivdag'] = new Tk\Rule('Date', 8);
			self::$tk_definitions[$tk]['Clearingnummer'] = new Tk\Rule('N', 4, 9900);
			self::$tk_definitions[$tk]['Bankgironummer'] = new Tk\Rule('N', 10);
			self::$tk_definitions[$tk]['Innehåll'] = new Tk\Rule('A', 20, 'AG-EMEDGIV','/^AG-EMEDGIV$/');
			self::$tk_definitions[$tk]['Reservfält_45_80'] = new Tk\Rule('A', 36, '', '/^ *$/');
			//</editor-fold>

			//<editor-fold desc="Medgivande post 1 (TK52)">
			$tk = 52;
			self::$tk_definitions[$tk]['Transaktionskod'] = new Tk\Rule('N', 2, $tk, '/^' . $tk . '$/');
			self::$tk_definitions[$tk]['Bankgironummer'] = new Tk\Rule('N', 10);
			self::$tk_definitions[$tk]['Betalarnummer'] = new Tk\Rule('N', 16);
			self::$tk_definitions[$tk]['Bankkontonummer'] = new Tk\Rule('N', 16);
			self::$tk_definitions[$tk]['Personnummer'] = new Tk\Rule('P/Org-nr', 12);
			self::$tk_definitions[$tk]['Reservfält_57_61'] = new Tk\Rule('A', 5, '', '/^ *$/');
			self::$tk_definitions[$tk]['Meddelandetyp'] = new Tk\Rule('N', 1, 0, '/^[0-2]$/');
			self::$tk_definitions[$tk]['Reservfält_63_80'] = new Tk\Rule('A', 18, '', '/^ *$/');
			//</editor-fold>

			//<editor-fold desc="Medgivande post, särskild information (TK53)">
			$tk = 53;
			self::$tk_definitions[$tk]['Transaktionskod'] = new Tk\Rule('N', 2, $tk, '/^' . $tk . '$/');
			self::$tk_definitions[$tk]['Information'] = new Tk\Rule('A', 36, '');
			self::$tk_definitions[$tk]['Reservfält_39_80'] = new Tk\Rule('A', 42, '', '/^ *$/');
			//</editor-fold>

			//<editor-fold desc="Medgivandepost, namn och adressdel 1 (TK54)">
			$tk = 54;
			self::$tk_definitions[$tk]['Transaktionskod'] = new Tk\Rule('N', 2, $tk, '/^' . $tk . '$/');
			self::$tk_definitions[$tk]['Rad1'] = new Tk\Rule('A', 36, '');
			self::$tk_definitions[$tk]['Rad2'] = new Tk\Rule('A', 36, '');
			self::$tk_definitions[$tk]['Reservfält_75_80'] = new Tk\Rule('A', 6, '', '/^ *$/');
			//</editor-fold>

			//<editor-fold desc="Medgivandepost, namn och adressdel 2 (TK55)">
			$tk = 55;
			self::$tk_definitions[$tk]['Transaktionskod'] = new Tk\Rule('N', 2, $tk, '/^' . $tk . '$/');
			self::$tk_definitions[$tk]['Rad3'] = new Tk\Rule('A', 36, '');
			self::$tk_definitions[$tk]['Rad4'] = new Tk\Rule('A', 36, '');
			self::$tk_definitions[$tk]['Reservfält_75_80'] = new Tk\Rule('A', 6, '', '/^ *$/');
			//</editor-fold>

			//<editor-fold desc="Medgivandepost, namn och adressdel 3 (TK56)">
			$tk = 56;
			self::$tk_definitions[$tk]['Transaktionskod'] = new Tk\Rule('N', 2, $tk, '/^' . $tk . '$/');
			self::$tk_definitions[$tk]['Postnummer'] = new Tk\Rule('N', 5);
			self::$tk_definitions[$tk]['Postadress'] = new Tk\Rule('A', 31);
			self::$tk_definitions[$tk]['Reservfält_39_80'] = new Tk\Rule('A', 42, '', '/^ *$/');
			//</editor-fold>

			//<editor-fold desc="Slutpost (TK59)">
			$tk = 59;
			self::$tk_definitions[$tk]['Transaktionskod'] = new Tk\Rule('N', 2, $tk, '/^' . $tk . '$/');
			self::$tk_definitions[$tk]['Skrivdag'] = new Tk\Rule('Date', 8);
			self::$tk_definitions[$tk]['Clearingnummer'] = new Tk\Rule('N', 4, 9900);
			self::$tk_definitions[$tk]['Antal'] = new Tk\Rule('N', 7);
			self::$tk_definitions[$tk]['Reservfält_22_80'] = new Tk\Rule('A', 59, '', '/^ *$/');
			//</editor-fold>

			return self::$tk_definitions;
		}
		//</editor-fold>

		public function __construct($tk_number, $data = null)
		{
			self::load_tk_definitoins();

			if(!$data AND is_string($tk_number) AND strlen($tk_number) > 2) {
				$data = $tk_number;
				$tk_number = substr($data, 0, 2);
			}

			if(empty(self::$tk_definitions[$tk_number]))
			{
				throw new \Exception("Bad tk-number");
			}

			$this->tk_nr = $tk_number;
			$this->data = array_fill_keys(array_keys(self::$tk_definitions[$tk_number]), NULL);
			foreach(self::$tk_definitions[$tk_number] as $key => $rule)
			{
				$this->__set($key, $rule->default);
			}

			if(is_string($data))
			{
				$this->load_from_string($data);
			}
			else if(is_array($data))
			{
				$this->load($data);
			}
		}

		public function load($list) {
			$rules = self::$tk_definitions[$this->tk_nr];
			$nr_to_key = [];
			foreach(array_keys($rules) as $key)
			{
				if($key == 'Transaktionskod') continue;
				$skip_prefix = 'Reservfält_';
				if(substr($key, 0, strlen($skip_prefix)) == $skip_prefix) continue;

				$nr_to_key[] = $key;
			}

			foreach($list as $key => $value) {
				if(isset($nr_to_key[$key])) $key = $nr_to_key[$key];
				$this->__set($key, $value);
			}
		}

		public function load_from_string($org_string, $encoding = TRUE)
		{
			$rules = self::$tk_definitions[$this->tk_nr];
			if($encoding === TRUE) {
				$encoding = mb_detect_encoding($org_string, ['ASCII', 'UTF-8', 'ISO-8859-1']);
			}
			switch((string) $encoding)
			{
				case 'ISO-8859-1':
				{
					$string = utf8_encode($org_string);
					break;
				}

				case '':
				case 'ASCII':
				case 'UTF-8':
				{
					$string = $org_string;
					break;
				}

				default:
				{
					$string = mb_convert_encoding($org_string, 'UTF-8', $encoding);
					break;
				}
			}

			foreach($rules as $key => $rule)
			{
				$this->__set($key, mb_substr($string, 0, $rule->length));
				$string = mb_substr($string, $rule->length);
			}
		}

		//<editor-fold desc="Getter, Setter, and Strings">
		public function __get($key)
		{
			if(isset(self::$tk_definitions[$this->tk_nr][$key]))
			{
				return $this->data[$key];
			}
			throw new \Exception('The key ' . $key . ' is not part of TK ' . $this->tk_nr);
		}

		public function __set($key, $value)
		{
			if(isset(self::$tk_definitions[$this->tk_nr][$key]))
			{
				$this->data[$key] = self::$tk_definitions[$this->tk_nr][$key]->clean($value);
			}
		}

		public function __toString()
		{
			return implode($this->data);
		}
		//</editor-fold>

		//<editor-fold desc="ArrayAccess wrapers">
		public function offsetExists($offset)
		{
			return isset(self::$tk_definitions[$this->tk_nr][$offset]);
		}

		public function offsetGet($offset)
		{
			return $this->__get($offset);
		}

		public function offsetSet($offset, $value)
		{
			$this->__set($offset, $value);
		}

		public function offsetUnset($offset)
		{
			$this->__set($offset, NULL);
		}
		//</editor-fold>

		//<editor-fold desc="IteratorAggregate / Traversable wrapers">
		public function getIterator()
		{
			return (new \ArrayObject($this->data))->getIterator();
		}
		//</editor-fold>
	}
