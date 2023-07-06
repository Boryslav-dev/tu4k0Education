<link rel="stylesheet" type="text/css" href="/../css/styles.css">
<main>
    <div class="authorization-form">
        <img src="/../logo/login.png" alt="login">
        <form action="/auth" method="post">
            <h1 class="authorization-header">Authorization</h1>
            <h2 style="text-align: center; color: white; margin-top: 20px">Fill this form to register</h2>
            <p><b>Name</b></p>
            <input class="authorization-data" type="text" name="name">
            <p><b>Login</b></p>
            <input class="authorization-data" type="text" name="login">
            <p><b>E-mail</b></p>
            <input class="authorization-data" type="text" name="email">
            <p><b>Password</b></p>
            <input class="authorization-data" type="text" name="password">
            <p><b>Repeat password</b></p>
            <input class="authorization-data" type="text" name="passwordRepeat">
            <button class="login-button" name="submit" type="submit">Sign in</button>
        </form>
    </div>
</main>
