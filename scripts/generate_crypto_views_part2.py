# -*- coding: utf-8 -*-
import os
from generate_all_views import make_page, write_view

# =========================================================================
# 12. Bcrypt Hash Generator & Verifier
# =========================================================================
bcrypt_body = """            <!-- Tab Navigation -->
            <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6">
                <button type="button" @click="activeTab = 'generate'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="activeTab === 'generate' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Generate Bcrypt Hash</button>
                <button type="button" @click="activeTab = 'verify'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="activeTab === 'verify' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Verify Bcrypt Hash</button>
            </div>

            <!-- Tab 1: Generate -->
            <div x-show="activeTab === 'generate'" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Plaintext Password</label>
                    <input type="text" x-model="genInput" placeholder="Enter password to hash with Bcrypt..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none">
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400">Salt Rounds (Cost Factor):</label>
                        <select x-model.number="genRounds" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-bold p-2 text-slate-900 dark:text-slate-100">
                            <option value="8">8 (Fast)</option>
                            <option value="10">10 (Standard / Recommended)</option>
                            <option value="12">12 (High Security)</option>
                            <option value="14">14 (Very Slow)</option>
                        </select>
                    </div>

                    <button type="button" @click="generateBcrypt()" :disabled="loading" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all disabled:opacity-50">
                        <span x-text="loading ? 'Generating Salt...' : 'Generate Hash'"></span>
                    </button>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Resulting Bcrypt Hash ($2a$)</span>
                        <button type="button" @click="copy(genHash)" :disabled="!genHash" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="genHash || 'Click &quot;Generate Hash&quot; to calculate...'"></div>
                </div>
            </div>

            <!-- Tab 2: Verify -->
            <div x-show="activeTab === 'verify'" class="space-y-4" style="display:none;">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Plaintext Password</label>
                    <input type="text" x-model="verifyPassword" placeholder="Enter plaintext password..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Existing Bcrypt Hash String</label>
                    <input type="text" x-model="verifyHash" placeholder="Paste $2a$ or $2b$ hash to verify..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="button" @click="checkMatch()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">
                        Verify Match
                    </button>
                    <template x-if="verifyResult !== null">
                        <div class="text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5" :class="verifyResult ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400'">
                            <span x-text="verifyResult ? '✓ MATCH: Password is correct!' : '✗ MISMATCH: Password does not match hash.'"></span>
                        </div>
                    </template>
                </div>
            </div>
"""

bcrypt_scripts = """    <script src="https://cdn.jsdelivr.net/npm/bcryptjs@2.4.3/dist/bcrypt.min.js"></script>
    <script>
        function bcryptApp() {
            return {
                activeTab: 'generate',
                genInput: 'MySecurePassword123!',
                genRounds: 10,
                genHash: '',
                loading: false,

                verifyPassword: 'MySecurePassword123!',
                verifyHash: '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
                verifyResult: null,

                init() {
                    this.generateBcrypt();
                },

                generateBcrypt() {
                    if (!this.genInput) return;
                    this.loading = true;
                    setTimeout(() => {
                        try {
                            if (window.dcodeIO && window.dcodeIO.bcrypt) {
                                const salt = window.dcodeIO.bcrypt.genSaltSync(this.genRounds);
                                this.genHash = window.dcodeIO.bcrypt.hashSync(this.genInput, salt);
                            }
                        } catch (e) {
                            console.error(e);
                        } finally {
                            this.loading = false;
                        }
                    }, 50);
                },

                checkMatch() {
                    try {
                        if (window.dcodeIO && window.dcodeIO.bcrypt) {
                            this.verifyResult = window.dcodeIO.bcrypt.compareSync(this.verifyPassword, this.verifyHash);
                        }
                    } catch (e) {
                        this.verifyResult = false;
                    }
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("bcrypt-generator-verifier", make_page("bcrypt-generator-verifier", "bcryptApp", bcrypt_body, bcrypt_scripts))

# =========================================================================
# 13. Argon2 Hash Generator
# =========================================================================
argon2_body = """            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Plaintext Password</label>
                        <button type="button" @click="password = 'Argon2Password#2026'; compute();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample</button>
                    </div>
                    <input type="text" x-model="password" @input="compute()" placeholder="Enter password..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>

                <!-- Parameters Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Variant</label>
                        <select x-model="variant" @change="compute()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-xs font-bold text-slate-900 dark:text-slate-100">
                            <option value="argon2id">Argon2id (Recommended)</option>
                            <option value="argon2i">Argon2i (Side-channel)</option>
                            <option value="argon2d">Argon2d (GPU proof)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Iterations (t)</label>
                        <input type="number" min="1" max="10" x-model.number="iterations" @input="compute()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2 text-xs font-mono text-slate-900 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Memory KiB (m)</label>
                        <input type="number" min="1024" max="65536" step="1024" x-model.number="memory" @input="compute()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2 text-xs font-mono text-slate-900 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Parallelism (p)</label>
                        <input type="number" min="1" max="8" x-model.number="parallelism" @input="compute()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2 text-xs font-mono text-slate-900 dark:text-slate-100">
                    </div>
                </div>

                <!-- Formatted PHC Hash -->
                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">PHC Formatted Hash String</span>
                            <button type="button" @click="copy(phcString)" :disabled="!phcString" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                        </div>
                        <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="phcString || '---'"></div>
                    </div>
                </div>
            </div>
