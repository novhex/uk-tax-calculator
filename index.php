<?php

require_once('lib/income_tax.php');
require_once('lib/data/income_tax_rates.php');
require_once('lib/data/national_insurance_rates.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$tax_year_is = trim($_POST["tax_year_is"]);
	$income_every_x = trim($_POST["income_every_x"]);
	$gross_income_is = trim($_POST["gross_income_is"]);
	$other_allowance_is = trim($_POST["other_allowance_is"]);
	$tax_code_is = trim($_POST["tax_code_is"]);
	$age_is = trim($_POST["age_is"]);
	$pension_contribution_is = trim($_POST["pension_contribution_is"]);
	$pension_every_x = trim($_POST["pension_every_x"]);
	$childcare_vouchers_are = trim($_POST["childcare_vouchers_are"]);
	$vouchers_every_x = trim($_POST["vouchers_every_x"]);

	if ($income_every_x === "day") {
		$gross_annual_income = $gross_income_is * 260;
	} elseif ($income_every_x === "week") {
		$gross_annual_income = $gross_income_is * 52;
	} elseif ($income_every_x === "month") {
		$gross_annual_income = $gross_income_is * 12;
	} elseif ($income_every_x === "year") {
		$gross_annual_income = $gross_income_is;
	}

	if ($vouchers_every_x === "week") {
		$annual_childcare_vouchers = $childcare_vouchers_are * 53; // It's 53 and not 52 due to rounding of the numbers
	} elseif ($vouchers_every_x === "month") {
		$annual_childcare_vouchers = $childcare_vouchers_are * 12;
	}

	if (isset($_POST["is_childcare_pre2011"])) {
		$is_childcare_pre2011 = trim($_POST["is_childcare_pre2011"]);
	} else {
		$is_childcare_pre2011 = NULL;
	}

	if (isset($_POST["is_blind"])) { 
		$is_blind = trim($_POST["is_blind"]);
	} else {
		$is_blind = NULL;
	}

	if (isset($_POST["exclude_ni"])) { 
		$exclude_ni = trim($_POST["exclude_ni"]);
	} else {
		$exclude_ni = NULL;
	}

	if (isset($_POST["has_student_loan"])) { 
		$has_student_loan = trim($_POST["has_student_loan"]);
	} else {
		$has_student_loan = NULL;
	}

	if (isset($_POST["is_married"])) { 
		$is_married = trim($_POST["is_married"]);
	} else {
		$is_married = NULL;
	}

	$persona = array(
		"tax_year_is" 				=> filter_var($tax_year_is, FILTER_SANITIZE_STRING),
		"income_every_x" 			=> filter_var($income_every_x, FILTER_SANITIZE_STRING),
		"gross_annual_income" 		=> filter_var($gross_annual_income, FILTER_SANITIZE_NUMBER_INT),
		"other_allowance_is" 		=> filter_var($other_allowance_is, FILTER_SANITIZE_NUMBER_INT),
		"tax_code_is" 				=> strtoupper(filter_var($tax_code_is, FILTER_SANITIZE_STRING)),
		"age_is" 					=> filter_var($age_is, FILTER_SANITIZE_STRING),
		"pension_contribution_is" 	=> filter_var($pension_contribution_is, FILTER_SANITIZE_STRING),
		"pension_every_x" 			=> filter_var($pension_every_x, FILTER_SANITIZE_STRING),
		"annual_childcare_vouchers" => filter_var($annual_childcare_vouchers, FILTER_SANITIZE_NUMBER_INT),
		"is_childcare_pre2011" 		=> filter_var($is_childcare_pre2011, FILTER_SANITIZE_STRING),
		"is_blind" 					=> filter_var($is_blind, FILTER_SANITIZE_STRING),
		"exclude_ni" 				=> filter_var($exclude_ni, FILTER_SANITIZE_STRING),
		"has_student_loan" 			=> filter_var($has_student_loan, FILTER_SANITIZE_STRING),
		"is_married" 				=> filter_var($is_married, FILTER_SANITIZE_STRING)
		);

	$taxcalc = new TaxCalculation($persona);
	
	$taxcalc->calculate_taxes();

	
	 
	 // echo '<pre>';
	 // var_dump($taxcalc->totalTaxableAmount);
	 // exit;
	



}

?>


<?php include('lib/header.php'); ?>
                        
<form method="post" action="./index.php">

