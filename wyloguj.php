<!-- Najprostrza metoda czyli usuwa sesje wszystko odpina -->

<?php
session_start();

// Usuń dane sesji
session_unset();
session_destroy();
setcookie('username', '', time() - 3600, '/');
// Przekieruj na stronę główną
header("Location: Index.php");
exit();
?>