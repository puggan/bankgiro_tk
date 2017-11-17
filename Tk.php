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
			self::$tk_definitions[51] = Tk\Rulesets::tk51();
			self::$tk_definitions[52] = Tk\Rulesets::tk52();
			self::$tk_definitions[53] = Tk\Rulesets::tk53();
			self::$tk_definitions[54] = Tk\Rulesets::tk54();
			self::$tk_definitions[55] = Tk\Rulesets::tk55();
			self::$tk_definitions[56] = Tk\Rulesets::tk56();
			self::$tk_definitions[59] = Tk\Rulesets::tk59();

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
