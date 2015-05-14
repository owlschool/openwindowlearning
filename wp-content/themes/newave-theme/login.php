<?php
/*
Template name: Login Template
*/

get_header();

get_template_part('sections/menu_section');

$section_style = 'style="background-color:#FFF;"';

$container_type = 'normal';
?>

<!-- Login -->
<section class="content" <?php echo $section_style; ?> >
  <!-- Container -->
  <div class="container">
    <div style="width:45%;float:left;margin:0 0 0 0;border:0 0 0 0;">
      <div class="section-title" style="width:100%;">
        <h1>Login</h1>
        <span class="border"></span>
      </div>				
      <form name="loginform" id="loginform" action="<?php echo get_option('home'); ?>/wp-login.php" method="post">
        <p>
          <input name="log" type="text" id="user_login" size="20" onfocus="if (this.value == 'Username') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Username'; }" value="Username">
        </p>
        <p>
          <input name="pwd" type="password" id="user_pass" size="20" onfocus="if (this.value == 'Password') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Password'; }" value="Password">
        </p>
        <input type="hidden" name="redirect_to" value="<?php echo get_option('home'); ?>" />
        <p class="forgetmenot">
          <label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> Remember Me</label>
          <br>
          <input type="submit" name="wp-submit" id="wp-submit" class="send_message" value="Log In" />
        </p>
      </form>
      <p>
        <a class="newave-button medium-button grey" href="<?php echo get_option('home'); ?>/wp-login.php?action=lostpassword" title="Password Lost and Found">Lost your password?</a>
      </p>
    </div>
    <div style="width:45%;float:right;margin:0 0 0 0;border:0 0 0 0;">
      <div class="section-title" style="width:100%;">
        <h1>Register</h1>
        <span class="border"></span>
      </div>				
      <form name="loginform" id="loginform" action="<?php echo get_option('home'); ?>/wp-login.php?action=register" method="post">
        <p>
          <input name="log" type="text" id="user_login" size="20" onfocus="if (this.value == 'Username') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Username'; }" value="Username">
        </p>
        <p>
          <input name="email" type="text" id="user_pass" size="20" onfocus="if (this.value == 'E-Mail Address') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'E-Mail Address'; }" value="E-Mail Address">
        </p>
        <input type="hidden" name="redirect_to" value="<?php echo get_option('home'); ?>" />
        <input type="hidden" name="testcookie" value="1" />
        <p class="forgetmenot">
          <input type="submit" name="wp-submit" id="wp-submit" class="send_message" value="Register" />
        </p>
      </form>
    </div>
  </div>
  <!-- Container -->  
</section>	

<?php
get_footer();

?>