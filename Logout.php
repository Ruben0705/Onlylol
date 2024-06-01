<?php
session_start();
session_unset();
session_destroy();

// Eliminar la cookie estableciendo su tiempo de expiración en el pasado
setcookie("username", "", time() - 3600, "/");

header("Location: Index.php");
exit();
?>