<table id="calculator">
<tr>
<td colspan="2">Enter gross salary, choose options and click <span>Calculate</span></td>
</tr>
<tr>
<td colspan="2">Select year &nbsp;<select id="tax_year_is" name="tax_year_is">
				<option value="year2014_15" name="year2014_15" id="year2014_15" <?php if(isset($_POST["tax_year_is"]) && $_POST["tax_year_is"] === "year2014_15"){echo "selected";} ?>>2014/15</option>
				<option value="year2013_14" name="year2013_14" id="year2013_14" <?php if(isset($_POST["tax_year_is"]) && $_POST["tax_year_is"] === "year2013_14"){echo "selected";} elseif (!isset($_POST["tax_year_is"])) { echo "selected"; } ?>>2013/14</option>
				<option value="year2012_13" name="year2012_13" id="year2012_13" <?php if(isset($_POST["tax_year_is"]) && $_POST["tax_year_is"] === "year2012_13"){echo "selected";} ?>>2012/13</option>
				<option value="year2011_12" name="year2011_12" id="year2011_12" <?php if(isset($_POST["tax_year_is"]) && $_POST["tax_year_is"] === "year2011_12"){echo "selected";} ?>>2011/12</option>
				<option value="year2010_11" name="year2010_11" id="year2010_11" <?php if(isset($_POST["tax_year_is"]) && $_POST["tax_year_is"] === "year2010_11"){echo "selected";} ?>>2010/11</option>
				<option value="year2009_10" name="year2009_10" id="year2009_10" <?php if(isset($_POST["tax_year_is"]) && $_POST["tax_year_is"] === "year2009_10"){echo "selected";} ?>>2009/10</option>
				</select>
</td>
</tr>
<tr class="white calc">
	<td>Gross income every &nbsp;<select id="income_every_x" name="income_every_x">
		<option value="year" <?php if(isset($_POST["income_every_x"]) && $_POST["income_every_x"] === "year"){echo "selected";} ?>>Year</option>
		<option value="month" <?php if(isset($_POST["income_every_x"]) && $_POST["income_every_x"] === "month"){echo "selected";} ?>>Month</option>
		<option value="week" <?php if(isset($_POST["income_every_x"]) && $_POST["income_every_x"] === "week"){echo "selected";} ?>>Week</option>
		<option value="day" <?php if(isset($_POST["income_every_x"]) && $_POST["income_every_x"] === "day"){echo "selected";} ?>>Day</option>
	</select> &nbsp;is</td>
	<td><input type="number" id="gross_income_is" name="gross_income_is"  value="<?php if(isset($_POST["gross_income_is"])){ echo $_POST["gross_income_is"]; } ?>"</td>
</tr>


<tr class="white calc">
	<td>Other Allowances:  </td>
	<td><input type="number" id="other_allowance_is" name="other_allowance_is" value='<?php if(isset($_POST["other_allowance_is"])) { echo $_POST["other_allowance_is"]; } ?>'></td>
</tr>
<tr class="grey calc">
	<td>Tax code (if known):  </td>
	<td><input type="text" id="tax_code_is" name="tax_code_is" value='<?php if (isset($_POST["tax_code_is"])) { echo $_POST["tax_code_is"]; } ?>'></td>
</tr>
<tr class="grey calc">
	<td>Age:</td>
	<td>
		<select id="age_is" name="age_is">
		  	<option value="under_65"  <?php if(isset($_POST["age_is"]) && $_POST["age_is"] === "under_65"){echo "selected";} ?>>Under 65</option>
		  	<option value="65_74"  <?php if(isset($_POST["age_is"]) && $_POST["age_is"] === "65_74"){echo "selected";} ?>>65-74</option>
		  	<option value="over_75"  <?php if(isset($_POST["age_is"]) && $_POST["age_is"] === "over_75"){echo "selected";} ?>>Over 75</option>
		</select>
	</td>
</tr>
<tr class="white calc">
	<td>Pension Contribution:</td>
	<td>
		<input type="text" id="pension_contribution_is" name="pension_contribution_is" value="<?php if(isset($_POST["pension_contribution_is"])) { echo $_POST["pension_contribution_is"]; } ?>">
	</td>
</tr>
<tr>
	<td style="text-align: right;">every&nbsp;</td>
	<td><select id="pension_every_x" name="pension_every_x">
			<option value="year" <?php if(isset($_POST["pension_every_x"]) && $_POST["pension_every_x"] === "year"){echo "selected";} ?>>Year</option>
			<option value="month" <?php if(isset($_POST["pension_every_x"]) && $_POST["pension_every_x"] === "month"){echo "selected";} elseif (!isset($_POST["pension_every_x"])) { echo "selected"; } ?>>Month</option>
		</select>
	</td>
