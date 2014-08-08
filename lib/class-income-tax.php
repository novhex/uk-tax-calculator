<?php

require_once 'class-tax-codes.php';
require_once 'class-national-insurance.php';

class Tax_Calculator {

	const TAX_YEAR = 'year';
	const MONTH = 'month';
	const INCOME = 'income';
	const OTHER_ALLOWANCE = 'other_allowance';
	const TAX_CODE = 'tax_code';
	const AGE = 'age';
	const PENSION = 'pension';
	const PENSION_EVERY = 'pension_every';
	const VOUCHERS = 'vouchers';
	const CHILDCARE_PRE2011 = 'childcare_pre2011';
	const BLIND = 'blind';
	const ALLOWANCE_BLIND = 'blind_persons';
	const EXCLUDE_NI = 'exclude_ni';
	const STUDENT_LOAN = 'student_loan';
	const MARRIED = 'married';
	const RATES = 'rates';
	const ALLOWANCES = 'allowances';
	const ALLOWANCE_PERSONAL = 'personal';
	const ALLOWANCE_PERSONAL_65_74 = 'personal_for_people_aged_65_74';
	const ALLOWANCE_PERSONAL_75_AND_OVER = 'personal_for_people_aged_75_and_over';
	const AGE_65_74 = '65_74';
	const AGE_OVER_75 = 'over_75';
	const INCOME_LIMIT_FOR_PERSONAL = 'income_limit_for_personal';
	const INCOME_LIMIT_FOR_AGE_RELATED = 'income_limit_for_age_related';
	const ALLOWANCE_MARRIED_COUPLES_OVER_75 = 'married_couples_over_75';
	const TAX_CODE_K = 'K';
	const SAVINGS = 'savings';
	const TAX_CODE_BR = 'BR';
	const TAX_CODE_D0 = 'D0';
	const TAX_CODE_D1 = 'D1';
	const TAX_CODE_NT = 'NT';
	const BAND_START = 'start';
	const BAND_END = 'end';
	const BAND_RATE = 'rate';
	const BAND_BASIC = 'basic';
	const BAND_HIGHER = 'higher';
	const BAND_ADDITIONAL = 'additional';
	const AMOUNT = 'amount';
	const PERCENTAGE_AMOUNT = 'percentage_amount';
	const ON = 'on';
	const YEAR2013_14 = 'year2013_14';
	const YEAR2014_15 = 'year2014_15';

	/*
	 * Sets the default values we need when the class is instantiated.
	 *
	 * @param array $persona User submitted inputs
	 * @param array $income_tax_rates Raw data for all tax years           
	 */
	public function __construct( $persona ) {
		include 'data/income-tax-rates.php';
		include 'data/national-insurance-rates.php';
		include 'data/student-loan-rates.php';
		include 'data/childcare-voucher-rates.php';

		// Set the persona and values
		$this->persona = $persona;
		$this->year = $this->persona[ self::TAX_YEAR ];
		$this->income = $this->persona[ self::INCOME ];
		$this->other = $this->persona[ self::OTHER_ALLOWANCE ];
		$this->tax_code = $this->persona[ self::TAX_CODE ];
		$this->age = $this->persona[ self::AGE ];
		$this->pension = $this->persona[ self::PENSION ];
		$this->pension_every = $this->persona[ self::PENSION_EVERY ];
		$this->vouchers = $this->persona[ self::VOUCHERS ];
		$this->childcare_pre2011 = $this->persona[ self::CHILDCARE_PRE2011 ];
		$this->blind = $this->persona[ self::BLIND ];
		$this->exclude_ni = $this->persona[ self::EXCLUDE_NI ];
		$this->student_loan = $this->persona[ self::STUDENT_LOAN ];
		$this->married = $this->persona[ self::MARRIED ];

		// Set tax rates
		$this->rates = $income_tax_rates;
		$this->ni_rates = $national_insurance_rates;	
		$this->bands = $this->rates[$this->year][ self::RATES ];
		$this->allowances = $this->rates[$this->year][ self::ALLOWANCES ];
		$this->student_rates = $student_loan_rates[ $this->year ];
		$this->voucher_rates = $annual_childcare_voucher_rates;
	}

