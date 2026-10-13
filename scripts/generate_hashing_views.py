# -*- coding: utf-8 -*-
import os
from generate_all_views import make_page, write_view

# =========================================================================
# 5. MD5 Hash Generator
# =========================================================================
md5_body = """            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Plaintext String / Message</label>
                    <button type="button" @click="input = 'The quick brown fox jumps over the lazy dog'; compute();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample Text</button>
                </div>
                <textarea x-model="input" @input="compute()" rows="4" placeholder="Type or paste any text to calculate MD5 hash..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none transition-all"></textarea>
                <div class="flex justify-between text-xs text-slate-400 mt-1">
                    <span>Characters: <strong x-text="input.length"></strong></span>
                    <span>UTF-8 Bytes: <strong x-text="byteCount"></strong></span>
                </div>
            </div>

            <!-- MD5 Hash Outputs -->
            <div class="space-y-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">MD5 Hex (Lowercase - Standard)</span>
                        <button type="button" @click="copy(hashLower)" :disabled="!hashLower" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-sm sm:text-base font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashLower || '---'"></div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">MD5 Hex (Uppercase)</span>
                        <button type="button" @click="copy(hashUpper)" :disabled="!hashUpper" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-sm sm:text-base font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashUpper || '---'"></div>
                </div>
            </div>
"""

md5_scripts = """    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
    <script>
        function md5GeneratorApp() {
            return {
                input: 'The quick brown fox jumps over the lazy dog',
                hashLower: '',
                hashUpper: '',
                byteCount: 0,

                init() {
                    this.compute();
                },

                compute() {
                    if (!this.input) {
                        this.hashLower = '';
                        this.hashUpper = '';
                        this.byteCount = 0;
                        return;
                    }
                    this.byteCount = new TextEncoder().encode(this.input).length;
                    if (window.CryptoJS) {
                        const hash = CryptoJS.MD5(this.input).toString();
                        this.hashLower = hash.toLowerCase();
                        this.hashUpper = hash.toUpperCase();
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
write_view("md5-hash-generator", make_page("md5-hash-generator", "md5GeneratorApp", md5_body, md5_scripts))

# =========================================================================
# 6. SHA-1 Hash Generator
# =========================================================================
sha1_body = """            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Plaintext Input Data</label>
                    <button type="button" @click="input = 'Hello, ToolZeno Security!'; compute();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample</button>
                </div>
                <textarea x-model="input" @input="compute()" rows="4" placeholder="Enter text to calculate 160-bit SHA-1 hash..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none transition-all"></textarea>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">SHA-1 Hex Digest (40 characters)</span>
                        <button type="button" @click="copy(hashHex)" :disabled="!hashHex" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Hex</button>
                    </div>
                    <div class="font-mono text-sm sm:text-base font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashHex || '---'"></div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">SHA-1 Base64 String</span>
                        <button type="button" @click="copy(hashB64)" :disabled="!hashB64" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Base64</button>
                    </div>
                    <div class="font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashB64 || '---'"></div>
                </div>
            </div>
