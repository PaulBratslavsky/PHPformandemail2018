<?php 
    $mailSent = false;
    // CHECKS FOR HEAEDER INJECTIONS
    $suspect = false;
    // USE REG EXPRESIONS TO CHECK INPUT FIELD
    $pattern = '/Content-type:|Bcc:|Cc:/i';


    // FUNCTION WE CREATED TO CHECK VALUE TO PATTERN
    function isSuspect( $value, $pattern, &$suspect ) {
        
        if ( is_array( $value ) ) {
            foreach( $value as $item ) {
                isSuspect( $item, $pattern, $suspect );
            } 
        } else {
                
            if ( preg_match( $pattern, $value ) ) {
                $suspect = true;
            }
        }
    }

    // CALLING ABOVE FUNCTIOM
    isSuspect( $_POST, $pattern, $suspect );

    if ( !$suspect ) :

        // CHECK IF FIELDS ARE EMPTY OR NOT
        foreach ( $_POST as $key => $value ) {
            // Check if array and reasigns if not trims all the white blank space
            $value = is_array( $value ) ? $value : trim( $value );
            
            if ( empty( $value ) && in_array( $key, $required ) ) {
                $missing[] = $key;
                $$key = '';
            } elseif ( in_array( $key, $expected ) ) {
                $$key = $value;
            }
        }

        // Validate users email
        if ( !$missing && !empty( $email ) ) :
            $validEmail = filter_input( INPUT_POST, 'email', FILTER_VALIDATE_EMAIL );

            if ( $validEmail ) {
                $headers[] = "Reply-to: {$validEmail}";
            } else {
                $errors['email'] = true; 
            }

        endif;
        
        // If no errors create headers and message body
        if ( !$errors && !$missing ) :
            $headers = implode( "\r\n", $headers );

            // Initialize message
            $message = '';

            foreach ( $expected as $field ) :
                
                if ( isset( $$field )  && !empty( $$field ) ) {
                    $tempVal = $$field;
                } else {
                    $tempVal = 'Not selected!';
                }
                
                // If an array change to comma seperated string
                if ( is_array( $tempVal ) ) {
                    $tempVal = implode( ', ', $tempVal );
                }

                // Replace underscores in the field names with spaces
                $field =str_replace( '_', ' ', $field );
                
                $message .= ucfirst( $field ) . ": {$tempVal}\r\n\r\n"; 

                
            endforeach;

            $message = wordwrap( $message, 70 );
            $mailSent = mail( $to, $subject, $message, $headers, $authorized );

            if ( !$mailSent ) {
                $errors['mailfail'] = true;  
            }
                
            print_r( $message );
            print_r( $mailSent );

        endif;    

    endif;

?>