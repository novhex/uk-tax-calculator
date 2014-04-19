<?php

require_once 'class-tax-codes.php';
require_once 'class-national-insurance.php';


class Tax_Calculator {

	/*
	 * Sets the default values we need when the class is instantiated.
	 * @param 	array 	$persona 			User submitted inputs
	 * @param 	array 	$income_tax_rates	Raw data for all tax years           
	 */

	public function __construct( $persona ) {
		include 'data/income-tax-rates.php';
		include 'data/national-insurance-rates.php';
		include 'data/student-loan-rates.php';
		include 'data/childcare-voucher-rates.php';
		// Set the persona and values
		$this->persona = $persona;
		$this->year = $this->persona['year'];
		$this->income = $this->persona['income'];
		$this->other = $this->persona['other_allowance'];
		$this->tax_code = $this->persona['tax_code'];
		$this->age = $this->persona['age'];
		$this->pension = $this->persona['pension'];
		$this->pension_every = $this->persona['pension_every'];
		$this->vouchers = $this->persona['vouchers'];
		$this->childcare_pre2011 = $this->persona['childcare_pre2011'];
		$this->blind = $this->persona['blind'];
		$this->exclude_ni = $this->persona['exclude_ni'];
		$this->student_loan = $this->persona['student_loan'];
		$this->married = $this->persona['married'];

		// Set tax rates
		$this->rates = $income_tax_rates;
		$this->ni_rates = $national_insurance_rates;
		
		$this->bands = $this->rates[$this->year]['rates'];
		$this->allowances = $this->rates[$this->year]['allowances'];
		$this->student_rates = $student_loan_rates[ $this->year ];
		$this->voucher_rates = $annual_childcare_voucher_rates;
	}


	/*
	 * Gets the personal allowance figure based on the users age.
	 * @return 	integer  The personal allowance for chosen tax year, by age	 	          
	 */

	public function get_personal_allowance() {
		if ( '65_74' == $this->age ) {
			$allowance = $this->allowances['personal_for_people_aged_65_74'];
			return $allowance;

		} elseif ( 'over_75' == $this->age ) {
			$allowance = $this->allowances['personal_for_people_aged_75_and_over'];
			return $allowance;
		} else {
			$allowance = $this->allowances['personal'];
			return $allowance;
		}
		
	}

	/*
	 * Find and set the income allowance limit
	 * @return 	integer   The income limit for chosen tax year, by age           
	 */

	public function get_income_allowance_limit() {

			if ( '65_74' == $this->age || 'over_75' == $this->age ) {
				$allowance_limit = $this->allowances['income_limit_for_age_related'];
				return $allowance_limit;
			} else {
				$allowance_limit = $this->allowances['income_limit_for_personal'];
				return $allowance_limit;
			}
	}

	/*
	 * Calculate the tax free amount that user is entitled to
	 * @return 	integer   The tax free allowance for chosen tax year           
	 */

	public function get_tax_free_allowance() {
		$personal_allowance = $this->get_personal_allowance();
		$income_allowance_limit = $this->get_income_allowance_limit();

		if ( $this->income > $income_allowance_limit ) {
			$deduct_from_allowance = ($this->income - $income_allowance_limit) / 2;
			$personal_allowance = $personal_allowance - $deduct_from_allowance;

			

			if ( '65_74' == $this->age || 'over_75' == $this->age ) {
				if ( $personal_allowance <= $this->allowances['personal'] ) {
					$personal_allowance = $this->allowances['personal'];
					$income_allowance_limit = $this->allowances['income_limit_for_personal'];

					if ( $this->income > $income_allowance_limit ) {

					$deduct_from_allowance = ($this->income - $income_allowance_limit) / 2;
					$personal_allowance = $personal_allowance - $deduct_from_allowance;
				}

				}
			}

		}

		if ( is_numeric( $this->other ) ) {
				$personal_allowance += $this->other;
			}

			if ( $personal_allowance < 0 ) {
				$personal_allowance = 0;
			} 

		return $personal_allowance;

	}

	/*
	 * Finds the blind allowance for the chosen tax year
	 * @return 	integer   Blind persons allowance          
	 */

	public function get_blind_persons_allowance() {
		$blind_persons_allowance = $this->allowances['blind_persons'];
		return $blind_persons_allowance;
	}

	/*
	 * Determines whether user is eligible for married couples allowance
	 * @return 	integer   Married couples allowance (10% of the allowance)          
	 */

	public function get_married_couples_allowance() {
			$married_allowance = ($this->allowances['married_couples_over_75'] / 100) * 10;
			return $married_allowance;
		}

	/*
	 * Determines the personal allowance based on entered tax code
	 * Replaces tax free allowance with calculated amount if the code isn't K
	 * Adds the calculated amount to the total taxable amount if it is K
	 * @return 	integer   Personal allowance by tax code          
	 */