"""

sha1_scripts = """    <script>
        function sha1GeneratorApp() {
            return {
                input: 'Hello, ToolZeno Security!',
                hashHex: '',
                hashB64: '',

                init() {
                    this.compute();
                },

                async compute() {
                    if (!this.input) {
                        this.hashHex = '';
                        this.hashB64 = '';
                        return;
                    }
                    const data = new TextEncoder().encode(this.input);
                    const digest = await window.crypto.subtle.digest('SHA-1', data);
                    const bytes = new Uint8Array(digest);
                    this.hashHex = Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
                    let bin = '';
                    bytes.forEach(b => bin += String.fromCharCode(b));
                    this.hashB64 = btoa(bin);
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("sha1-hash-generator", make_page("sha1-hash-generator", "sha1GeneratorApp", sha1_body, sha1_scripts))

# =========================================================================
# 7. SHA-256 Hash Generator
# =========================================================================
sha256_body = """            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Plaintext Input String</label>
                    <button type="button" @click="input = 'ToolZeno Military Grade SHA-256'; compute();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample Text</button>
                </div>
                <textarea x-model="input" @input="compute()" rows="4" placeholder="Enter string to calculate standard 256-bit SHA-256 hash..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none transition-all"></textarea>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">SHA-256 Hex Digest (64 characters / 256 bits)</span>
                        <button type="button" @click="copy(hashHex)" :disabled="!hashHex" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Hex</button>
                    </div>
                    <div class="font-mono text-sm sm:text-base font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashHex || '---'"></div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">SHA-256 Base64 Digest</span>
                        <button type="button" @click="copy(hashB64)" :disabled="!hashB64" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Base64</button>
                    </div>
                    <div class="font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashB64 || '---'"></div>
                </div>
            </div>
"""

sha256_scripts = """    <script>
        function sha256GeneratorApp() {
            return {
                input: 'ToolZeno Military Grade SHA-256',
                hashHex: '',
                hashB64: '',

                init() {
                    this.compute();
                },

                async compute() {
                    if (!this.input) {
                        this.hashHex = '';
                        this.hashB64 = '';
                        return;
                    }
                    const data = new TextEncoder().encode(this.input);
                    const digest = await window.crypto.subtle.digest('SHA-256', data);
                    const bytes = new Uint8Array(digest);
                    this.hashHex = Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
                    let bin = '';
                    bytes.forEach(b => bin += String.fromCharCode(b));
                    this.hashB64 = btoa(bin);
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("sha256-hash-generator", make_page("sha256-hash-generator", "sha256GeneratorApp", sha256_body, sha256_scripts))

# =========================================================================
# 8. SHA-512 Hash Generator
# =========================================================================
sha512_body = """            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Plaintext Input String</label>
                    <button type="button" @click="input = 'Ultra Secure High Entropy Payload'; compute();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample</button>
                </div>
                <textarea x-model="input" @input="compute()" rows="4" placeholder="Enter text to calculate 512-bit SHA-512 hash..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none transition-all"></textarea>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">SHA-512 Hex Digest (128 characters / 512 bits)</span>
                        <button type="button" @click="copy(hashHex)" :disabled="!hashHex" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Hex</button>
                    </div>
                    <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashHex || '---'"></div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">SHA-512 Base64 Output</span>
                        <button type="button" @click="copy(hashB64)" :disabled="!hashB64" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Base64</button>
                    </div>
                    <div class="font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashB64 || '---'"></div>
                </div>
            </div>
"""

sha512_scripts = """    <script>
        function sha512GeneratorApp() {
            return {
                input: 'Ultra Secure High Entropy Payload',
                hashHex: '',
                hashB64: '',

                init() {
                    this.compute();
                },

                async compute() {
                    if (!this.input) {
                        this.hashHex = '';
                        this.hashB64 = '';
                        return;
                    }
                    const data = new TextEncoder().encode(this.input);
                    const digest = await window.crypto.subtle.digest('SHA-512', data);
                    const bytes = new Uint8Array(digest);
                    this.hashHex = Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
                    let bin = '';
                    bytes.forEach(b => bin += String.fromCharCode(b));
                    this.hashB64 = btoa(bin);
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("sha512-hash-generator", make_page("sha512-hash-generator", "sha512GeneratorApp", sha512_body, sha512_scripts))

# =========================================================================
# 9. SHA-3 / Keccak Hash Generator
# =========================================================================
sha3_body = """            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Algorithm</label>
                    <select x-model="algorithm" @change="compute()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none">
                        <option value="keccak256">Keccak-256 (Ethereum standard)</option>
                        <option value="sha3_256">SHA3-256 (NIST standard)</option>
                        <option value="sha3_512">SHA3-512 (High Security)</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Input Text or Hex Payload</label>
                        <button type="button" @click="input = 'transfer(address,uint256)'; compute();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample (Web3 method)</button>
                    </div>
                    <input type="text" x-model="input" @input="compute()" placeholder="Type message or contract signature to hash..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400" x-text="algorithm.toUpperCase() + ' Hash Output'"></span>
                        <button type="button" @click="copy(hashHex)" :disabled="!hashHex" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Hex</button>
                    </div>
                    <div class="font-mono text-sm sm:text-base font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashHex || '---'"></div>
                </div>

                <div x-show="algorithm === 'keccak256' && hashHex">
                    <span class="text-xs font-semibold text-slate-500">Ethereum 4-byte Method Selector:</span>
                    <span class="font-mono text-xs font-bold text-rose-600 dark:text-rose-400 ml-2" x-text="'0x' + hashHex.substring(0, 8)"></span>
                </div>
            </div>
"""

sha3_scripts = """    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
    <script>
        function sha3GeneratorApp() {
            return {
                algorithm: 'keccak256',
                input: 'transfer(address,uint256)',
                hashHex: '',

                init() {
                    this.compute();
                },

                compute() {
                    if (!this.input || !window.CryptoJS) {
                        this.hashHex = '';
                        return;
                    }
                    if (this.algorithm === 'sha3_512') {
                        this.hashHex = CryptoJS.SHA3(this.input, { outputLength: 512 }).toString();
                    } else if (this.algorithm === 'sha3_256') {
                        this.hashHex = CryptoJS.SHA3(this.input, { outputLength: 256 }).toString();
                    } else {
                        // Keccak 256
                        this.hashHex = CryptoJS.SHA3(this.input, { outputLength: 256, keccak: true }).toString();
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
write_view("sha3-hash-generator", make_page("sha3-hash-generator", "sha3GeneratorApp", sha3_body, sha3_scripts))

# =========================================================================
# 10. File Checksum / Hash Calculator
# =========================================================================
file_check_body = """            <!-- Drag & Drop Area -->
            <div class="border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-rose-500 dark:hover:border-rose-400 rounded-2xl p-8 text-center bg-slate-50/50 dark:bg-slate-950/50 cursor-pointer transition-all" @dragover.prevent @drop.prevent="handleDrop($event)" @click="$refs.fileInput.click()">
                <input type="file" x-ref="fileInput" @change="handleFile($event)" class="hidden">
                <div class="flex flex-col items-center justify-center space-y-2">
                    <svg class="w-12 h-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Click to select a file or drag & drop here</span>
                    <span class="text-xs text-slate-500">100% processed locally in your browser memory — 0 bytes uploaded to any server</span>
                </div>
            </div>

            <!-- Active File Info -->
            <template x-if="fileName">
                <div class="p-4 rounded-xl bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-sm text-slate-900 dark:text-white block" x-text="fileName"></span>
                        <span class="text-xs text-slate-500 font-mono" x-text="fileSize"></span>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400" x-text="statusText"></span>
                </div>
            </template>

            <!-- Hashes Grid -->
            <div class="space-y-3 pt-2">
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700 dark:text-slate-300">SHA-256 Checksum</span>
                        <button type="button" @click="copy(sha256)" :disabled="!sha256" class="text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-200 dark:border-slate-800 break-all select-all text-slate-900 dark:text-slate-100" x-text="sha256 || 'Waiting for file...'"></div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700 dark:text-slate-300">MD5 Checksum</span>
                        <button type="button" @click="copy(md5)" :disabled="!md5" class="text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-200 dark:border-slate-800 break-all select-all text-slate-900 dark:text-slate-100" x-text="md5 || 'Waiting for file...'"></div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700 dark:text-slate-300">SHA-1 Checksum</span>
                        <button type="button" @click="copy(sha1)" :disabled="!sha1" class="text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-200 dark:border-slate-800 break-all select-all text-slate-900 dark:text-slate-100" x-text="sha1 || 'Waiting for file...'"></div>
                </div>
            </div>

            <!-- Checksum Comparator -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Verify Against Expected Checksum</label>
                <input type="text" x-model="expected" @input="verifyMatch()" placeholder="Paste expected checksum here to compare..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                <template x-if="matchResult !== null">
                    <div class="mt-2 text-xs font-bold flex items-center gap-1.5" :class="matchResult ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'">
                        <span x-text="matchResult ? '✓ MATCH CONFIRMED: File is authentic!' : '✗ MISMATCH: File checksum does NOT match expected hash.'"></span>
                    </div>
                </template>
            </div>
"""

file_check_scripts = """    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
    <script>
        function fileChecksumApp() {
            return {
                fileName: '',
                fileSize: '',
                statusText: 'Idle',
                sha256: '',
                md5: '',
                sha1: '',
                expected: '',
                matchResult: null,

                handleDrop(e) {
                    if (e.dataTransfer.files.length) {
                        this.processFile(e.dataTransfer.files[0]);
                    }
                },

                handleFile(e) {
                    if (e.target.files.length) {
                        this.processFile(e.target.files[0]);
                    }
                },

                async processFile(file) {
                    this.fileName = file.name;
                    this.fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                    this.statusText = 'Calculating hashes...';

                    const buffer = await file.arrayBuffer();

                    // SHA-256
                    const d256 = await window.crypto.subtle.digest('SHA-256', buffer);
                    this.sha256 = Array.from(new Uint8Array(d256)).map(b => b.toString(16).padStart(2, '0')).join('');

                    // SHA-1
                    const d1 = await window.crypto.subtle.digest('SHA-1', buffer);
                    this.sha1 = Array.from(new Uint8Array(d1)).map(b => b.toString(16).padStart(2, '0')).join('');

                    // MD5 via CryptoJS wordArray
                    if (window.CryptoJS) {
                        const wordArray = CryptoJS.lib.WordArray.create(buffer);
                        this.md5 = CryptoJS.MD5(wordArray).toString();
                    }

                    this.statusText = 'Completed';
                    this.verifyMatch();
                },

                verifyMatch() {
                    if (!this.expected.trim()) {
                        this.matchResult = null;
                        return;
                    }
                    const exp = this.expected.trim().toLowerCase();
                    this.matchResult = (exp === this.sha256 || exp === this.md5 || exp === this.sha1);
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("file-checksum-calculator", make_page("file-checksum-calculator", "fileChecksumApp", file_check_body, file_check_scripts))

# =========================================================================
# 11. Hash Identifier
# =========================================================================
hash_id_body = """            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Raw Hash String</label>
                    <button type="button" @click="input = '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy'; analyze();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample (Bcrypt)</button>
                </div>
                <input type="text" x-model="input" @input="analyze()" placeholder="Paste unknown hash string here (e.g. 5d41402abc4b2a76b9719d911017c592)..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none">
            </div>

            <!-- Stats Bar -->
            <div class="flex items-center gap-4 text-xs font-semibold text-slate-600 dark:text-slate-400">
                <span>Length: <strong class="text-slate-900 dark:text-slate-100" x-text="input.trim().length"></strong> characters</span>
                <span>Hex: <strong class="text-slate-900 dark:text-slate-100" x-text="isHex ? 'Yes' : 'No'"></strong></span>
                <span>Base64: <strong class="text-slate-900 dark:text-slate-100" x-text="isBase64 ? 'Yes' : 'No'"></strong></span>
            </div>

            <!-- Identified Candidates -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-3">Identified Hash Types</span>
                <template x-if="matches.length === 0">
                    <div class="text-xs text-slate-400 py-4 text-center">Paste a hash string above to detect matching algorithms.</div>
                </template>
                <div class="space-y-2.5">
                    <template x-for="(m, i) in matches" :key="i">
                        <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-sm text-slate-900 dark:text-white" x-text="m.name"></span>
                                <span class="text-xs text-slate-500 block" x-text="m.desc"></span>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full" :class="m.badgeClass" x-text="m.confidence"></span>
                        </div>
                    </template>
                </div>
            </div>
"""

hash_id_scripts = """    <script>
        function hashIdentifierApp() {
            return {
                input: '5d41402abc4b2a76b9719d911017c592',
                isHex: false,
                isBase64: false,
                matches: [],

                init() {
                    this.analyze();
                },

                analyze() {
                    const h = this.input.trim();
                    this.matches = [];
                    if (!h) return;

                    this.isHex = /^[0-9a-fA-F]+$/.test(h);
                    this.isBase64 = /^[0-9a-zA-Z+/=_-]+$/.test(h);

                    // Bcrypt
                    if (/^\\$2[aby]\\$\\d{2}\\$[./A-Za-z0-9]{53}$/.test(h)) {
                        this.matches.push({ name: 'Bcrypt Password Hash', desc: 'Modular crypt format, 60 characters with salt and cost factor.', confidence: 'Exact Match (100%)', badgeClass: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' });
                    }

                    // Argon2
                    if (/^\\$argon2(id|i|d)\\$v=\\d+\\$m=\\d+/.test(h)) {
                        this.matches.push({ name: 'Argon2 Password Hash', desc: 'Standard PHC string with memory, time cost, and salt parameters.', confidence: 'Exact Match (100%)', badgeClass: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' });
                    }

                    // MD5 / NTLM (32 hex)
                    if (h.length === 32 && this.isHex) {
                        this.matches.push({ name: 'MD5 (Message Digest 5)', desc: '128-bit checksum. Most common 32-char hex hash.', confidence: 'High Probability', badgeClass: 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400' });
                        this.matches.push({ name: 'NTLM / MD4', desc: 'Windows NT password hash format.', confidence: 'Possible', badgeClass: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' });
                    }

                    // SHA-1 (40 hex)
                    if (h.length === 40 && this.isHex) {
                        this.matches.push({ name: 'SHA-1 (Secure Hash Algorithm 1)', desc: '160-bit hash. Used in Git commits and legacy SSL.', confidence: 'High Probability', badgeClass: 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400' });
                        this.matches.push({ name: 'RIPEMD-160', desc: '160-bit European cryptographic hash function.', confidence: 'Possible', badgeClass: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' });
                    }

                    // SHA-256 (64 hex)
                    if (h.length === 64 && this.isHex) {
                        this.matches.push({ name: 'SHA-256 (SHA-2 Family)', desc: '256-bit cryptographic digest. Bitcoin and standard TLS hash.', confidence: 'High Probability', badgeClass: 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400' });
                        this.matches.push({ name: 'SHA3-256 / Keccak-256', desc: 'Ethereum smart contract hash.', confidence: 'Possible', badgeClass: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' });
                    }

                    // SHA-512 (128 hex)
                    if (h.length === 128 && this.isHex) {
                        this.matches.push({ name: 'SHA-512 (SHA-2 Family)', desc: '512-bit high-security digest for 64-bit systems.', confidence: 'High Probability', badgeClass: 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400' });
                        this.matches.push({ name: 'Whirlpool', desc: '512-bit hash based on AES design.', confidence: 'Possible', badgeClass: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' });
                    }

                    // CRC32 (8 hex)
                    if (h.length === 8 && this.isHex) {
                        this.matches.push({ name: 'CRC32 Checksum', desc: '32-bit cyclic redundancy check used in ZIP archives.', confidence: 'High Probability', badgeClass: 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400' });
                    }
                }
            }
        }
    </script>
"""
write_view("hash-identifier", make_page("hash-identifier", "hashIdentifierApp", hash_id_body, hash_id_scripts))

print("Hashing tools views generated successfully.")
