<?php

function setusercookie($id) {
    setcookie('userid', $id, time()+1440, "/");
}

function otpdone($id) {
    setcookie('otpdone', $id, time()+(1440 * 365), "/");
}

function setusercookieexpire($id) {
    setcookie('userid', $id, time()-1440, "/");
}

function createRandomPassword() { 

    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 

    while ($i <= 14) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 

    return $pass; 

} 

function createOTP() { 

    $pass = random_int(100000, 999999);

    return $pass;

} 

?>