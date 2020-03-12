 <div class="card card-container">

    <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />

    <p id="profile-name" class="profile-name-card"></p>

    <?php $this->check_error(); ?>

    <form class="form-signin" action="post.php" method="post">
        <span id="reauth-email" class="reauth-email"></span>
        <input type="text" id="inputEmail" class="form-control" placeholder="Email address"  name="user_email">
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required name="user_pass">
        <div id="remember" class="checkbox">
            <label>
                <input type="checkbox" name="rememberme" value="remember"> ログイン情報を保持する
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">ログイン</button>
        <input type="hidden" name="guild_press_action" value="user_login">

        <?php $this->login_nonce(); ?>

    </form><!-- /form -->
</div><!-- /card-container -->