	/*
	 * Gets the personal allowance figure based on the users age.
	 *
	 * @return int The personal allowance for chosen tax year, by age	 	          
	 */
	public function get_personal_allowance() {
		if ( self::AGE_65_74 === $this->age ) {
			return $this->allowances[ self::ALLOWANCE_PERSONAL_65_74];
		} elseif ( self::AGE_OVER_75 === $this->age ) {
			return $this->allowances[ self::ALLOWANCE_PERSONAL_75_AND_OVER ];
		} else {
			return $this->allowances[ self::ALLOWANCE_PERSONAL ];
		}	
	}

	/*
	 * Find and set the income allowance limit
	 *
	 * @return int The income limit for chosen tax year, by age           
	 */
	public function get_income_allowance_limit() {
		if ( self::AGE_65_74 === $this->age || self::AGE_OVER_75 === $this->age ) {
			return $this->allowances[ self::INCOME_LIMIT_FOR_AGE_RELATED];
		} else {
			return $this->allowances[ self::INCOME_LIMIT_FOR_PERSONAL ];
		}
	}

	/*
	 * Calculate the tax free amount that user is entitled to
	 *
	 * @return int The tax free allowance for chosen tax year           
	 */
	public function get_tax_free_allowance() {
		$personal_allowance = $this->get_personal_allowance();
		$income_allowance_limit = $this->get_income_allowance_limit();

		if ( $this->income > $income_allowance_limit ) {
			$deduct_from_allowance = ( $this->income - $income_allowance_limit ) / 2;
			$personal_allowance = $personal_allowance - $deduct_from_allowance;

			if ( self::AGE_65_74 === $this->age || self::AGE_OVER_75 === $this->age ) {
				if ( $personal_allowance <= $this->allowances[ self::ALLOWANCE_PERSONAL ] ) {
					$personal_allowance = $this->allowances[ self::ALLOWANCE_PERSONAL ];
					$income_allowance_limit = $this->allowances[ self::INCOME_LIMIT_FOR_PERSONAL ];

					if ( $this->income > $income_allowance_limit ) {
						$deduct_from_allowance = ( $this->income - $income_allowance_limit ) / 2;
						$personal_allowance = $personal_allowance - $deduct_from_allowance;
					}
				}
			}
		}

		// Note: this isn't working when using a K tax code. 
		// TODO: Add/Deduct $this->other to/from total_taxable_amount instead
		if ( isset( $this->other ) ) {
				$personal_allowance += $this->other;
			}

			if ( $personal_allowance < 0 ) {
				$personal_allowance = 0;
			} 

		return $personal_allowance;
	}

	/*
	 * Finds the blind allowance for the chosen tax year
	 *
	 * @return int Blind persons allowance          
	 */
	public function get_blind_persons_allowance() {
		return $this->allowances[ self::ALLOWANCE_BLIND ];
	}

	/*
	 * Determines whether user is eligible for married couples allowance
	 *
	 * @return int Married couples allowance (10% of the allowance)          
	 */
	public function get_married_couples_allowance() {
		return ( $this->allowances[ self::ALLOWANCE_MARRIED_COUPLES_OVER_75 ] / 100 ) * 10;
		}

	/*
	 * Determines the personal allowance based on entered tax code
	 * Replaces tax free allowance with calculated amount if the code isn't K
	 * Adds the calculated amount to the total taxable amount if it is K
	 *
	 * @return int Personal allowance by tax code          
	 */
	public function get_tax_code_personal_allowance() {
		$tax_code_calculator = new Tax_Code_Calculator( $this->tax_code );

		$this->tax_code_personal_allowance = $tax_code_calculator->get_personal_allowance_from_code();
		$this->tax_code_letter = $tax_code_calculator->tax_code_letter;

		if ( is_numeric( $this->tax_code_personal_allowance ) && self::TAX_CODE_K === $this->tax_code_letter ) {
			$this->total_taxable_amount = $this->show_gross_income + $this->tax_code_personal_allowance;
			$this->show_tax_free_allowance = 0;
		} elseif ( is_numeric( $this->tax_code_personal_allowance ) && self::TAX_CODE_K !== $this->tax_code_letter ) {
			$this->show_tax_free_allowance = $this->tax_code_personal_allowance;
			$this->total_taxable_amount = $this->show_gross_income - $this->show_tax_free_allowance;
		} 
	}

