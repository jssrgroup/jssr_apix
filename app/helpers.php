<?php

if (!function_exists('jencrypt')) {
  function jencrypt($string)
  {
    $key = env('JSSR_SECRET', 'jssrsecret');
    $result = '';
    for ($i = 0; $i < strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key)) - 1, 1);
      $char = chr(ord($char) + ord($keychar) + ((2910 - 1979) % 32));
      $result .= $char;
    }
    return base64_encode($result);
  }
}

if (!function_exists('jdecrypt')) {
  function jdecrypt($string)
  {
    $key = env('JSSR_SECRET', 'jssrsecret');
    $result = '';
    $string = base64_decode($string);
    for ($i = 0; $i < strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key)) - 1, 1);
      $char = chr(ord($char) - ord($keychar) - ((2910 - 1979) % 32));
      $result .= $char;
    }
    return $result;
  }
}
