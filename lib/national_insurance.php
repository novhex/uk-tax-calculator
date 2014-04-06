<?php




class NationalInsuranceCalculator {

	public $weekly_income;
	public $tax_year;
	public $ni_bands;

	public function __construct($weekly_income, $tax_year, $ni_bands) {
		$this->weekly_income = $weekly_income;
		$this->tax_year = $tax_year;
		$this->get_ni_bands = $ni_bands;
		$this->ni_bands = $this->get_ni_bands[$this->tax_year];
	}

/*
 * Takes two numbers and determines which is the lower figure.
 * @param 	integer   $a,$b   Used to compare integers in other functions
 * @return 	integer 		  The lowest value of the two checked 			          
 */
	
	public function get_lower_figure($a, $b) {
		if ($a <= $b) {
			return $a;
		} else {
			return $b;
		}
	}

/*
 * Loops through the national insurance bands to check if the income is
 * within the band to find the total weekly contribution.
 * @return 	float 		  The lowest value of the two checked 			          
 */

	public function get_ni_contributions() {
		$bandDeductions = 0;
		$values = array();
		foreach ($this->ni_bands as $key => $band) {

			if ($band["end"] === null) {
				$band["end"] = 9999999999999999999;
			}

			if ($this->weekly_income > $band["start"]) {
				if ($band["end"] && $band["end"] > 0) {
					$deductableAmount = $this->get_lower_figure($this->weekly_income, $band["end"]) - $band["start"];
				} else {
					$deductableAmount = $this->weekly_income - $band["start"];
				}

				

			} elseif ($this->weekly_income < $band["start"]) {
				$deductableAmount = 0;
			}
				$bandDeductions = ($deductableAmount / 100) * $band["rate"];
				$values[$key] = $bandDeductions;
		
		}

		$totalContribution = ($values["primary"] + $values["upper"]) * 52;

		return $totalContribution;
	}
		
	



}
