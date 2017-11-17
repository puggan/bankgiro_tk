<?php

	namespace Spiro\Puggan\Bankgiro;

	class Tk implements \IteratorAggregate, \ArrayAccess
	{
		private $tk_nr, $rules, $data = [];

		public function __construct($tk_number, $data = null)
		{
			if(!$data AND is_string($tk_number) AND strlen($tk_number) > 2) {
				$data = $tk_number;
				$tk_number = substr($data, 0, 2);
			}

			$this->tk_nr = $tk_number;
			$this->rules = Tk\Rulesets::rules($tk_number);
			$this->data = array_fill_keys(array_keys($this->rules), NULL);
			foreach($this->rules as $key => $rule)
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
			$rules = $this->rules;
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
			$rules = $this->rules;
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
			if(isset($this->rules[$key]))
			{
				return $this->data[$key];
			}
			throw new \Exception('The key ' . $key . ' is not part of TK ' . $this->tk_nr);
		}

		public function __set($key, $value)
		{
			if(isset($this->rules[$key]))
			{
				$this->data[$key] = $this->rules[$key]->clean($value);
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
			return isset($this->rules[$offset]);
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
