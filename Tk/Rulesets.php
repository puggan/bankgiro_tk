<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-17
	 * Time: 16:24
	 */

	namespace Spiro\Puggan\Bankgiro\Tk;

	class Rulesets
	{
		static function rules($tk_number)
		{
			$method = 'tk' . $tk_number;
			if(method_exists(self::class, $method))
			{
				return self::$method();
			}
			throw new \Exception("Unknown TK " . $tk_number);
		}

		/**
		 * Öppningspost
		 */
		static function tk51()
		{
			$tk = 51;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Skrivdag'] = new Rule('Date', 8);
			$rules['Clearingnummer'] = new Rule('N', 4, 9900);
			$rules['Bankgironummer'] = new Rule('N', 10);
			$rules['Innehåll'] = new Rule('A', 20, 'AG-EMEDGIV', '/^AG-EMEDGIV$/');
			$rules['Reservfält_45_80'] = new Rule('A', 36, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Medgivande post 1
		 */
		static function tk52()
		{
			$tk = 52;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Bankgironummer'] = new Rule('N', 10);
			$rules['Betalarnummer'] = new Rule('N', 16);
			$rules['Bankkontonummer'] = new Rule('N', 16);
			$rules['Personnummer'] = new Rule('P/Org-nr', 12);
			$rules['Reservfält_57_61'] = new Rule('A', 5, '', '/^ *$/');
			$rules['Meddelandetyp'] = new Rule('N', 1, 0, '/^[0-2]$/');
			$rules['Reservfält_63_80'] = new Rule('A', 18, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Medgivande post, särskild information
		 */
		static function tk53()
		{
			$tk = 53;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Information'] = new Rule('A', 36, '');
			$rules['Reservfält_39_80'] = new Rule('A', 42, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Medgivandepost, namn och adressdel 1
		 */
		static function tk54()
		{
			$tk = 54;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Rad1'] = new Rule('A', 36, '');
			$rules['Rad2'] = new Rule('A', 36, '');
			$rules['Reservfält_75_80'] = new Rule('A', 6, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Medgivandepost, namn och adressdel 2
		 */
		static function tk55()
		{
			$tk = 55;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Rad3'] = new Rule('A', 36, '');
			$rules['Rad4'] = new Rule('A', 36, '');
			$rules['Reservfält_75_80'] = new Rule('A', 6, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Medgivandepost, namn och adressdel 3
		 */
		static function tk56()
		{
			$tk = 56;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Postnummer'] = new Rule('N', 5);
			$rules['Postadress'] = new Rule('A', 31);
			$rules['Reservfält_39_80'] = new Rule('A', 42, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Slutpost
		 */
		static function tk59()
		{
			$tk = 59;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Skrivdag'] = new Rule('Date', 8);
			$rules['Clearingnummer'] = new Rule('N', 4, 9900);
			$rules['Antal'] = new Rule('N', 7);
			$rules['Reservfält_22_80'] = new Rule('A', 59, '', '/^ *$/');

			return $rules;
		}
	}