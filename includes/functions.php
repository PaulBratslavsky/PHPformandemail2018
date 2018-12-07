<?php 
    function showValue( $value, $errors, $missing ) {
        if ( $errors || $missing ) {
            echo 'value="' . htmlentities( $value ) . '"';
        }
    }
?>