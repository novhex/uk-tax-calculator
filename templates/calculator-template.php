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
	<td><input type="text" id="gross_income_is" name="gross_income_is"  value="<?php if(isset($_POST["gross_income_is"])){ echo $_POST["gross_income_is"]; } ?>"</td>
</tr>


<tr class="white calc">
	<td>Other Allowances:  </td>
	<td><input type="text" id="other_allowance_is" name="other_allowance_is" value='<?php if(isset($_POST["other_allowance_is"])) { echo $_POST["other_allowance_is"]; } ?>'></td>
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

</form>