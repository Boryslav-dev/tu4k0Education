<?php

use Framework\Session\Session;

session_start();

?>

<link rel="stylesheet" type="text/css" href="/../css/styles.css">
<main>
    <div class="login-form">
        <img src="/../logo/login.png" alt="login">
        <form action="/home" method="post">
            <h1 class="login-header">Login form</h1>
            <p>Login</p>
            <input class="login-data" type="text" name="login">
            <p>Password</p>
            <input class="login-data" type="text" name="password">
            <button class="login-button" type="submit">Log in</button>
        </form>
    </div>
</main>
