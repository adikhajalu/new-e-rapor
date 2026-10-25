// ===== helpers =====
function strToBytes(str) {
    return new TextEncoder().encode(str);
}
function bytesToStr(bytes) {
    return new TextDecoder().decode(new Uint8Array(bytes));
}
function bytesToBase64(bytes) {
    let binary = '';
    const len = bytes.length;
    for (let i = 0; i < len; i++) binary += String.fromCharCode(bytes[i]);
    return btoa(binary);
}
function base64ToBytes(b64) {
    const binary = atob(b64);
    const len = binary.length;
    const out = new Uint8Array(len);
    for (let i = 0; i < len; i++) out[i] = binary.charCodeAt(i);
    return out;
}

// Simple deterministic hash from passphrase -> uint32 (djb2 variant)
function seedFromPassphrase(pass) {
    const bytes = strToBytes(pass);
    let h = 5381 >>> 0;
    for (let i = 0; i < bytes.length; i++) {
    h = ((h * 33) ^ bytes[i]) >>> 0;
    }
    return h >>> 0;
}

// xorshift32 PRNG (seedable)
function xorshift32(seed) {
    let x = seed >>> 0;
    return function() {
    // produce 32-bit then return a byte
    x ^= (x << 13) >>> 0;
    x ^= (x >>> 17) >>> 0;
    x ^= (x << 5) >>> 0;
    // return low 8 bits
    return x & 0xFF;
    };
}

// ===== hybrid encrypt / decrypt =====
function hybridEncrypt(plaintext, passphrase) {
    const seed = seedFromPassphrase(passphrase);
    const prng = xorshift32(seed);

    const ivLen = 12;
    const iv = new Uint8Array(ivLen);
    for (let i = 0; i < ivLen; i++) iv[i] = prng();

    const plainBytes = strToBytes(plaintext);
    const out = new Uint8Array(ivLen + plainBytes.length);

    // keystream generation: advance PRNG by ivLen already done above (we used same prng),
    // so continue using the same prng to build keystream.
    const shift = seed % 256;

    for (let i = 0; i < plainBytes.length; i++) {
    const ks = prng(); // keystream byte
    const xored = plainBytes[i] ^ ks;
    // add small mixing with IV and shift
    const mixed = (xored + iv[i % ivLen] + shift) & 0xFF;
    out[ivLen + i] = mixed;
    }
    // prepend IV
    out.set(iv, 0);
    return bytesToBase64(out);
}

function hybridDecrypt(b64cipher, passphrase) {
    const all = base64ToBytes(b64cipher);
    const ivLen = 12;
    if (all.length < ivLen) throw new Error("Ciphertext too short");

    const iv = all.slice(0, ivLen);
    const cipherBytes = all.slice(ivLen);
    const seed = seedFromPassphrase(passphrase);

    // Create PRNG and advance it by generating ivLen bytes (to match encryption state)
    const prng = xorshift32(seed);
    for (let i = 0; i < ivLen; i++) prng(); // discard (we generated iv during encryption)

    const shift = seed % 256;
    const plain = new Uint8Array(cipherBytes.length);

    for (let i = 0; i < cipherBytes.length; i++) {
    const ks = prng();
    // reverse mixing and XOR
    const mixed = cipherBytes[i];
    const xored = (mixed - iv[i % ivLen] - shift) & 0xFF;
    const orig = (xored ^ ks) & 0xFF;
    plain[i] = orig;
    }
    return bytesToStr(plain);
}

function encryptJSON(obj, passphrase) {
    const jsonStr = JSON.stringify(obj);
    return hybridEncrypt(jsonStr, passphrase);
}

function decryptJSON(ciphertext, passphrase) {
    const jsonStr = hybridDecrypt(ciphertext, passphrase);
    return JSON.parse(jsonStr);
}

const key = "ini-kunci-super";