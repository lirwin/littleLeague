<?php 

    session_start();
    
    // Set some important CAPTCHA constants
    define('CAPTCHA_NUMCHARS', 6);
    // number of characters in pass-phrase
    define('CAPTCHA_WIDTH', 120);
    // width of image
    define('CAPTCHA_HEIGHT', 30);
    // height of image
    
    // Generate the random pass-phrase
    $alphanum = "ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz123456789";
    $pass_phrase = substr(str_shuffle($alphanum), 0, CAPTCHA_NUMCHARS);
    
    // Store the encrypted pass-phrase in a session variable
    $_SESSION['captcha'] = sha1($pass_phrase);
    
    // Create the image    $img = imagecreatetruecolor(CAPTCHA_WIDTH, CAPTCHA_HEIGHT);       
    // Create random r, g, b values for the background
    $r = mt_rand(230, 255);
    $g = mt_rand(230, 255);
    $b = mt_rand(230, 255);
  
    // Create color handles
    $bg_color = imagecolorallocate($img, $r, $g, $b);
    $text_color = imagecolorallocate($img, $r-150, $g-150, $b-150);
    $graphic_color = imagecolorallocate($img, 64, 64, 64);
    
    // Fill the background
    imagefilledrectangle($img, 0, 0, CAPTCHA_WIDTH, CAPTCHA_HEIGHT, $bg_color);
    
    // Draw some random lines
    for ($i = 0; $i < 2; $i++) {
        imageline($img, 0, rand() % CAPTCHA_HEIGHT, CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color);
    }
    
    // Sprinkle in some random dots
    for ($i = 0; $i < 20; $i++) {
        imagesetpixel($img, rand() % CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color);
    }
       
    // Add characters in random orientation
    for($i = 1; $i <= CAPTCHA_NUMCHARS; $i++){
       $counter = mt_rand(0, 1);
       if ($counter == 0){
          $angle = mt_rand(0, 30);
       }
       if ($counter == 1){
          $angle = mt_rand(330, 360);
       }
       imagettftext($img, mt_rand(14, 18), $angle, ($i * 18)-8, mt_rand(20, 25), $text_color, 'Courier New Bold.ttf', substr($pass_phrase, ($i - 1), 1));
    }

    // Blur the image
    $gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
    imageconvolution($img, $gaussian, 16, 0);
    
    // Prevent caching
    header('Expires: Tue, 08 Oct 1991 00:00:00 GMT');
    header('Cache-Control: no-cache, must-revalidate');

    // Output the image as a PNG using a header    header("Content-type: image/png");    imagepng($img);
    
    // Clean up
    imagedestroy($img);
?>
