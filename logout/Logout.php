<?php
session_start();
session_unset();
session_destroy();
echo "<script>alert(' Berhasil Log-Out '); window.location.href = '../login/'</script>";
?>