<?php
require_once(UIKAR_REGISTER_BUILDER_DIR . 'includes/links.php');
?>
<main class="main-container clearfix">
    <div class="centerize grid_12 clearfix">
        <div class="grid_5 centerize clearfix">
            <div class="login-box clearfix">
                <?php 
                $userID = $_REQUEST['usrkey'];
                $nonce = $_REQUEST['_wpnonce'];
                $userkey = $_REQUEST['key'];
                $role = 'Customer';
                if ( ! wp_verify_nonce( $nonce, 'register-nonce' ) ) {?>
                    <div class="alert alert-danger">
                        <p><?php _e('Opps.. For security we cant activate your account, please contact the administrator', 'cosmetic');?></p>
                    </div>
                <?php 
                } else {
                     if($userID)
                        {
                            $hasactive = get_user_meta($userID, 'has_to_be_activated', true);
                            if(strcasecmp($hasactive, $userkey) == 0)
                            {

                                $user_id = wp_update_user( array( 'ID' => $userID, 'role' => 'customer' ) );
                                if ( is_wp_error( $user_id ) ) {?>
                                    <div class="alert alert-danger">
                                        <p><?php _e('Opps.. Somthings went wrong. we can not activate your account. Please contact the adminstrator', 'cosmetic');?></p>
                                    </div>
                                <?php } else {?>
                                    <div class="alert alert-success" role="alert">
                                        <strong><?php _e('Welcome', 'cosmetic'); ?>..! </strong><?php _e('Your Account is activated. please Login'); ?>
                                    </div>
                                <?php 
                                }
                                update_user_meta( $userID, 'has_to_be_activated', '', $userkey );
                            }
                            else
                            {?>
                                <div class="alert alert-danger">
                                    <p><?php _e('Opps.. Somthings went wrong. we can not activate your account. Please contact the adminstrator', 'cosmetic');?></p>
                                </div>
                            <?php 
                            }
                        }
                        else{?>
                            <div class="alert alert-danger">
                                <p><?php _e('Opps.. Somthings went wrong. we can not activate your account. Please contact the adminstrator', 'cosmetic');?></p>
                            </div>
                        <?php 
                        }
                } 
                $loginMessage = $_GET['login'];
                if($loginMessage == 'failed')
                {?>
                    <div class="alert alert-danger">
                        <p><?php _e('Wrong username or password', 'cosmetic');?></p>
                    </div>
                <?php }
                else if($loginMessage == 'empty'){?>
                    <div class="alert alert-danger">
                        <p><?php _e('One of the fields are empty.', 'cosmetic');?></p>
                    </div>
                <?php } 
                $args = array(
                    'echo'           => true,
                    'remember'       => true,
                    'redirect'       => home_url(),
                    'form_id'        => 'loginform',
                    'id_username'    => 'user_login',
                    'id_password'    => 'user_pass',
                    'id_remember'    => 'rememberme',
                    'id_submit'      => 'wp-submit',
                    'label_username' => __( 'Username or Email Address' ),
                    'label_password' => __( 'Password' ),
                    'label_remember' => __( 'Remember Me' ),
                    'label_log_in'   => __( 'Log In' ),
                    'value_username' => '',
                    'value_remember' => false
                );
                wp_login_form($args); ?>
                <a href="<?php echo wp_lostpassword_url(); ?>" class="lost" title="Lost Password"><?php _e('Lost Your Password?', 'cosmetic');?></a>
            </div><!-- end of cart-box -->
        </div><!-- end of left-column -->
    </div><!-- end of centerize -->
</main>