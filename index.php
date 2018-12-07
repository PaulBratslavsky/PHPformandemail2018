<?php 
    require './includes/functions.php';

    $errors = [];
    $missing = [];

    $name = '';
    $email = '';

    // Check if for was submitted with required fields
    if ( isset($_POST['send']) ) {
        // This is the data we should have
        $expected = ['name','email','comments'];
        $required = ['name','email','comments'];
        
        // Required date to send email
        $to = 'Paul Bratslavsky <paul.bratslavsky@gmail.com>';
        $subject = 'Feedback from an online form';
        $headers = [];
        $headers[] = 'From: webmasters@example.com';
        $headers[] = 'Cc: another@example.com';
        $headers[] = 'Content-type: text/plain; charset: utf-8';
        $authorized = null;

        require './includes/process_mail.php';

        if ( $mailSent ) {
            header( 'Location: thankyou.php' );
            exit; 
        } 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My PHP Form</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">My PHP Form</a>
                </div>
            </div><!-- /.container-fluid -->
        </nav>


        <div class="jumbotron">
            <section id="form">
                <div class=container>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-container>">
                                <h3>Step One: Contact Information</h3>

                                <?php if ( $_POST && ( $suspect || isset( $errors['mailfail'] ) ) ) : ?>

                                    <div class="alert alert-danger" role="alert">
                                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                        <span class="sr-only">Error:</span>
                                        Sorry, your email couldn't be sent!
                                    </div>

                                <?php endif; ?>
                                
                                <?php if ( $errors || $missing ) : ?>
                                
                                    <div class="alert alert-danger" role="alert">
                                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                        <span class="sr-only">Error:</span>
                                        Please fix the following error(s)!
                                    </div>

                                <?php endif; ?>

                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" >
                                     
                                    <div class="form-group">
                                        
                                        <label for="name">Name: </label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name here..." <?php showValue( $name, $errors, $missing ); ?>>              

                                        <?php if ( $missing && in_array( 'name', $missing ) ) : ?>
                                            <br>
                                            <div class="alert alert-warning" role="alert">
                                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                <span class="sr-only">Error:</span>
                                                Please add your name above!
                                            </div>

                                        <?php endif; ?>

                                    </div>
                                    
                                    <div class="form-group">

                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email here..." <?php showValue( $email, $errors, $missing ); ?>>
                                    
                                        <?php if ( $missing && in_array( 'email', $missing ) ) : ?>
                                            <br>
                                            <div class="alert alert-warning" role="alert">
                                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                <span class="sr-only">Error:</span>
                                                Please add your email above!
                                            </div>
                                            <?php elseif ( isset( $errors['email']) ) : ?>
                                                <div class="alert alert-warning" role="alert">
                                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                    <span class="sr-only">Error:</span>
                                                   Invalid Email!
                                                </div>
                                        <?php endif; ?>

                                    </div>

                                    <div class="form-group">

                                        <label for="comments">Comments: </label>
                                        <textarea class="form-control" id="comments" rows="3" name="comments" ><?php 
                                             if ( $errors || $missing ) {
                                                echo htmlentities( $comments );
                                            }
                                        ?></textarea>

                                        <?php if ( $missing && in_array( 'comments', $missing ) ) : ?>
                                            <br>
                                            <div class="alert alert-warning" role="alert">
                                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                <span class="sr-only">Error:</span>
                                                Please add your comment above!
                                            </div>

                                        <?php endif; ?>

                                    </div>

                                    <button type="submit" class="btn btn-primary" name="send">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


     
        <pre>
            <?php
            if ( $_GET ) {
                
                echo 'Content of GET array:<br>';
                print_r($_GET);

            } elseif ( $_POST ) {

                echo 'Content of POST array:<br>';
                print_r($_POST);

            } 
        
            ?>
        </pre>

        <br>

        <pre>
            <?php
            if ( $_POST && $mailSent ) {
                echo "Message: \n\n";
                echo htmlentities( $message);

                echo "Headers: \n\n";
                echo htmlentities( $headers );
            }    
            ?>
        </pre>
        

        </div>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