</tr>
<tr class="white calc">
	<td>Childcare vouchers:</td>
	<td>
		<input type="text" id="childcare_vouchers_are" name="childcare_vouchers_are" value="<?php if(isset($_POST["childcare_vouchers_are"])) { echo $_POST["childcare_vouchers_are"]; } ?>">
	</td>
</tr>
<tr>
<tr>
<td>Did you sign up before April 6 2011?</td>
<td><input type="checkbox" id="is_childcare_pre2011" name="is_childcare_pre2011" 
		<?php if(isset($_POST["is_childcare_pre2011"]) && $_POST["is_childcare_pre2011"] === "on"){echo "checked";} ?> ></td>
</tr>
	<td style="text-align: right;">every&nbsp;</td>
	<td><select id="vouchers_every_x" name="vouchers_every_x">
			<option value="week" <?php if(isset($_POST["vouchers_every_x"]) && $_POST["vouchers_every_x"] === "week"){echo "selected";} ?>>Week</option>
			<option value="month" <?php if(isset($_POST["vouchers_every_x"]) && $_POST["vouchers_every_x"] === "month"){echo "selected";} elseif (!isset($_POST["vouchers_every_x"])) { echo "selected"; } ?>>Month</option>
		</select>
	</td>
</tr>
<tr class="grey calc">
	<td colspan="2">
		<input type="checkbox" id="is_blind" name="is_blind" 
		<?php if(isset($_POST["is_blind"]) && $_POST["is_blind"] === "on"){echo "checked";} ?> >&nbsp;I am blind  
	</td>
</tr>
<tr class="white calc">

	<td colspan="2">
		<input type="checkbox" id="exclude_ni" name="exclude_ni"
		<?php if(isset($_POST["exclude_ni"]) && $_POST["exclude_ni"] === "on"){echo "checked";} ?> >&nbsp;I do not pay NI 
	</td>
</tr>
<tr class="grey calc">

	<td colspan="2">
		<input type="checkbox" id="has_student_loan" name="has_student_loan" 
		<?php if(isset($_POST["has_student_loan"]) && $_POST["has_student_loan"] === "on"){echo "checked";} ?> >&nbsp;I have a student loan   
	</td>
</tr>

<tr class="white calc">

	<td colspan="2">
		<input type="checkbox" id="is_married" name="is_married"
		<?php if(isset($_POST["is_married"]) && $_POST["is_married"] === "on"){echo "checked";} ?> >&nbsp;Married 
	</td>
</tr>

<tr>
	<td colspan="2">
		<input type="submit" id="calculate_taxes" name="calculate_taxes" value="Calculate" />
	</td>
</tr>

</table>


<!-- Results Table -->
<table id="results">

<tr style="text-transform:none;" class="results-header">
<th class="row-label"></th>
<th class="yr">Year</th>
<th class="mth">Month</th>
<th class="wk">Week</th>
<th class="day col-day">Day</th>
</tr>
<tr class="gross-row">
<td class="row-label">Gross Income</td>
<td class="yr"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showGrossIncome, 2); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showGrossIncome / 12, 2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showGrossIncome / 52, 2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showGrossIncome / 260, 2); } else { echo number_format(0, 2);} ?></td>
</tr>
<tr class="childcare-row">
<td class="row-label">Childcare Vouchers</td>
<td class="yr"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showChildCareVouchers, 2); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc)) { echo number_format(round($taxcalc->showChildCareVouchers / 12), 2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc)) { echo number_format(round($taxcalc->showChildCareVouchers / 53), 2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc)) { echo number_format(round($taxcalc->showChildCareVouchers / 260), 2); } else { echo number_format(0, 2);} ?></td>
</tr>
<tr class="tfa-row">
<td class="row-label">Tax free Allowance</td>
<td class="yr"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showTaxFreeAllowance, 2); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showTaxFreeAllowance / 12, 2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showTaxFreeAllowance / 52, 2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showTaxFreeAllowance / 260, 2); } else { echo number_format(0, 2);} ?></td>
</tr>
<tr class="pension-row">
<td class="row-label">Pension</td>
<td class="yr"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showEmployerPension,2 ); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showEmployerPension / 12, 2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showEmployerPension / 52, 2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showEmployerPension / 260, 2); } else { echo number_format(0, 2);} ?></td>
</tr>
<tr class="taxable-row">
<td class="row-label">Total taxable</td>
<td class="yr"><?php if (isset($taxcalc)) { echo number_format($taxcalc->totalTaxableAmount, 2); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc)) { echo number_format($taxcalc->totalTaxableAmount / 12, 2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc)) { echo number_format($taxcalc->totalTaxableAmount / 52, 2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc)) { echo number_format($taxcalc->totalTaxableAmount / 260, 2); } else { echo number_format(0, 2);} ?></td>
</tr>
<!-- </table>

