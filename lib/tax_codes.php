<?php

class TaxCodeCalculator {

	public $tax_code_is;
	public $taxCodeLetter;

	public function __construct($taxcode) {
		$this->tax_code_is = $taxcode;
		$this->get_personal_allowance_from_code();
	}

	public function tax_code_calculator() {
		// Find a valid tax code letter from the input field
		// Not required for most letters, but needed in particular for K
		// preg_match('/([KDLYTP]+)/i', $this->tax_code_is, $taxCodeLetter); (Don't need to find all valid tax code letters right now)

		preg_match('/([K]+)/i', $this->tax_code_is, $taxCodeLetter);

		// Now we've got the letter we can strip it from the string to perform calculations
		$this->personalAllowance = preg_replace('/\D/', '', $this->tax_code_is);

		$this->specialTaxCodes = array('D0', 'D1', 'BR', 'NT');

		if (!empty($taxCodeLetter)) {
		$this->taxCodeLetter = $taxCodeLetter[0];
		}
	}

/*
 * Finds the letter in the tax code for calculating the personal allowance
 * If the code is K, the amount is added to the total taxable
 * If not, perform the calculation to work out the personal allowance
 * @return 	integer 			Annual personal allowance based on tax code          
 */

	public function get_personal_allowance_from_code() {

		$this->tax_code_calculator();

		if (!empty($this->personalAllowance)) {

			if (isset($this->taxCodeLetter) && $this->taxCodeLetter[0] === "K") {
				$addToTotalTaxable = $this->personalAllowance * 10;
				return $addToTotalTaxable;
			} elseif (in_array($this->tax_code_is, $this->specialTaxCodes)) {
				$personalAllowance = 0;
			} else {

				$division = $this->personalAllowance / 500;
				$quotient = floor($division);
				$fraction = $division - $quotient;
				$remainder = $fraction *  500;

				$personalAllowance = $quotient * 500 * 10 + (($remainder * 10) + 9);
				return $personalAllowance;

			}
		}
	}

	

}