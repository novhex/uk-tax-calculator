$( '#childcare_vouchers_are' ).focus(function() {
  $( '#voucher_extra').show('fast');
});

$( '#childcare_vouchers_are' ).blur(function() {
  if ( $( '#childcare_vouchers_are' ).val().length == 0 ) {
  $( '#voucher_extra').hide('fast');
  } else {
    return;
  }
});

$( '#pension-expand' ).click(function() {
  if ( $( '.hmrc-pension-row' ).is(":visible") ) {
    $( '.hmrc-pension-row' ).hide('fast');
    $( '#pension-expand .glyphicon' ).removeClass( 'open' );
  } else {
    $( '.hmrc-pension-row' ).show('fast');
    $( '#pension-expand .glyphicon' ).addClass( 'open' );
  }
});

$( '#tax-expand' ).click(function() {
  if ( $( '.tax-bands' ).is(":visible") ) {
    $( '.tax-bands' ).hide('fast');
    $( '#tax-expand .glyphicon' ).removeClass( 'open' );
  } else {
    $( '.tax-bands' ).show('fast');
    $( '#tax-expand .glyphicon' ).addClass( 'open' );
  }
});