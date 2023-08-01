<?php

use Framework\Session\Session;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>
        Tu4k0 Marketplace
    </title>
</head>
<body>
<?php include "header.php" ?>
<h1 style="text-align: center; color: #e0fbfc;">
    <?php
    if (Session::get('User')['role_id'] === 1) :
        echo 'Client content';
    elseif (Session::get('Admin') === 2) :
        echo 'Admin content';
    endif;
    ?></h1>
<?php
include "footer.php" ?>
</body>
</html>
