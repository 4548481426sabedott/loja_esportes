<?php
session_start();
unset($_SESSION['admin_id']);
unset($_SESSION['admin_nome']);
unset($_SESSION['admin_email']);
session_destroy();
header('Location: admin_login.php');
exit;
?>
