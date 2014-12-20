<?php session_start();
/**
 *=-----------------------------------------------------------=
 * logout.php
 *=-----------------------------------------------------------=
 * 
 */
//ob_start();
function nuke_session()
{
    session_destroy();
    setcookie(session_name(), '', time() - 1440);
    $_SESSION[] = array();
}
/**
 * Niszczy dane sesji
 */
nuke_session();

header("Location: ../index.php");
exit(0);
?>
