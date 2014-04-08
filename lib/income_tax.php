<?php

require_once('tax_codes.php');
require_once('national_insurance.php');


class TaxCalculation {

	public $persona;
	public $taxRates;
	public $taxBand;
	public $taxFreeAllowance;
	public $personalAllowance;
	public $totalTaxableAmount;

/*
 * Sets the default values we need when the class is instantiated.
 * @param 	array 	$persona 			User submitted inputs
 * @param 	array 	$income_tax_rates	Raw data for all tax years           
 */

	public function __construct($persona) {
		include('data/income_tax_rates.php');
		include('data/national_insurance_rates.php');
		include('data/student_loan_rates.php');
		include('data/childcare_voucher_rates.php');
		$this->persona = $persona;
		$this->taxRates = $income_tax_rates;
		$this->niRates = $national_insurance_rates;
		$this->taxYear = $this->persona["tax_year_is"];
		$this->taxBand = $this->taxRates[$this->taxYear]["rates"];
		$this->taxFreeAllowance = $this->taxRates[$this->taxYear]["allowances"];
		$this->studentRates = $student_loan_rates[$this->taxYear];
		$this->childCareVoucher = $annual_childcare_voucher_rates;
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
 * Gets the personal allowance figure based on the users age.
 * @return 	integer  The personal allowance for chosen tax year, by age	 	          
 */

	public function get_personal_allowance() {
		if ($this->persona["age_is"] === "65_74") {
			$allowance = $this->taxFreeAllowance["personal_for_people_aged_65_74"];
			return $allowance;

		} elseif ($this->persona["age_is"] === "over_75") {
			$allowance = $this->taxFreeAllowance["personal_for_people_aged_75_and_over"];
			return $allowance;
		} else {
			$allowance = $this->taxFreeAllowance["personal"];
			return $allowance;
		}
		
	}

/*
 * Find and set the income allowance limit
 * @return 	integer   The income limit for chosen tax year, by age           
 */

	public function get_income_allowance_limit() {

			if ($this->persona["age_is"] === "65_74" || $this->persona["age_is"] === "over_75") {
				$allowanceLimit = $this->taxFreeAllowance["income_limit_for_age_related"];
				return $allowanceLimit;
			} else {
				$allowanceLimit = $this->taxFreeAllowance["income_limit_for_personal"];
				return $allowanceLimit;
			}
	}

/*
 * Calculate the tax free amount that user is entitled to
 * @return 	integer   The tax free allowance for chosen tax year           
 */

	public function get_tax_free_allowance() {
		$personalAllowance = $this->get_personal_allowance();
		$incomeAllowanceLimit = $this->get_income_allowance_limit();

		if ($this->persona["gross_annual_income"] > $incomeAllowanceLimit) {
			$deductFromAllowance = ($this->persona["gross_annual_income"] - $incomeAllowanceLimit) / 2;
			$personalAllowance = $personalAllowance - $deductFromAllowance;

			

			if ($this->persona["age_is"] === "65_74" || $this->persona["age_is"] === "over_75" ) {
				if ($personalAllowance <= $this->taxFreeAllowance["personal"]) {
					$personalAllowance = $this->taxFreeAllowance["personal"];
					$incomeAllowanceLimit = $this->taxFreeAllowance["income_limit_for_personal"];

					if ($this->persona["gross_annual_income"] > $incomeAllowanceLimit) {

					$deductFromAllowance = ($this->persona["gross_annual_income"] - $incomeAllowanceLimit) / 2;
					$personalAllowance = $personalAllowance - $deductFromAllowance;
				}

				}
			}

		}

		if (is_numeric($this->persona["other_allowance_is"])) {
				$personalAllowance += $this->persona["other_allowance_is"];
			}

			if ($personalAllowance < 0) {
				$personalAllowance = 0;
			} 

		return $personalAllowance;

	}

/*
 * Set gross income to a float
 * @return 	integer   Gross annual income         
 */

	public function set_gross_income() {
		$this->grossIncome = floatval($this->persona["gross_annual_income"]);
		return $this->grossIncome;
	}



/*
 * Finds the blind allowance for the chosen tax year
 * @return 	integer   Blind persons allowance          
 */

	public function get_blind_persons_allowance() {
		$blind_persons_allowance = $this->taxFreeAllowance["blind_persons"];
		return $blind_persons_allowance;
	}

/*
 * Determines whether user is eligible for married couples allowance
 * @return 	integer   Married couples allowance (10% of the allowance)          
 */

	public function get_married_couples_allowance() {
			$marriedAllowance = ($this->taxFreeAllowance["married_couples_over_75"] / 100) * 10;
			return $marriedAllowance;
		}

/*
 * Determines the personal allowance based on entered tax code
 * Replaces tax free allowance with calculated amount if the code isn't K
 * Adds the calculated amount to the total taxable amount if it is K
 * @return 	integer   Personal allowance by tax code          
 */

	public function get_tax_code_personal_allowance() {
			$taxCodeCalculator = new TaxCodeCalculator($this->persona["tax_code_is"]);

			$this->taxCodePersonalAllowance = $taxCodeCalculator->get_personal_allowance_from_code();
			$this->taxCodeLetter = $taxCodeCalculator->taxCodeLetter;

			if (is_numeric($this->taxCodePersonalAllowance) && $this->taxCodeLetter === "K") {
				$this->totalTaxableAmount = $this->showGrossIncome + $this->taxCodePersonalAllowance;
				$this->showTaxFreeAllowance = 0;
			} elseif (is_numeric($this->taxCodePersonalAllowance) && $this->taxCodeLetter !== "K") {
				$this->showTaxFreeAllowance = $this->taxCodePersonalAllowance;
				$this->totalTaxableAmount = $this->showGrossIncome - $this->showTaxFreeAllowance;
			} 
	}

/*
 * Checks if the tax code is one of the special codes to work out bands/taxable amounts
 * Compares the total taxable amount against the tax bands for chosen tax year and
 * works out the value of tax for each banding
 * @return 	integer   Personal allowance by tax code          
 */

	public function calculate_tax_bands() {

		unset($this->taxBand["savings"]);

		if (isset($this->persona["tax_code_is"])) {
			$output = array();
			switch($this->persona["tax_code_is"]) {
				case 'BR':
					// Basic Rate percentage
					$this->showTaxFreeAllowance = 0;
					$this->totalTaxableAmount = $this->showGrossIncome;
					$bandPercentage = $this->taxBand["basic"]["rate"];
					$percentageAmount = ($this->totalTaxableAmount / 100) * $bandPercentage;
					$output["basic"] = round($percentageAmount);
					$output["higher"] = 0;
					$output["additional"] = 0;
					return $output;

				case 'D0':
					// Higher Band percentage
					$this->showTaxFreeAllowance = 0;
					$this->totalTaxableAmount = $this->showGrossIncome;
					$bandPercentage = $this->taxBand["higher"]["rate"];
					$percentageAmount = ($this->totalTaxableAmount / 100) * $bandPercentage;
					$output["basic"] = 0;
					$output["higher"] = round($percentageAmount);
					$output["additional"] = 0;
					return $output;
				case 'D1':
					// Additional Band percentage
					$this->showTaxFreeAllowance = 0;
					$this->totalTaxableAmount = $this->showGrossIncome;
					$bandPercentage = $this->taxBand["additional"]["rate"];
					$percentageAmount = ($this->totalTaxableAmount / 100) * $bandPercentage;
					$output["basic"] = 0;
					$output["higher"] = 0;
					$output["additional"] = round($percentageAmount);
					$this->showTaxFreeAllowance = 0;
					return $output;
				case 'NT':
					// No Tax
					return 0;

			}
		}


		$values = array();
		foreach ($this->taxBand as $key => $band) {

					if ($band["end"] !== null || $band["end"] > 0) {
						$band["amount"] = $this->get_lower_figure($this->totalTaxableAmount, $band["end"]) - $band["start"];
					} else {
						$band["amount"] = $this->totalTaxableAmount - $band["start"];
					}

				$band["percentage_amount"] = ($band["amount"] / 100) * $band["rate"];
				$totalDeduction = $band["percentage_amount"];
				
				if ($totalDeduction < 0) {
					$totalDeduction = 0;
				}

				$values[$key] = $totalDeduction;
			
		}
		return $values;

	}

/*
 * Takes total weekly income less deductions and works out the national
 * insurance contributions for the primary and upper bandings.
 * @return 	integer   Annual national insurance contributions          
 */

	public function get_national_insurance_contribution() {
		$nationalInsuranceCalculator = new nationalInsuranceCalculator(
										($this->persona["gross_annual_income"] - $this->showChildCareVouchers) / 52, $this->taxYear, $this->niRates);

		$totalNIContribution = $nationalInsuranceCalculator->get_ni_contributions();

		return $totalNIContribution;
	}

/*
 * Takes gross income less deductions and works out whether the income
 * is over the start amount before calculating the repayment amount.
 * Student loans are also rounded down to the nearest pound.
 * @return 	integer   Annual student loan repayment amount          
 */

	public function get_student_loan_repayment() {
		if ($this->persona["gross_annual_income"] >= $this->studentRates["start"]) {
			$deductableAmount = $this->persona["gross_annual_income"] - $this->studentRates["start"];

			if (isset($this->showChildCareVouchers)) {
				$deductableAmount -= $this->showChildCareVouchers;
			}

			$deduction = ($deductableAmount / 100) * $this->studentRates["rate"];
		}

		return floor($deduction);
	}

/*
 * If the pension amount has a percentage in it, use that percentage to work
 * out the annual amount.  If it's a number, just use that as the annual amount.
 * @return 	integer   Annual pension amount.          
 */

	public function get_employers_pension_amount() {
		preg_match('/[%]/', $this->persona["pension_contribution_is"], $pensionPercentage);

		if (!empty($pensionPercentage) && $pensionPercentage[0] === "%") {
			$pensionPercentageAmount = preg_replace('/\D/', '', $this->persona["pension_contribution_is"]);

			if ($this->persona["pension_every_x"] === "month") {
				$monthlyIncome = $this->persona["gross_annual_income"] / 52;

				$pensionAmount = ($monthlyIncome / 100) * $pensionPercentageAmount;
				$annualAmount = $pensionAmount * 52;

				return $annualAmount;
			} else {
				$annualAmount = ($this->persona["gross_annual_income"] / 100) * $pensionPercentageAmount;

				return $annualAmount;
			}
		} else {
			if ($this->persona["pension_every_x"] === "month") {
				$monthlyIncome = $this->persona["gross_annual_income"] / 52;

				$pensionAmount = $this->persona["pension_contribution_is"];
				$annualAmount = $pensionAmount * 52;

				return $annualAmount;
			} else {
				$annualAmount = $this->persona["pension_contribution_is"];

				return $annualAmount;
			}
		}

	}

/*
 * Using the annual pension amount, check if the income is in a higher or
 * additional band and works out HMRC pension relief amount.
 * @return 	integer   Annual HMRC pension relief amount.          
 */

	public function get_hmrc_employers_pension_amount($pensionAmount) {
		$taxBands = $this->calculate_tax_bands();

		if ($taxBands["higher"] > 0 && $taxBands["additional"] === 0) {
			$pensionHMRC = ($pensionAmount / 100) * $this->taxBand["higher"]["rate"];

			return $pensionHMRC;

		} elseif ($taxBands["additional"] > 0) {
			$pensionHMRC = ($pensionAmount / 100) * $this->taxBand["additional"]["rate"];

			return $pensionHMRC;
		} else {
			$pensionHMRC = ($pensionAmount / 100) * $this->taxBand["basic"]["rate"];

			return $pensionHMRC;
		}

	}

/*
 * Checks the income and which tax band its in the figure out the maximum
 * childcare voucher amount.
 * @return 	integer   Annual childcare voucher amount          
 */

	public function get_childcare_voucher_amount() {
		$income = $this->persona["gross_annual_income"];
		$taxBands = $this->taxBand;
		$annualAmount = $this->persona["annual_childcare_vouchers"];
		$rates = $this->childCareVoucher;
		$pre2011 = $this->persona["is_childcare_pre2011"];

		if ($annualAmount > $rates["basic"]) {
			$annualAmount = $rates["basic"];
		}

		if ($income >= $taxBands["higher"]["start"] && $annualAmount > $rates["higher"] && $pre2011 === "") {
			$annualAmount = $rates["higher"];

		} 

		if ($income >= $taxBands["additional"]["start"] && $annualAmount > $rates["additional"] && $pre2011 === "") {
			if ($this->persona["tax_year_is"] === "year2013_14" || $this->persona["tax_year_is"] === "year2014_15") {
				$rates["additional"] = 1320;

				$annualAmount = $rates["additional"];
			}

			$annualAmount = $rates["additional"];
		} 

		return $annualAmount;

	}

/*
 * Calculate the taxes for user and pull all figures together
 * @return 	mixed   Return everything we need to populate the tax calculation table         
 */

	public function calculate_taxes() {

		$this->showGrossIncome = $this->persona["gross_annual_income"];
		$this->showTaxFreeAllowance = $this->get_tax_free_allowance();
		$this->totalTaxableAmount = $this->showGrossIncome - $this->showTaxFreeAllowance;
		$this->showTotalDeduction = 0;

		if ($this->persona["is_married"] === "on" && $this->persona["age_is"] === "over_75") {
			$this->showMarriedAllowance = $this->get_married_couples_allowance();
		}

		if ($this->persona["is_blind"] === "on") {
			$this->showBlindAllowance = $this->get_blind_persons_allowance();
			$this->showTaxFreeAllowance += $this->showBlindAllowance;
			$this->totalTaxableAmount -= $this->showBlindAllowance;
		}

		if (isset($this->persona["tax_code_is"])) {
			$this->get_tax_code_personal_allowance();
		}

		if (isset($this->persona["pension_contribution_is"])) {
			$this->showEmployerPension = $this->get_employers_pension_amount();
			$this->showPensionHMRC = $this->get_hmrc_employers_pension_amount($this->showEmployerPension);
			$this->totalTaxableAmount -= $this->showEmployerPension;
			$this->showTotalDeduction += $this->showEmployerPension;
		}

		if (isset($this->persona["annual_childcare_vouchers"])) {
			$this->showChildCareVouchers = $this->get_childcare_voucher_amount();
			$this->totalTaxableAmount -= $this->showChildCareVouchers;
			$this->showTotalDeduction += $this->showChildCareVouchers;
		}

		if ($this->persona["has_student_loan"] === "on") {
			$this->showStudentLoanAmount = $this->get_student_loan_repayment();
			$this->showTotalDeduction += $this->showStudentLoanAmount;
		}

		if ($this->persona["exclude_ni"] === "on" || $this->persona["age_is"] === "over_75" || $this->persona["age_is"] === "65_74") {
			$this->showNIContribution = 0;
		} else {
			$this->showNIContribution = $this->get_national_insurance_contribution();
			$this->showTotalDeduction += $this->showNIContribution;
		}

		if ($this->showGrossIncome <= $this->showTaxFreeAllowance) {
			$this->totalTaxableAmount = 0;
			$this->totalTaxDue = 0;
		} else {
			$this->deduction = $this->calculate_tax_bands();
			$this->totalTaxDue = $this->deduction["basic"] + $this->deduction["higher"] + $this->deduction["additional"];
			$this->showTotalDeduction += $this->totalTaxDue;
		} 

		$this->showNetIncome = $this->showGrossIncome - $this->showTotalDeduction;
	}

}