	public function get_tax_code_personal_allowance() {
			$tax_code_calculator = new Tax_Code_Calculator( $this->tax_code );

			$this->tax_code_personal_allowance = $tax_code_calculator->get_personal_allowance_from_code();
			$this->tax_code_letter = $tax_code_calculator->tax_code_letter;

			if ( is_numeric( $this->tax_code_personal_allowance ) && 'K' == $this->tax_code_letter ) {
				$this->total_taxable_amount = $this->show_gross_income + $this->tax_code_personal_allowance;
				$this->show_tax_free_allowance = 0;
			} elseif ( is_numeric( $this->tax_code_personal_allowance ) && 'K' != $this->tax_code_letter ) {
				$this->show_tax_free_allowance = $this->tax_code_personal_allowance;
				$this->total_taxable_amount = $this->show_gross_income - $this->show_tax_free_allowance;
			} 
	}

	/*
	 * Checks if the tax code is one of the special codes to work out
	 * Compares the total taxable amount against the tax bands for chosen year
	 * and works out the value of tax for each banding
	 * @return     integer   Personal allowance by tax code          
	 */

	public function calculate_tax_bands() {

		unset( $this->bands['savings'] );

		if ( isset( $this->tax_code ) ) {
			$output = array();
			switch( $this->tax_code ) {
				case 'BR':
					// Basic Rate percentage
					$this->show_tax_free_allowance = 0;
					$this->total_taxable_amount = $this->show_gross_income;
					$band_percentage = $this->bands['basic']['rate'];
					$percentage_amount = ($this->total_taxable_amount / 100) * $band_percentage;
					$output['basic'] = round( $percentage_amount );
					$output['higher'] = 0;
					$output['additional'] = 0;
					return $output;

				case 'D0':
					// Higher Band percentage
					$this->show_tax_free_allowance = 0;
					$this->total_taxable_amount = $this->show_gross_income;
					$band_percentage = $this->bands['higher']['rate'];
					$percentage_amount = ($this->total_taxable_amount / 100) * $band_percentage;
					$output['basic'] = 0;
					$output['higher'] = round( $percentage_amount );
					$output['additional'] = 0;
					return $output;
				case 'D1':
					// Additional Band percentage
					$this->show_tax_free_allowance = 0;
					$this->total_taxable_amount = $this->show_gross_income;
					$band_percentage = $this->bands['additional']['rate'];
					$percentage_amount = ($this->total_taxable_amount / 100) * $band_percentage;
					$output['basic'] = 0;
					$output['higher'] = 0;
					$output['additional'] = round( $percentage_amount );
					$this->show_tax_free_allowance = 0;
					return $output;
				case 'NT':
					// No Tax
					return 0;

			}
		}


		$values = array();
		foreach ( $this->bands as $key => $band ) {

					if ( $band['end'] != null || $band['end'] > 0 ) {
						$band['amount'] = min( $this->total_taxable_amount, $band['end'] ) - $band['start'];
					} else {
						$band['amount'] = $this->total_taxable_amount - $band['start'];
					}

				$band['percentage_amount'] = ($band['amount'] / 100) * $band['rate'];
				$total_deduction = $band['percentage_amount'];
				
				if ( $total_deduction < 0 ) {
					$total_deduction = 0;
				}

				$values[ $key ] = $total_deduction;
			
		}
		return $values;

	}

	/*
	 * Takes total weekly income less deductions and works out the national
	 * insurance contributions for the primary and upper bandings.
	 * @return     integer   Annual national insurance contributions 
	 */

	public function get_national_insurance_contribution() {
		$national_insurance_calculator = new National_Insurance_Calculator( 
										($this->income - $this->show_childcare_vouchers) / 52, $this->year, $this->ni_rates );

		$total_ni_contribution = $national_insurance_calculator->get_ni_contributions();

		return $total_ni_contribution;
	}

	/*
	 * Takes gross income less deductions and works out whether the income
	 * is over the start amount before calculating the repayment amount
	 * Student loans are also rounded down to the nearest pound.
	 * @return     integer   Annual student loan repayment amount    
	 */

	public function get_student_loan_repayment() {
		if ( $this->income >= $this->student_rates['start'] ) {
			$deductable_amount = $this->income - $this->student_rates['start'];

			if ( isset( $this->show_childcare_vouchers ) ) {
				$deductable_amount -= $this->show_childcare_vouchers;
			}

			$deduction = ($deductable_amount / 100) * $this->student_rates['rate'];
		}

		return floor( $deduction );
	}

	/*
	 * Checks the pension amount for a % symbol and if found calculates the
	 * percentage based on the annual income.  If there is no %, the entered
	 * amount will be used instead.
	 * @return     integer   Annual pension amount    
	 */

	public function get_employers_pension_amount() {
		preg_match( '/[%]/', $this->pension, $pension_percentage );

		if ( ! empty( $pension_percentage ) && '%' == $pension_percentage[0] ) {
			$pension_percentage_amount = preg_replace( '/\D/', '', $this->pension );

			if ( 'month' == $this->pension_every ) {
				$monthly_income = $this->income / 52;

				$pension_amount = ($monthly_income / 100) * $pension_percentage_amount;
				$annual_amount = $pension_amount * 52;

				return $annual_amount;
			} else {
				$annual_amount = ($this->income / 100) * $pension_percentage_amount;

				return $annual_amount;
			}
		} else {
			if ( 'month' == $this->pension_every ) {
				$monthly_income = $this->income / 52;

				$pension_amount = $this->pension;
				$annual_amount = $pension_amount * 52;

				return $annual_amount;
			} else {
				$annual_amount = $this->pension;

				return $annual_amount;
			}
		}

	}

