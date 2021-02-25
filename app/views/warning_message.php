<?php
if (!empty($_SESSION['warning'])) {
    echo $_SESSION['warning'];
    unset($_SESSION['warning']);
}
