<?php

class Tax_Code_Calculator {

	public $tax_code;
	public $tax_code_letter;
	const D0 = 'D0';
	const D1 = 'D1';
	const BR = 'BR';
	const NT = 'NT';
	const K = 'K';

	public function __construct( $tax_code ) {
		$this->tax_code = $tax_code;
		$this->get_personal_allowance_from_code();
	}

	public function tax_code_calculator() {
		// Find a valid tax code letter from the input field
		// Not required for most letters, but needed in particular for K
		// preg_match('/([KDLYTP]+)/i', $this->tax_code_is, $taxCodeLetter); (Don't need to find all valid tax code letters right now)

		preg_match( '/([K]+)/i', $this->tax_code, $tax_code_letter );

		// Now we've got the letter we can strip it from the string to perform calculations
		$this->personal_allowance = preg_replace( '/\D/', '', $this->tax_code );

		$this->special_tax_codes = array( self::D0, self::D1, self::BR, self::NT );

		if ( ! empty( $tax_code_letter ) ) {
		$this->tax_code_letter = $tax_code_letter[ 0 ];
		}
	}

	/*
	 * Finds the letter in the tax code for calculating the personal allowance
	 * If the code is K, the amount is added to the total taxable
	 * If not, perform the calculation to work out the personal allowance
	 *
	 * @return int Annual personal allowance based on tax code          
	 */
	public function get_personal_allowance_from_code() {

		$this->tax_code_calculator();

		if ( ! empty( $this->personal_allowance ) ) {

			if ( isset( $this->tax_code_letter ) && self::K == $this->tax_code_letter[ 0 ] ) {
				$add_to_total_taxable = $this->personal_allowance * 10;
				return $add_to_total_taxable;
			} elseif ( in_array( $this->tax_code, $this->special_tax_codes ) ) {
				$personalAllowance = 0;
			} else {

				$division = $this->personal_allowance / 500;
				$quotient = floor( $division );
				$fraction = $division - $quotient;
				$remainder = $fraction *  500;

				$personal_allowance = $quotient * 500 * 10 + (($remainder * 10) + 9);
				return $personal_allowance;

			}
		}
	}
}