	/*
	 * Checks if the tax code is one of the special codes to work out
	 * Compares the total taxable amount against the tax bands for chosen year
	 * and works out the value of tax for each banding
	 *
	 * @return int Personal allowance by tax code          
	 */
	public function calculate_tax_bands() {
		unset( $this->bands[ self::SAVINGS ] );

		if ( isset( $this->tax_code ) ) {
			$output = array();
			switch( $this->tax_code ) {
				case self::TAX_CODE_BR:
					// Basic Rate percentage
					$this->show_tax_free_allowance = 0;
					$this->total_taxable_amount = $this->show_gross_income;
					$band_percentage = $this->bands[ self::BAND_BASIC ][ self::BAND_RATE ];
					$percentage_amount = ( $this->total_taxable_amount / 100 ) * $band_percentage;
					$output[ self::BAND_BASIC ] = round( $percentage_amount );
					$output[ self::BAND_HIGHER ] = 0;
					$output[ self::BAND_ADDITIONAL ] = 0;

					return $output;
				case self::TAX_CODE_D0:
					// Higher Band percentage
					$this->show_tax_free_allowance = 0;
					$this->total_taxable_amount = $this->show_gross_income;
					$band_percentage = $this->bands[ self::BAND_HIGHER ][ self::BAND_RATE ];
					$percentage_amount = ( $this->total_taxable_amount / 100 ) * $band_percentage;
					$output[ self::BAND_BASIC ] = 0;
					$output[ self::BAND_HIGHER ] = round( $percentage_amount );
					$output[ self::BAND_ADDITIONAL ] = 0;

					return $output;
				case self::TAX_CODE_D1:
					// Additional Band percentage
					$this->show_tax_free_allowance = 0;
					$this->total_taxable_amount = $this->show_gross_income;
					$band_percentage = $this->bands[ self::BAND_ADDITIONAL ][ self::BAND_RATE ];
					$percentage_amount = ( $this->total_taxable_amount / 100 ) * $band_percentage;
					$output[ self::BAND_BASIC ] = 0;
					$output[ self::BAND_HIGHER ] = 0;
					$output[ self::BAND_ADDITIONAL ] = round( $percentage_amount );
					$this->show_tax_free_allowance = 0;

					return $output;
				case self::TAX_CODE_NT:
					// No Tax

					return 0;
			}
		}

		$values = array();
		foreach ( $this->bands as $key => $band ) {
			if ( null !== $band[ self::BAND_END ] || $band[ self::BAND_END ] > 0 ) {
				$band[ self::AMOUNT ] = min( $this->total_taxable_amount, $band[ self::BAND_END ] ) - $band[ self::BAND_START ];
			} else {
				$band[ self::AMOUNT ] = $this->total_taxable_amount - $band[ self::BAND_START ];
			}

			$band[ self::PERCENTAGE_AMOUNT ] = ( $band[ self::AMOUNT ] / 100 ) * $band[ self::BAND_RATE ];
			$total_deduction = $band[ self::PERCENTAGE_AMOUNT ];
				
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
	 *
	 * @return int Annual national insurance contributions 
	 */
	public function get_national_insurance_contribution() {
		$annual_vouchers = $this->get_childcare_voucher_amount();
		$national_insurance_calculator = new National_Insurance_Calculator( 
										( $this->income - $annual_vouchers ) / 52, $this->year, $this->ni_rates );

		return $national_insurance_calculator->get_ni_contributions();
	}

	/*
	 * Takes gross income less deductions and works out whether the income
	 * is over the start amount before calculating the repayment amount
	 * Student loans are also rounded down to the nearest pound.
	 *
	 * @return int Annual student loan repayment amount    
	 */
	public function get_student_loan_repayment() {
		if ( $this->income >= $this->student_rates[ self::BAND_START ] ) {
			$deductable_amount = $this->income - $this->student_rates[ self::BAND_START ];

			if ( isset( $this->vouchers ) ) {
				$deductable_amount -= $this->vouchers;
			}

			$deduction = ( $deductable_amount / 100 ) * $this->student_rates[ self::BAND_RATE ];

			return floor( $deduction );
		}
	}

	/*
	 * Checks the pension amount for a % symbol and if found calculates the
	 * percentage based on the annual income.  If there is no %, the entered
	 * amount will be used instead.
	 *
	 * @return int Annual pension amount    
	 */
	public function get_employers_pension_amount() {
		preg_match( '/[%]/', $this->pension, $pension_percentage );

		if ( ! empty( $pension_percentage ) && '%' === $pension_percentage[0] ) {
			$pension_percentage_amount = preg_replace( '/\D/', '', $this->pension );

			if ( self::MONTH === $this->pension_every ) {
				$monthly_income = $this->income / 12;

				$pension_amount = ( $monthly_income / 100 ) * $pension_percentage_amount;
				$annual_amount = $pension_amount * 12;

				return $annual_amount;
			} else {
				$annual_amount = ( $this->income / 100 ) * $pension_percentage_amount;

				return $annual_amount;
			}
		} else {
			if ( self::MONTH === $this->pension_every ) {
				
				$pension_amount = $this->pension;
				$annual_amount = $pension_amount * 12;

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
	 *
	 * @return int Annual HMRC pension relief    
	 */
	public function get_hmrc_employers_pension_amount( $pension_amount ) {
		$tax_bands = $this->calculate_tax_bands();

		if ( $tax_bands[ self::BAND_HIGHER ] > 0 && 0 === $tax_bands[ self::BAND_ADDITIONAL ] ) {
			return ($pension_amount / 100) * $this->bands[ self::BAND_HIGHER ][ self::BAND_RATE ];
		} elseif ( $tax_bands[ self::BAND_ADDITIONAL ] > 0 ) {
			return ($pension_amount / 100) * $this->bands[ self::BAND_ADDITIONAL ][ self::BAND_RATE ];
		} else {
			return ($pension_amount / 100) * $this->bands[ self::BAND_BASIC ][ self::BAND_RATE ];
		}
	}

	/*
	 * Checks whether the childcare voucher amount is within the limits allowed and 
	 * if too high, returns the maximum allowed amount.  If the amount is in a higher
	 * or additional tax band, a lower amount will be used.
	 *
	 * @return integer Annual childcare voucher amount   
	 */
	public function get_childcare_voucher_amount() {
		$income = $this->income;
		$bands = $this->bands;
		$vouchers = $this->vouchers;
		$rates = $this->voucher_rates;
		$pre2011 = $this->childcare_pre2011;

		if ( $vouchers > $rates[ self::BAND_BASIC ] ) {
			$vouchers = $rates[ self::BAND_BASIC ];
		}

		if ( $income >= $bands[ self::BAND_HIGHER ][ self::BAND_START ] && $vouchers > $rates[ self::BAND_HIGHER ] && self::ON !== $pre2011 ) {
			$vouchers = $rates[ self::BAND_HIGHER ];
		} 

		if ( $income >= $bands[ self::BAND_ADDITIONAL ][ self::BAND_START ] && $vouchers > $rates[ self::BAND_ADDITIONAL ] && self::ON !== $pre2011 ) {
			if ( self::YEAR2013_14 === $this->year || self::YEAR2014_15 === $this->year ) {
				$rates[ self::BAND_ADDITIONAL ] = 1320;

				$vouchers = $rates[ self::BAND_ADDITIONAL ];
			}

			$vouchers = $rates[ self::BAND_ADDITIONAL ];
		} 

		return $vouchers;
	}

	/*
	 * Calculate the taxes for user and pull all figures together
	 *
	 * @return mixed Return everything we need to populate the tax calculation table         
	 */
	public function calculate_taxes() {
		$this->show_gross_income = $this->income;
		$this->show_tax_free_allowance = $this->get_tax_free_allowance();
		$this->total_taxable_amount = $this->show_gross_income - $this->show_tax_free_allowance;
		$this->show_total_deduction = 0;

		if ( self::ON === $this->married && self::AGE_OVER_75 === $this->age ) {
			$this->show_married_allowance = $this->get_married_couples_allowance();
		}

		if ( self::ON === $this->blind ) {
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

		if ( self::ON === $this->student_loan ) {
			$this->show_student_loan_amount = $this->get_student_loan_repayment();
			$this->show_total_deduction += $this->show_student_loan_amount;
		}

		if ( self::ON === $this->exclude_ni || self::AGE_OVER_75 === $this->age || self::AGE_65_74 === $this->age ) {
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
			$this->total_tax_due = $this->deduction[ self::BAND_BASIC ] + $this->deduction[ self::BAND_HIGHER ] + $this->deduction[ self::BAND_ADDITIONAL ];
			$this->show_total_deduction += $this->total_tax_due;
		} 

		$this->show_net_income = $this->show_gross_income - $this->show_total_deduction;
	}
}