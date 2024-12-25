<?php

/**
 * @param string $message Pesan yang ingin ditampilkan dalam alert.
 * @param string $redirectUrl URL tempat pengguna akan diarahkan setelah menutup alert.
 */
function showAlertAndRedirect($message = "", $redirectUrl = "")
{
  $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
  echo "<script type='text/javascript'>
            alert('$message');
            window.location.href = '$redirectUrl';
          </script>";
  exit();
}
function showAlert($message = "")
{
  $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
  echo "<script type='text/javascript'>
            alert('$message');
          </script>";
}
