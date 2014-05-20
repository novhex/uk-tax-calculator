<?php

class National_Insurance_Calculator {

	const BAND_START = 'start';
	const BAND_END = 'end';
	const BAND_RATE = 'rate';
	const THRESHOLD_PRIMARY = 'primary';
	const THRESHOLD_UPPER = 'upper';
	const NUMBER_OF_WEEKS = 52;
	const BAND_END_MAX = 9999999999999999999;

	public $weekly_income;
	public $tax_year;
	public $ni_bands;

	public function __construct($weekly_income, $tax_year, $ni_bands) {
		$this->weekly_income = $weekly_income;
		$this->tax_year = $tax_year;
		$this->get_ni_bands = $ni_bands;
		$this->ni_bands = $this->get_ni_bands[ $this->tax_year ];
	}

	/*
	 * Loops through the national insurance bands to check if the income is
	 * within the band to find the total weekly contribution.
	 *
	 * @return float The lowest value of the two checked 			          
	 */
	public function get_ni_contributions() {
		$band_deductions = 0;
		$values = array();
		foreach ( $this->ni_bands as $key => $band ) {

			if ( null === $band[ self::BAND_END ] ) {
				$band[ self::BAND_END ] = self::BAND_END_MAX;
			}

			if ( $this->weekly_income > $band[ self::BAND_START ] ) {
				if ( $band[ self::BAND_END ] && $band[ self::BAND_END ] > 0 ) {
					$deductable_amount = min( $this->weekly_income, $band[ self::BAND_END ] ) - $band[ self::BAND_START ];
				} else {
					$deductable_amount = $this->weekly_income - $band[ self::BAND_START ];
				}

			} elseif ( $this->weekly_income < $band[ self::BAND_START ] ) {
				$deductable_amount = 0;
			}
				$band_deductions = ($deductable_amount / 100) * $band[ self::BAND_RATE ];
				$values[ $key ] = $band_deductions;	
		}

		$total_contribution = ( $values[ self::THRESHOLD_PRIMARY ] + $values[ self::THRESHOLD_UPPER ] ) * self::NUMBER_OF_WEEKS;

		return $total_contribution;
	}
}
