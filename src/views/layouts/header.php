<?php

use Framework\Session\Session;

Session::start();

?>

<header>
    <div class="navigation">
        <img src="logo/logo.png" alt="logo">
        <div class="navbar">
            <ul>
                <li><a href="/login">Sign in</a></li>
                <li><a href="/auth">Sign up</a></li>
                <li><a href="/home">Home</a></li>
                <li><a href="/">Market</a></li>
                <li><a href="/">Contact</a></li>
                <li><a href="/">About</a></li>
            </ul>
        </div>
    </div>
</header>
