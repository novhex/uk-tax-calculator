<?php




class National_Insurance_Calculator {

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
	 * @return 	float 		  The lowest value of the two checked 			          
	 */

	public function get_ni_contributions() {
		$band_deductions = 0;
		$values = array();
		foreach ( $this->ni_bands as $key => $band ) {

			if ( null == $band['end'] ) {
				$band['end'] = 9999999999999999999;
			}

			if ( $this->weekly_income > $band['start'] ) {
				if ( $band['end'] && $band['end'] > 0 ) {
					$deductable_amount = min( $this->weekly_income, $band['end'] ) - $band['start'];
				} else {
					$deductable_amount = $this->weekly_income - $band['start'];
				}

				

			} elseif ( $this->weekly_income < $band['start'] ) {
				$deductable_amount = 0;
			}
				$band_deductions = ($deductable_amount / 100) * $band['rate'];
				$values[ $key ] = $band_deductions;
		
		}

		$total_contribution = ($values['primary'] + $values['upper']) * 52;

		return $total_contribution;
	}
		
	



}
