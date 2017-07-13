<form class="login" onsubmit="WhiteLabel_form_login(this); return false;" data-validate="<?php echo plugin_dir_url( __FILE__ ); ?>user/login.php">
    <input type="text" name="user" value="" placeholder="User" autocomplete="off" />
    <input type="password" name="pass" value="" placeholder="Password" autocomplete="off" />
    <input type="submit" value="INGRESAR"/>
    <div class="message"></div>
</form>
