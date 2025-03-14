<?php
session_start();
session_destroy();
header("Location: Admin.php?logout_message=You have successfully logged out.");
exit();