<table border="1"> -->

<tr class="taxbands-row">
<td class="row-label"><a class="tax-expand" href="javascript:"><i class="fa fa-plus-square"></i></a>&nbsp;Tax Due</td>
<td class="yr"><?php if (isset($taxcalc)) { echo number_format($taxcalc->totalTaxDue, 2); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc)) { echo number_format($taxcalc->totalTaxDue / 12, 2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc)) { echo number_format($taxcalc->totalTaxDue / 52, 2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc)) { echo number_format($taxcalc->totalTaxDue / 260, 2); } else { echo number_format(0, 2);} ?></td>
</tr>
<!-- </table> -->


<tr  id="taxband1-row" class="tax-bands">
<td class="row-label">&nbsp;&nbsp;&nbsp;&nbsp;<em><?php if (isset($taxcalc)) { echo $taxcalc->taxBand["additional"]["rate"]; } else { echo 50;} ?>% tax rate</em>
</td><td class="yr"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["additional"], 2); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["additional"] / 12, 2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["additional"] / 52, 2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["additional"] / 260, 2); } else { echo number_format(0, 2);} ?></td>
</tr>
<tr  id="taxband2-row" class="tax-bands">
<td class="row-label">&nbsp;&nbsp;&nbsp;&nbsp;<em>40% tax rate</em>
</td><td class="yr"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["higher"], 2); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["higher"] / 12, 2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["higher"] / 52, 2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["higher"] / 260, 2); } else { echo number_format(0, 2);} ?></td>
</tr >
<tr id="taxband3-row" class="tax-bands">
<td class="row-label">&nbsp;&nbsp;&nbsp;&nbsp;<em>20% tax rate</em>
</td><td class="yr"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["basic"], 2); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["basic"] / 12, 2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["basic"] / 52, 2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc)) { echo number_format($taxcalc->deduction["basic"] / 260,2); } else { echo number_format(0, 2);} ?></td>
</tr>



<!-- <table border="1"> -->



<tr class="ni-row">
<td class="row-label">National Insurance</td>
<td class="yr"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showNIContribution, 2); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showNIContribution / 12, 2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showNIContribution / 52, 2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showNIContribution / 260,2); } else { echo number_format(0, 2);} ?></td>
</tr>
<tr class="student-row">
<td class="row-label">Student Loan</td>
<td class="yr"><?php if (isset($taxcalc) && isset($taxcalc->showStudentLoanAmount)) { echo number_format($taxcalc->showStudentLoanAmount,2); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc) && isset($taxcalc->showStudentLoanAmount)) { echo number_format(floor($taxcalc->showStudentLoanAmount / 12),2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc) && isset($taxcalc->showStudentLoanAmount)) { echo number_format(floor($taxcalc->showStudentLoanAmount / 52),2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc) && isset($taxcalc->showStudentLoanAmount)) { echo number_format(floor($taxcalc->showStudentLoanAmount / 260),2); } else { echo number_format(0, 2);} ?></td>
</tr>
<tr class="total-deductions-row">
<td class="row-label">Total Deductions</td>
<td class="yr"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showTotalDeduction,2); } else { echo number_format(0, 2);} ?></td>
<td class="mth"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showTotalDeduction / 12,2); } else { echo number_format(0, 2);} ?></td>
<td class="wk"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showTotalDeduction / 52,2); } else { echo number_format(0, 2);} ?></td>
<td class="day"><?php if (isset($taxcalc)) { echo number_format($taxcalc->showTotalDeduction / 260,2); } else { echo number_format(0, 2);} ?></td>
</tr>
<tr class="net-row">
<td class="row-label">Net Income</td>
<td class="yr"><span><?php if (isset($taxcalc)) { echo number_format($taxcalc->showNetIncome,2); } else { echo number_format(0, 2);} ?></span></td>
<td class="mth"><span><?php if (isset($taxcalc)) { echo number_format($taxcalc->showNetIncome / 12,2); } else { echo number_format(0, 2);} ?></span></td>
<td class="wk"><span><?php if (isset($taxcalc)) { echo number_format($taxcalc->showNetIncome / 52,2); } else { echo number_format(0, 2);} ?></span></td>
<td class="day"><span><?php if (isset($taxcalc)) { echo number_format($taxcalc->showNetIncome / 260,2); } else { echo number_format(0, 2);} ?></span></td>
</tr>
<!-- </table> -->
	
	
</table>
<!-- End of Results Table -->

<?php

// tabular data ends here
?>
<!-- Ends here -->
</div>
</form>
</div>
</div>

<?php include('lib/footer.php'); ?>