	/*
	 * Checks tax banding to see whether income is in a higher or additional band
	 * If so, calculates the pension relief amount by multiplying the pension
	 * amount by the tax band rate.
	 * @return     integer   Annual HMRC pension relief    
	 */

	public function get_hmrc_employers_pension_amount( $pension_amount ) {
		$tax_bands = $this->calculate_tax_bands();

		if ( $tax_bands['higher'] > 0 && 0 == $tax_bands['additional'] ) {
			$pension_hmrc = ($pension_amount / 100) * $this->bands['higher']['rate'];

			return $pension_hmrc;

		} elseif ( $tax_bands['additional'] > 0 ) {
			$pension_hmrc = ($pension_amount / 100) * $this->bands['additional']['rate'];

			return $pension_hmrc;
		} else {
			$pension_hmrc = ($pension_amount / 100) * $this->bands['basic']['rate'];

			return $pension_hmrc;
		}

	}

	/*
	 * Checks whether the childcare voucher amount is within the limits allowed and 
	 * if too high, returns the maximum allowed amount.  If the amount is in a higher
	 * or additional tax band, a lower amount will be used.
	 * @return     integer   Annual childcare voucher amount   
	 */

	public function get_childcare_voucher_amount() {
		$income = $this->income;
		$bands = $this->bands;
		$vouchers = $this->vouchers;
		$rates = $this->voucher_rates;
		$pre2011 = $this->childcare_pre2011;

		if ( $vouchers > $rates['basic'] ) {
			$vouchers = $rates['basic'];
		}

		if ( $income >= $bands['higher']['start'] && $vouchers > $rates['higher'] && '' == $pre2011 ) {
			$vouchers = $rates["higher"];

		} 

		if ( $income >= $bands['additional']['start'] && $vouchers > $rates['additional'] && '' == $pre2011 ) {
			if ( 'year2013_14' == $this->year || 'year_2014_15' == $this->year ) {
				$rates['additional'] = 1320;

				$vouchers = $rates['additional'];
			}

			$vouchers = $rates['additional'];
		} 

		return $vouchers;

	}

	/*
	 * Calculate the taxes for user and pull all figures together
	 * @return 	mixed   Return everything we need to populate the tax calculation table         
	 */

	public function calculate_taxes() {

		$this->show_gross_income = $this->income;
		$this->show_tax_free_allowance = $this->get_tax_free_allowance();
		$this->total_taxable_amount = $this->show_gross_income - $this->show_tax_free_allowance;
		$this->show_total_deduction = 0;

		if ( 'on' == $this->married && 'over_75' == $this->age ) {
			$this->show_married_allowance = $this->get_married_couples_allowance();
		}

		if ( 'on' == $this->blind ) {
			$this->show_blind_allowance = $this->get_blind_persons_allowance();
			$this->show_tax_free_allowance += $this->show_blind_allowance;
			$this->total_taxable_amount -= $this->show_blind_allowance;
		}

		if ( isset( $this->tax_code ) ) {
			$this->get_tax_code_personal_allowance();
		}

		if ( isset( $this->pension ) ) {
			$this->show_employer_pension = $this->get_employers_pension_amount();
			$this->show_pension_hmrc = $this->get_hmrc_employers_pension_amount( $this->show_employer_pension );
			$this->total_taxable_amount -= $this->show_employer_pension;
			$this->show_total_deduction += $this->show_employer_pension;
		}

		if ( isset( $this->vouchers ) ) {
			$this->show_childcare_vouchers = $this->get_childcare_voucher_amount();
			$this->total_taxable_amount -= $this->show_childcare_vouchers;
			$this->show_total_deduction += $this->show_childcare_vouchers;
		}

		if ( 'on' == $this->student_loan ) {
			$this->show_student_loan_amount = $this->get_student_loan_repayment();
			$this->showTotalDeduction += $this->show_student_loan_amount;
		}

		if ( 'on' == $this->exclude_ni || 'over_75' == $this->age || '65_74' == $this->age ) {
			$this->show_ni_contribution = 0;
		} else {
			$this->show_ni_contribution = $this->get_national_insurance_contribution();
			$this->show_total_deduction += $this->show_ni_contribution;
		}

		if ( $this->show_gross_income <= $this->show_tax_free_allowance ) {
			$this->total_taxable_amount = 0;
			$this->total_tax_due = 0;
		} else {
			$this->deduction = $this->calculate_tax_bands();
			$this->total_tax_due = $this->deduction['basic'] + $this->deduction['higher'] + $this->deduction['additional'];
			$this->show_total_deduction += $this->total_tax_due;
		} 

		$this->show_net_income = $this->show_gross_income - $this->show_total_deduction;
	}

}