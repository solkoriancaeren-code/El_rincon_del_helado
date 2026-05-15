<?php
session_start();
session_destroy();
header("Location: /helado/views/auth/login.php");
exit();
