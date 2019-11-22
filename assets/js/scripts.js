function checkPasswordStrength( $pass1, $pass2, $strengthResult, $submitButton, blacklistArray ) {
    var pass1 = $pass1.val();
    var pass2 = $pass2.val();
 
    // Reset the form & meter
    $submitButton.attr( 'disabled', 'disabled' );
        $strengthResult.next().removeClass( 'short bad good strong' );
 
    // Extend our blacklist array with those from the inputs & site data
    blacklistArray = blacklistArray.concat( wp.passwordStrength.userInputBlacklist() )
 
    // Get the password strength
    var strength = wp.passwordStrength.meter( pass1, blacklistArray, pass2 );
    console.log(strength);
    // Add the strength meter results
    switch ( strength ) {
 
        case 2:
            $strengthResult.html( pwsL10n.bad );
            $strengthResult.next().addClass( 'bad' );
            break;
 
        case 3:
            $strengthResult.html( pwsL10n.good );
            $strengthResult.next().addClass( 'good' );
            break;
 
        case 4:
            $strengthResult.html( pwsL10n.strong );
            $strengthResult.next().addClass( 'strong' );
            break;
 
        case 5:
            $strengthResult.html( pwsL10n.mismatch );
            $strengthResult.next().addClass( 'short' );
            break;
 
        default:
            $strengthResult.html( pwsL10n.short );
            $strengthResult.next().addClass( 'short' );
 
    }
 
    // The meter function returns a result even if pass2 is empty,
    // enable only the submit button if the password is strong and
    // both passwords are filled up
    if ( (4 === strength || 3 === strength) && '' !== pass2.trim() ) {
        $submitButton.removeAttr( 'disabled' );
    }
 
    return strength;
}
 
jQuery( document ).ready( function( $ ) {
    // Binding to trigger checkPasswordStrength
    $( 'body' ).on( 'keyup', 'input[name=password], input[name=repassword]',
        function( event ) {
            checkPasswordStrength(
                $('input[name=password]'),         // First password field
                $('input[name=repassword]'), // Second password field
                $('#password-strength'),           // Strength meter
                $('input[type=submit]'),           // Submit button
                ['black', 'listed', 'word']        // Blacklisted words
            );
        }
    );
});