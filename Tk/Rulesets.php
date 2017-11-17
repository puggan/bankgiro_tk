<?php
	/**
	 * Created by PhpStorm.
	 * User: puggan
	 * Date: 2017-11-17
	 * Time: 16:24
	 */

	namespace Spiro\Puggan\Bankgiro\Tk;

	/**
	 * Class Rulesets
	 * @package Spiro\Puggan\Bankgiro\Tk
	 *
	 * @see https://www.bankgirot.se/globalassets/dokument/tekniska-manualer/autogiro_tekniskmanual_sv.pdf
	 */
	class Rulesets
	{
		static function rules($tk_number)
		{
			$method = sprintf("tk%02d", $tk_number);
			if(method_exists(self::class, $method))
			{
				return self::$method();
			}
			throw new \Exception("Unknown TK " . $tk_number);
		}

		//<editor-fold desc="tk0x">
		/**
		 * Öppningspost
		 */
		static function tk01()
		{
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, 1, '/^0?1$/');
			$rules['Skrivdag'] = new Rule('Date', 8);
			$rules['Layoutnamn'] = new Rule('A', 8, 'AUTOGIRO', '/^AUTOGIRO$/');
			$rules['Reservfält_19_62'] = new Rule('A', 44, '', '/^ *$/');
			$rules['Kundnummer'] = new Rule('N', 6);
			$rules['Bankgironummer'] = new Rule('N', 10);
			$rules['Reservfält_79_80'] = new Rule('A', 2, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Post för makulering av Medgivande
		 */
		static function tk03()
		{
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, 3, '/^0?3$/');
			$rules['Bankgironummer'] = new Rule('N', 10);
			$rules['Betalarnummer'] = new Rule('N', 16);
			$rules['Reservfält_29_80'] = new Rule('A', 52, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Post för nyinlägg och godkännande/avvisande av Medgivanden via Internetbanken
		 */
		static function tk04()
		{
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, 4, '/^0?4$/');
			$rules['Bankgironummer'] = new Rule('N', 10);
			$rules['Betalarnummer'] = new Rule('N', 16);
			$rules['Bankkontonummer'] = new Rule('N', 16);
			$rules['Personnummer'] = new Rule('P/Org-nr', 12);
			$rules['Reservfält_57_76'] = new Rule('A', 20, '', '/^ *$/');
			$rules['Nyinlägg'] = new Rule('A', 2, '', '/^( *|AV)$/');
			$rules['Reservfält_79_80'] = new Rule('A', 2, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Post för byte av betalarnummer
		 */
		static function tk05()
		{
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, 5, '/^0?5$/');
			$rules['Bankgironummer'] = new Rule('N', 10);
			$rules['Betalarnummer'] = new Rule('N', 16);
			$rules['Bankgironummer2'] = new Rule('N', 10);
			$rules['Betalarnummer2'] = new Rule('N', 16);
			$rules['Reservfält_55_80'] = new Rule('A', 26, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Slutpost
		 */
		static function tk09()
		{
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, 9, '/^0?9$/');
			$rules['Skrivdag'] = new Rule('Date', 8);
			$rules['Clearingnummer'] = new Rule('N', 4, 9900);
			$rules['AntalIn'] = new Rule('N', 6);
			$rules['Antal82'] = new Rule('N', 12);
			$rules['AntalUt'] = new Rule('N', 6);
			$rules['Antal32'] = new Rule('N', 12);
			$rules['AntalÅter'] = new Rule('N', 6);
			$rules['Antal77'] = new Rule('N', 12);
			$rules['Reservfält_69_80'] = new Rule('A', 12, '', '/^ *$/');

			return $rules;
		}
		//</editor-fold>

		//<editor-fold desc="tk1x">
		// TODO Tk11, page 54: Makuleringspost
		// TODO Tk15, page 35: Insättningspost
		// TODO Tk16, page 37: Uttagspost
		// TODO Tk17, page 40: Uttagspost för Återbetalning
		//</editor-fold>

		//<editor-fold desc="tk2x">
		/**
		 * Makuleringspost, alla betalningar oavsätt dag
		 */
		static function tk23()
		{
			$tk = 23;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Bankgironummer'] = new Rule('N', 10);
			$rules['Betalarnummer'] = new Rule('N', 16);
			$rules['Betalningsdag'] = new Rule('A', 8, '', '/^ *$/');
			$rules['Belopp'] = new Rule('A', 12, '', '/^ *$/');
			$rules['Betalningskod'] = new Rule('A', 2, '', '/^ *$/');
			$rules['Reservfält_51_58'] = new Rule('A', 8, '', '/^ *$/');
			$rules['Referense'] = new Rule('A', 16, '', '/^ *$/');
			$rules['Reservfält_75_80'] = new Rule('A', 6, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Makuleringspost, alla betalningar en dag
		 */
		static function tk24()
		{
			$tk = 24;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Bankgironummer'] = new Rule('N', 10);
			$rules['Betalarnummer'] = new Rule('N', 16);
			$rules['Betalningsdag'] = new Rule('Date', 8);
			$rules['Belopp'] = new Rule('A', 12, '', '/^ *$/');
			$rules['Betalningskod'] = new Rule('A', 2, '', '/^ *$/');
			$rules['Reservfält_51_58'] = new Rule('A', 8, '', '/^ *$/');
			$rules['Referense'] = new Rule('A', 16, '', '/^ *$/');
			$rules['Reservfält_75_80'] = new Rule('A', 6, '', '/^ *$/');

			return $rules;
		}

		/**
		 * Makuleringspost, en betalning
		 */
		static function tk25()
		{
			$tk = 25;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Bankgironummer'] = new Rule('N', 10);
			$rules['Betalarnummer'] = new Rule('N', 16);
			$rules['Betalningsdag'] = new Rule('Date', 8);
			$rules['Belopp'] = new Rule('N', 12, 0);
			$rules['Betalningskod'] = new Rule('N', 2, '', '/^[38]2$/');
			$rules['Reservfält_51_58'] = new Rule('A', 8, '', '/^ *$/');
			$rules['Referense'] = new Rule('A', 16);
			$rules['Reservfält_75_80'] = new Rule('A', 6, '', '/^ *$/');

			return $rules;
		}

		// TODO Tk26-29, page 30: Poster för ändring av betalningsdag (TK26, TK27, TK28 och TK29)

		//</editor-fold>

		//<editor-fold desc="tk3x">
		/**
		 * Utbetalningspost
		 */
		static function tk32()
		{
			$tk = 32;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Betalningsdag'] = new Rule('AN', 8, 'GENAST', '/^(GENAST *|[0-9]{4}[0-1][0-9][0-3][0-9])$/');
			$rules['Periodkod'] = new Rule('N', 1, 0, '/^[0-8]$/');
			$rules['Antal'] = new Rule('AN', 3, '', '/^( *|[0-9]+)$/');
			$rules['Reservfält_15'] = new Rule('A', 1, '', '/^ ?$/');
			$rules['Betalarnummer'] = new Rule('N', 16);
			$rules['Belopp'] = new Rule('N', 12);
			$rules['Bankgironummer'] = new Rule('N', 10);
			$rules['Referense'] = new Rule('A', 16);
			$rules['Reservfält_70_80'] = new Rule('A', 11, '', '/^ *$/');

			return $rules;
		}
		//</editor-fold>

		//<editor-fold desc="tk5x">

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
		//</editor-fold>

		//<editor-fold desc="tk7x">
		// TODO Tk73, page 45: Post för nyinlägg och makulering av Medgivanden
		// TODO Tk77, page 40: Betalningspost för Återbetalning
		//</editor-fold>

		//<editor-fold desc="tk8x">
		/**
		 * Inbetalningspost
		 */
		static function tk82()
		{
			$tk = 82;
			$rules = [];
			$rules['Transaktionskod'] = new Rule('N', 2, $tk, '/^' . $tk . '$/');
			$rules['Betalningsdag'] = new Rule('A', 8, 'GENAST', '/^(GENAST *|[0-9]{4}[0-1][0-9][0-3][0-9])$/');
			$rules['Periodkod'] = new Rule('N', 1, 0, '/^[0-8]$/');
			$rules['Antal'] = new Rule('AN', 3, '', '/^( *|[0-9]+)$/');
			$rules['Reservfält_15'] = new Rule('A', 1, '', '/^ ?$/');
			$rules['Betalarnummer'] = new Rule('N', 16);
			$rules['Belopp'] = new Rule('N', 12);
			$rules['Bankgironummer'] = new Rule('N', 10);
			$rules['Referense'] = new Rule('A', 16);
			$rules['Reservfält_70_80'] = new Rule('A', 11, '', '/^ *$/');

			return $rules;
		}
		//</editor-fold>
	}