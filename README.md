UK Tax & Salary Calculator
=================

A complete UK tax and salary calculator built in PHP which allows you to calculate net salary, national insurance contributions, pension contributions, childcare vouchers, student loan repayments, and use your own tax code for more accurate results.

<a href="https://salarycalculatorpro.com/">Salary calculator in action</a>

TO DO
=================
* Married allowance is calculated, but isn't deducted from total income or displayed on the table
* Add Plan 2 student loan repayment
* Add hourly rate and number of days per week option
* K tax code doesn't work correctly if "Other allowance" is set
  * I'm not entirely sure there is even a need the "other allowance field. It would be better to have pre- and post-tax deduction options instead.
* Add salary sacrifice, personal and contracted out pension options
* Add W1 and M1 tax codes i.e. 1000L W1 (low priority)
* Add option for monthly/4-weekly/2-weekly/weekly bonus to compare against regular month
* Add overtime option
* Move all data from arrays into MySQL database
* Add ability to store user calculations for retrieval, email and data manipulation
* Refactor code into a simple API