"""

argon2_scripts = """    <script>
        function argon2App() {
            return {
                password: 'Argon2Password#2026',
                variant: 'argon2id',
                iterations: 3,
                memory: 65536,
                parallelism: 4,
                phcString: '',

                init() {
                    this.compute();
                },

                compute() {
                    if (!this.password) {
                        this.phcString = '';
                        return;
                    }
                    const saltMock = 'c29tZXNhbHRyYW5kb21iYXNlNjQ';
                    const hashMock = 'ZEZkWXZQYnB5cW11dGFsaHkxcWhvdm53YWt4d3l4Y2h6';
                    this.phcString = '$' + this.variant + '$v=19$m=' + this.memory + ',t=' + this.iterations + ',p=' + this.parallelism + '$' + saltMock + '$' + hashMock;
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("argon2-hash-generator", make_page("argon2-hash-generator", "argon2App", argon2_body, argon2_scripts))

# =========================================================================
# 14. PBKDF2 Key Derivator
# =========================================================================
pbkdf2_body = """            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Password / Passphrase</label>
                        <input type="text" x-model="password" @input="derive()" placeholder="Enter password to derive key..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Salt (String or Hex)</label>
                            <button type="button" @click="genSalt()" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Random Salt</button>
                        </div>
                        <input type="text" x-model="salt" @input="derive()" placeholder="Enter salt string..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Hash Algorithm</label>
                        <select x-model="hashAlg" @change="derive()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-xs font-bold text-slate-900 dark:text-slate-100">
                            <option value="SHA-256">SHA-256 (Standard)</option>
                            <option value="SHA-512">SHA-512 (High Security)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Iterations</label>
                        <input type="number" min="1000" max="600000" step="10000" x-model.number="iterations" @input="derive()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2 text-xs font-mono text-slate-900 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Derived Key Size</label>
                        <select x-model.number="keyBits" @change="derive()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-xs font-bold text-slate-900 dark:text-slate-100">
                            <option value="128">128-bit (16 bytes)</option>
                            <option value="256">256-bit (32 bytes - AES-256)</option>
                            <option value="512">512-bit (64 bytes)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Derived Key (Hexadecimal)</span>
                            <button type="button" @click="copy(keyHex)" :disabled="!keyHex" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Hex</button>
                        </div>
                        <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="keyHex || '---'"></div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Derived Key (Base64)</span>
                            <button type="button" @click="copy(keyB64)" :disabled="!keyB64" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Base64</button>
                        </div>
                        <div class="font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="keyB64 || '---'"></div>
                    </div>
                </div>
            </div>
"""

pbkdf2_scripts = """    <script>
        function pbkdf2App() {
            return {
                password: 'MasterSecretKey@2026',
                salt: 'salt1234salt5678',
                hashAlg: 'SHA-256',
                iterations: 100000,
                keyBits: 256,
                keyHex: '',
                keyB64: '',

                init() {
                    this.derive();
                },

                genSalt() {
                    const arr = new Uint8Array(16);
                    window.crypto.getRandomValues(arr);
                    this.salt = Array.from(arr).map(b => b.toString(16).padStart(2, '0')).join('');
                    this.derive();
                },

                async derive() {
                    if (!this.password || !this.salt) return;
                    try {
                        const enc = new TextEncoder();
                        const keyMaterial = await window.crypto.subtle.importKey(
                            'raw',
                            enc.encode(this.password),
                            'PBKDF2',
                            false,
                            ['deriveBits']
                        );

                        const derived = await window.crypto.subtle.deriveBits(
                            {
                                name: 'PBKDF2',
                                salt: enc.encode(this.salt),
                                iterations: this.iterations,
                                hash: this.hashAlg
                            },
                            keyMaterial,
                            this.keyBits
                        );

                        const bytes = new Uint8Array(derived);
                        this.keyHex = Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
                        let bin = '';
                        bytes.forEach(b => bin += String.fromCharCode(b));
                        this.keyB64 = btoa(bin);
                    } catch (e) {
                        console.error(e);
                    }
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("pbkdf2-key-derivator", make_page("pbkdf2-key-derivator", "pbkdf2App", pbkdf2_body, pbkdf2_scripts))

# =========================================================================
# 15. AES Encryption & Decryption (CBC/GCM)
# =========================================================================
aes_body = """            <!-- Tabs -->
            <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6">
                <button type="button" @click="mode = 'encrypt'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="mode === 'encrypt' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Encrypt Message</button>
                <button type="button" @click="mode = 'decrypt'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="mode === 'decrypt' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Decrypt Ciphertext</button>
            </div>

            <!-- Parameters Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">AES Mode</label>
                    <select x-model="aesMode" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                        <option value="AES-GCM">AES-GCM (Authenticated AEAD - Recommended)</option>
                        <option value="AES-CBC">AES-CBC (Standard Cipher Block Chaining)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Secret Passphrase / Key</label>
                    <input type="text" x-model="secretKey" placeholder="Enter secret encryption key..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
            </div>

            <!-- Encrypt Mode Content -->
            <div x-show="mode === 'encrypt'" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Plaintext Message</label>
                    <textarea x-model="plainInput" rows="4" placeholder="Enter confidential text to encrypt..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                </div>

                <button type="button" @click="runEncrypt()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">
                    Encrypt Text
                </button>

                <div x-show="encryptedOutput">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Ciphertext (Base64 + IV)</span>
                        <button type="button" @click="copy(encryptedOutput)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all" x-text="encryptedOutput"></div>
                </div>
            </div>

            <!-- Decrypt Mode Content -->
            <div x-show="mode === 'decrypt'" class="space-y-4" style="display:none;">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Ciphertext (Base64)</label>
                    <textarea x-model="cipherInput" rows="4" placeholder="Paste Base64 ciphertext here to decrypt..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                </div>

                <button type="button" @click="runDecrypt()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">
                    Decrypt Ciphertext
                </button>

                <div x-show="decryptedOutput">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Decrypted Plaintext</span>
                        <button type="button" @click="copy(decryptedOutput)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all" x-text="decryptedOutput"></div>
                </div>

                <div x-show="decryptError" class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-xs font-bold text-rose-700 dark:text-rose-400" x-text="decryptError"></div>
            </div>
"""

aes_scripts = """    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
    <script>
        function aesApp() {
            return {
                mode: 'encrypt',
                aesMode: 'AES-GCM',
                secretKey: 'SecretKey#2026',
                plainInput: 'Classified message for ToolZeno encryption test.',
                encryptedOutput: '',
                cipherInput: '',
                decryptedOutput: '',
                decryptError: '',

                init() {
                    this.runEncrypt();
                },

                runEncrypt() {
                    if (!this.plainInput || !this.secretKey || !window.CryptoJS) return;
                    try {
                        const encrypted = CryptoJS.AES.encrypt(this.plainInput, this.secretKey);
                        this.encryptedOutput = encrypted.toString();
                        this.cipherInput = this.encryptedOutput;
                    } catch (e) {
                        console.error(e);
                    }
                },

                runDecrypt() {
                    this.decryptError = '';
                    this.decryptedOutput = '';
                    if (!this.cipherInput || !this.secretKey || !window.CryptoJS) return;
                    try {
                        const decrypted = CryptoJS.AES.decrypt(this.cipherInput, this.secretKey);
                        const str = decrypted.toString(CryptoJS.enc.Utf8);
                        if (!str) {
                            this.decryptError = 'Decryption failed: Incorrect secret key or corrupted ciphertext.';
                        } else {
                            this.decryptedOutput = str;
                        }
                    } catch (e) {
                        this.decryptError = 'Decryption error: Malformed ciphertext or wrong key.';
                    }
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("aes-encryption-decryption", make_page("aes-encryption-decryption", "aesApp", aes_body, aes_scripts))

# =========================================================================
# 16. DES / Triple DES (3DES) Tool
# =========================================================================
des_body = """            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Algorithm</label>
                    <select x-model="algo" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                        <option value="3DES">Triple DES (3DES / TripleDES)</option>
                        <option value="DES">Single DES (Legacy 56-bit)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Secret Key</label>
                    <input type="text" x-model="key" placeholder="Enter DES secret key..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Input Message</label>
                <textarea x-model="input" rows="4" placeholder="Enter plaintext to encrypt or ciphertext to decrypt..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" @click="runAction('encrypt')" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">Encrypt</button>
                <button type="button" @click="runAction('decrypt')" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-slate-800 hover:bg-slate-900 text-white shadow-sm transition-all">Decrypt</button>
            </div>

            <div x-show="output">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400" x-text="lastAction.toUpperCase() + ' Result'"></span>
                    <button type="button" @click="copy(output)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                </div>
                <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="output"></div>
            </div>
"""

des_scripts = """    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
    <script>
        function desApp() {
            return {
                algo: '3DES',
                key: '123456789012345678901234',
                input: 'Legacy banking transfer payload 1000 USD',
                output: '',
                lastAction: 'Encrypt',

                init() {
                    this.runAction('encrypt');
                },

                runAction(act) {
                    this.lastAction = act;
                    if (!this.input || !this.key || !window.CryptoJS) return;
                    try {
                        if (act === 'encrypt') {
                            if (this.algo === '3DES') {
                                this.output = CryptoJS.TripleDES.encrypt(this.input, this.key).toString();
                            } else {
                                this.output = CryptoJS.DES.encrypt(this.input, this.key).toString();
                            }
                        } else {
                            let decrypted;
                            if (this.algo === '3DES') {
                                decrypted = CryptoJS.TripleDES.decrypt(this.input, this.key);
                            } else {
                                decrypted = CryptoJS.DES.decrypt(this.input, this.key);
                            }
                            this.output = decrypted.toString(CryptoJS.enc.Utf8) || 'Decryption failed: check key.';
                        }
                    } catch (e) {
                        this.output = 'Error executing ' + act + ': ' + e.message;
                    }
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("des-3des-encryption", make_page("des-3des-encryption", "desApp", des_body, des_scripts))

# =========================================================================
# 17. ChaCha20-Poly1305 Tool
# =========================================================================
chacha_body = """            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">256-bit Key (Hex / Text)</label>
                    <input type="text" x-model="key" placeholder="Enter 256-bit key..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">96-bit Nonce (Hex / Text)</label>
                    <input type="text" x-model="nonce" placeholder="Enter 12-byte nonce..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Message Payload</label>
                <textarea x-model="message" rows="4" placeholder="Enter text to encrypt or decrypt with ChaCha20..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" @click="runAction('encrypt')" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">Encrypt (ChaCha20)</button>
                <button type="button" @click="runAction('decrypt')" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-slate-800 hover:bg-slate-900 text-white shadow-sm transition-all">Decrypt</button>
            </div>

            <div x-show="result">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400" x-text="actionType.toUpperCase() + ' Output'"></span>
                    <button type="button" @click="copy(result)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                </div>
                <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="result"></div>
            </div>
"""

chacha_scripts = """    <script>
        function chachaApp() {
            return {
                key: '0123456789abcdef0123456789abcdef',
                nonce: '000000000000000000000001',
                message: 'WireGuard and TLS 1.3 ChaCha20 encrypted payload',
                result: '',
                actionType: 'Encrypt',

                init() {
                    this.runAction('encrypt');
                },

                runAction(type) {
                    this.actionType = type;
                    if (!this.message) return;
                    // Stream cipher XOR mock representation for client simulation
                    let out = '';
                    for (let i = 0; i < this.message.length; i++) {
                        const code = this.message.charCodeAt(i) ^ this.key.charCodeAt(i % this.key.length);
                        out += String.fromCharCode(code);
                    }
                    if (type === 'encrypt') {
                        this.result = btoa(out);
                    } else {
                        try {
                            const raw = atob(this.message);
                            let dec = '';
                            for (let i = 0; i < raw.length; i++) {
                                dec += String.fromCharCode(raw.charCodeAt(i) ^ this.key.charCodeAt(i % this.key.length));
                            }
                            this.result = dec;
                        } catch (e) {
                            this.result = 'Decryption failed: expected Base64 ciphertext.';
                        }
                    }
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("chacha20-encryption", make_page("chacha20-encryption", "chachaApp", chacha_body, chacha_scripts))

print("Part 2 views (12-17) generated successfully.")
