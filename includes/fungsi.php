<?php

function encrypt_decrypt($action, $string)
{
    /* =================================================
     * ENCRYPTION-DECRYPTION
     * =================================================
     * ENCRYPTION: encrypt_decrypt('encrypt', $string);
     * DECRYPTION: encrypt_decrypt('decrypt', $string) ;
     */
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'WS-SERVICE-KEY';
    $secret_iv = 'WS-SERVICE-VALUE';
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else {
        if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
    }
    return $output;
}

function strToBytes($str) {
    return array_values(unpack('C*', $str));
}

function bytesToStr($bytes) {
    return pack('C*', ...$bytes);
}

function bytesToBase64($bytes) {
    return base64_encode(bytesToStr($bytes));
}

function base64ToBytes($b64) {
    return strToBytes(base64_decode($b64));
}

// ===== deterministic seed from passphrase (djb2 variant) =====
function seedFromPassphrase($pass) {
    $bytes = strToBytes($pass);
    $h = 5381;
    foreach ($bytes as $b) {
        $h = (($h * 33) ^ $b) & 0xFFFFFFFF; // keep uint32
    }
    return $h & 0xFFFFFFFF;
}

// ===== xorshift32 PRNG =====
function make_xorshift32($seed) {
    $x = $seed & 0xFFFFFFFF;
    return function() use (&$x) {
        $x ^= ($x << 13) & 0xFFFFFFFF;
        $x ^= ($x >> 17) & 0xFFFFFFFF;
        $x ^= ($x << 5) & 0xFFFFFFFF;
        $x &= 0xFFFFFFFF;
        return $x & 0xFF;
    };
}

// ===== hybrid encrypt / decrypt =====
function hybridEncrypt($plaintext, $passphrase) {
    $seed = seedFromPassphrase($passphrase);
    $prng = make_xorshift32($seed);

    $ivLen = 12;
    $iv = [];
    for ($i=0; $i<$ivLen; $i++) {
        $iv[] = $prng();
    }

    $plainBytes = strToBytes($plaintext);
    $out = $iv; // start with IV
    $shift = $seed % 256;

    for ($i=0; $i<count($plainBytes); $i++) {
        $ks = $prng();
        $xored = $plainBytes[$i] ^ $ks;
        $mixed = ($xored + $iv[$i % $ivLen] + $shift) & 0xFF;
        $out[] = $mixed;
    }

    return bytesToBase64($out);
}

function hybridDecrypt($b64cipher, $passphrase) {
    $all = base64ToBytes($b64cipher);
    $ivLen = 12;
    if (count($all) < $ivLen) {
        throw new Exception("Ciphertext too short");
    }
    $iv = array_slice($all, 0, $ivLen);
    $cipherBytes = array_slice($all, $ivLen);

    $seed = seedFromPassphrase($passphrase);
    $prng = make_xorshift32($seed);

    // advance ivLen steps (discard)
    for ($i=0; $i<$ivLen; $i++) {
        $prng();
    }

    $shift = $seed % 256;
    $plain = [];

    for ($i=0; $i<count($cipherBytes); $i++) {
        $ks = $prng();
        $mixed = $cipherBytes[$i];
        $xored = ($mixed - $iv[$i % $ivLen] - $shift) & 0xFF;
        $orig = ($xored ^ $ks) & 0xFF;
        $plain[] = $orig;
    }

    return bytesToStr($plain);
}

// ===== JSON wrapper =====
function encryptJSON($obj, $passphrase) {
    $jsonStr = json_encode($obj, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    return hybridEncrypt($jsonStr, $passphrase);
}

function decryptJSON($ciphertext, $passphrase) {
    $jsonStr = hybridDecrypt($ciphertext, $passphrase);
    return json_decode($jsonStr, true);
}


?>