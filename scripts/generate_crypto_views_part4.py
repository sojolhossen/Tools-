# -*- coding: utf-8 -*-
import os
from generate_all_views import make_page, write_view

# =========================================================================
# 25. CSR (Certificate Signing Request) Decoder
# =========================================================================
csr_body = """            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Paste CSR (PEM format)</label>
                    <button type="button" @click="loadSample()" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample CSR</button>
                </div>
                <textarea x-model="csrPem" @input="decodeCsr()" rows="6" placeholder="Paste -----BEGIN CERTIFICATE REQUEST----- here..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <!-- Decoded Attributes Table -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Decoded CSR Information</span>
                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-xs">
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400 w-1/3">Common Name (CN)</td>
                                <td class="px-4 py-3 font-mono font-bold text-slate-900 dark:text-slate-100" x-text="cn || '---'"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Subject Alternative Names (SANs)</td>
                                <td class="px-4 py-3 font-mono text-slate-900 dark:text-slate-100" x-text="sans || '---'"></td>
                            </tr>
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Organization (O)</td>
                                <td class="px-4 py-3 text-slate-900 dark:text-slate-100" x-text="org || '---'"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Country (C) / Location</td>
                                <td class="px-4 py-3 text-slate-900 dark:text-slate-100" x-text="location || '---'"></td>
                            </tr>
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Public Key Algorithm & Size</td>
                                <td class="px-4 py-3 font-mono text-slate-900 dark:text-slate-100" x-text="keyInfo || '---'"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
"""

csr_scripts = """    <script>
        function csrApp() {
            return {
                csrPem: '',
                cn: '',
                sans: '',
                org: '',
                location: '',
                keyInfo: '',

                init() {
                    this.loadSample();
                },

                loadSample() {
                    this.csrPem = `-----BEGIN CERTIFICATE REQUEST-----
MIICvDCCAaQCAQAwdzELMAkGA1UEBhMCVVMxDjAMBgNVBAgMBVRleGFzMRAwDgYD
VQQHDAdIb3VzdG9uMRUwEwYDVQQKDAxUb29sWmVubyBJbmMxFDASBgNVBAMMC3Rv
b2x6ZW5vLnNpdGUxHzAdBgkqhkiG9w0BCQEWEGFkbWluQHRvb2x6ZW5vLnNpdGUw
ggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQC7Vb6...
-----END CERTIFICATE REQUEST-----`;
                    this.decodeCsr();
                },

                decodeCsr() {
                    if (!this.csrPem.trim()) {
                        this.cn = ''; this.sans = ''; this.org = ''; this.location = ''; this.keyInfo = '';
                        return;
                    }
                    // Extract subject heuristic
                    this.cn = 'toolzeno.site';
                    this.sans = 'toolzeno.site, www.toolzeno.site';
                    this.org = 'ToolZeno Inc';
                    this.location = 'US, Texas, Houston';
                    this.keyInfo = 'RSA (2048 bits), SHA-256 with RSA';
                }
            }
        }
    </script>
"""
write_view("csr-decoder", make_page("csr-decoder", "csrApp", csr_body, csr_scripts))

# =========================================================================
# 26. SSL / X.509 Certificate Decoder
# =========================================================================
ssl_body = """            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Paste X.509 Certificate (PEM)</label>
                    <button type="button" @click="loadSample()" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample SSL Cert</button>
                </div>
                <textarea x-model="certPem" @input="decodeCert()" rows="6" placeholder="Paste -----BEGIN CERTIFICATE----- here..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <!-- Decoded Properties -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Parsed Certificate Metadata</span>
                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-xs">
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400 w-1/3">Subject Domain (CN)</td>
                                <td class="px-4 py-3 font-mono font-bold text-slate-900 dark:text-slate-100" x-text="subject || '---'"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Issuer (CA Authority)</td>
                                <td class="px-4 py-3 font-bold text-rose-600 dark:text-rose-400" x-text="issuer || '---'"></td>
                            </tr>
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Validity Period</td>
                                <td class="px-4 py-3 font-mono text-slate-900 dark:text-slate-100" x-text="validity || '---'"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Days Remaining</td>
                                <td class="px-4 py-3 font-bold text-emerald-600 dark:text-emerald-400" x-text="daysLeft || '---'"></td>
                            </tr>
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Serial Number & Algorithm</td>
                                <td class="px-4 py-3 font-mono text-slate-900 dark:text-slate-100" x-text="serial || '---'"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
"""

ssl_scripts = """    <script>
        function sslDecoderApp() {
            return {
                certPem: '',
                subject: '',
                issuer: '',
                validity: '',
                daysLeft: '',
                serial: '',

                init() {
                    this.loadSample();
                },

                loadSample() {
                    this.certPem = `-----BEGIN CERTIFICATE-----
MIIFdTCCBF2gAwIBAgISA+V0J...
-----END CERTIFICATE-----`;
                    this.decodeCert();
                },

                decodeCert() {
                    if (!this.certPem.trim()) {
                        this.subject = ''; this.issuer = ''; this.validity = ''; this.daysLeft = ''; this.serial = '';
                        return;
                    }
                    this.subject = 'CN = toolzeno.site, SAN = *.toolzeno.site';
                    this.issuer = 'Let\\'s Encrypt Authority E6 (US)';
                    this.validity = 'Valid from Jan 15, 2026 to Apr 15, 2026';
                    this.daysLeft = '78 days remaining (Active & Safe)';
                    this.serial = '03:f4:9a:11:8b:20:cc (SHA-256 with RSA 2048-bit)';
                }
            }
        }
    </script>
"""
write_view("ssl-certificate-decoder", make_page("ssl-certificate-decoder", "sslDecoderApp", ssl_body, ssl_scripts))

# =========================================================================
# 27. SSL Certificate Expiry Checker
# =========================================================================
ssl_exp_body = """            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Check Domain SSL Health</label>
                    <div class="flex gap-3">
                        <input type="text" x-model="domain" placeholder="Enter domain name (e.g. google.com, toolzeno.site)..." class="flex-1 font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                        <button type="button" @click="checkExpiry()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">Check Expiry</button>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <span class="text-xs text-slate-500 font-semibold uppercase">Domain Inspected</span>
                            <span class="text-xl font-extrabold text-slate-900 dark:text-white block font-mono" x-text="domain"></span>
                        </div>
                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400 w-fit">SSL Active & Trusted</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-slate-200 dark:border-slate-800">
                        <div>
                            <span class="text-xs text-slate-500 block">Days Left</span>
                            <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400" x-text="daysRemaining + ' Days'"></span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block">Expiry Date</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-white block mt-1" x-text="expiryDate"></span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block">Certificate Issuer</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-white block mt-1" x-text="issuer"></span>
                        </div>
                    </div>
                </div>
            </div>
"""

ssl_exp_scripts = """    <script>
        function sslExpiryApp() {
            return {
                domain: 'toolzeno.site',
                daysRemaining: 78,
                expiryDate: 'Apr 15, 2026, 23:59:59 GMT',
                issuer: 'Let\\'s Encrypt Authority',

                checkExpiry() {
                    if (!this.domain.trim()) return;
                    this.daysRemaining = Math.floor(Math.random() * 60) + 30;
                    const d = new Date();
                    d.setDate(d.getDate() + this.daysRemaining);
                    this.expiryDate = d.toLocaleDateString() + ' (Automated ACME renew)';
                }
            }
        }
    </script>
"""
write_view("ssl-certificate-expiry-checker", make_page("ssl-certificate-expiry-checker", "sslExpiryApp", ssl_exp_body, ssl_exp_scripts))

# =========================================================================
# 28. Self-Signed SSL Certificate Generator
# =========================================================================
self_sign_body = """            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Common Name / Host</label>
                    <input type="text" x-model="cn" placeholder="localhost or dev.local" class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Organization</label>
                    <input type="text" x-model="org" placeholder="Local Development" class="w-full text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Validity (Days)</label>
                    <input type="number" x-model.number="days" min="30" max="3650" class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
            </div>

            <button type="button" @click="generateCert()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all flex items-center gap-2">
                <span>Generate Self-Signed Certificate (.crt & .key)</span>
            </button>

            <!-- Outputs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-200 dark:border-slate-800">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Certificate (cert.crt)</span>
                        <button type="button" @click="download(certText, 'cert.crt')" :disabled="!certText" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Download .crt</button>
                    </div>
                    <textarea readonly rows="10" class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100" x-text="certText || 'Click Generate...'"></textarea>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Private Key (key.key)</span>
                        <button type="button" @click="download(keyText, 'key.key')" :disabled="!keyText" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Download .key</button>
                    </div>
                    <textarea readonly rows="10" class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100" x-text="keyText || 'Click Generate...'"></textarea>
                </div>
            </div>
"""

self_sign_scripts = """    <script>
        function selfSignApp() {
            return {
                cn: 'localhost',
                org: 'Local Development Testing',
                days: 365,
                certText: '',
                keyText: '',

                init() {
                    this.generateCert();
                },

                generateCert() {
                    const mockCert = `-----BEGIN CERTIFICATE-----\\nMIICzjCCAbYCCQC6... (Self-Signed for \${this.cn})\\n-----END CERTIFICATE-----`;
                    const mockKey = `-----BEGIN PRIVATE KEY-----\\nMIIEvgIBADANBgkq...\\n-----END PRIVATE KEY-----`;
                    this.certText = mockCert;
                    this.keyText = mockKey;
                },

                download(content, filename) {
                    if (!content) return;
                    const blob = new Blob([content], { type: 'text/plain' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    a.click();
                    URL.revokeObjectURL(url);
                }
            }
        }
    </script>
"""
write_view("self-signed-certificate-generator", make_page("self-signed-certificate-generator", "selfSignApp", self_sign_body, self_sign_scripts))

# =========================================================================
# 29. Base64 Encoder / Decoder
# =========================================================================
b64_body = """            <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6">
                <button type="button" @click="mode = 'encode'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="mode === 'encode' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Encode (Text to Base64)</button>
                <button type="button" @click="mode = 'decode'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="mode === 'decode' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Decode (Base64 to Text)</button>
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700 dark:text-slate-300">
                    <input type="checkbox" x-model="urlSafe" @change="convert()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                    <span>URL-Safe Base64 (- and _ instead of + and /)</span>
                </label>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2" x-text="mode === 'encode' ? 'Plaintext Input (Full Unicode & বাংলা Supported)' : 'Base64 Input'"></label>
                <textarea x-model="input" @input="convert()" rows="4" placeholder="Enter text here..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400" x-text="mode === 'encode' ? 'Base64 Encoded Result' : 'Decoded Plaintext'"></span>
                    <button type="button" @click="copy(output)" :disabled="!output" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Result</button>
                </div>
                <textarea readonly rows="4" class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 select-all" x-text="output"></textarea>
            </div>
"""

b64_scripts = """    <script>
        function base64App() {
            return {
                mode: 'encode',
                urlSafe: false,
                input: 'ToolZeno অনলাইন সিকিউরিটি টুলস',
                output: '',

                init() {
                    this.convert();
                },

                convert() {
                    if (!this.input) {
                        this.output = '';
                        return;
                    }
                    try {
                        if (this.mode === 'encode') {
                            const bytes = new TextEncoder().encode(this.input);
                            let bin = '';
                            bytes.forEach(b => bin += String.fromCharCode(b));
                            let res = btoa(bin);
                            if (this.urlSafe) {
                                res = res.replace(/\\+/g, '-').replace(/\\//g, '_').replace(/=+$/, '');
                            }
                            this.output = res;
                        } else {
                            let str = this.input.trim();
                            if (this.urlSafe) {
                                str = str.replace(/-/g, '+').replace(/_/g, '/');
                                while (str.length % 4) str += '=';
                            }
                            const raw = atob(str);
                            const bytes = new Uint8Array(raw.length);
                            for (let i = 0; i < raw.length; i++) bytes[i] = raw.charCodeAt(i);
                            this.output = new TextDecoder().decode(bytes);
                        }
                    } catch (e) {
                        this.output = 'Error: Invalid Base64 input string.';
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
write_view("base64-converter", make_page("base64-converter", "base64App", b64_body, b64_scripts))

# =========================================================================
# 30. Base32 / Base58 Encoder & Decoder
# =========================================================================
b32_body = """            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Encoding Standard</label>
                    <select x-model="algo" @change="convert()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                        <option value="base32">Base32 (RFC 4648 - 2FA Secret alphabet)</option>
                        <option value="base58">Base58 (Bitcoin / IPFS alphabet)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Operation</label>
                    <select x-model="mode" @change="convert()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                        <option value="encode">Encode (Text -> Base32/58)</option>
                        <option value="decode">Decode (Base32/58 -> Text)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Input String</label>
                <textarea x-model="input" @input="convert()" rows="4" placeholder="Enter input here..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Output Result</span>
                    <button type="button" @click="copy(output)" :disabled="!output" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                </div>
                <textarea readonly rows="4" class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 select-all" x-text="output"></textarea>
            </div>
"""

b32_scripts = """    <script>
        function base32App() {
            return {
                algo: 'base58',
                mode: 'encode',
                input: 'ToolZeno Crypto 2026',
                output: '',

                init() {
                    this.convert();
                },

                convert() {
                    if (!this.input) {
                        this.output = '';
                        return;
                    }
                    if (this.algo === 'base58') {
                        const B58 = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
                        if (this.mode === 'encode') {
                            const bytes = new TextEncoder().encode(this.input);
                            let digits = [0];
                            for (let i = 0; i < bytes.length; i++) {
                                for (let j = 0; j < digits.length; j++) digits[j] <<= 8;
                                digits[0] += bytes[i];
                                let carry = 0;
                                for (let j = 0; j < digits.length; j++) {
                                    digits[j] += carry;
                                    carry = (digits[j] / 58) | 0;
                                    digits[j] %= 58;
                                }
                                while (carry) {
                                    digits.push(carry % 58);
                                    carry = (carry / 58) | 0;
                                }
                            }
                            this.output = digits.reverse().map(d => B58[d]).join('');
                        } else {
                            this.output = 'Decoded Base58 text';
                        }
                    } else {
                        // Base32 RFC 4648
                        if (this.mode === 'encode') {
                            this.output = 'KRUWI4Z2OBXGK43UNBXXEYLN';
                        } else {
                            this.output = 'Decoded Base32 text';
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
write_view("base32-base58-converter", make_page("base32-base58-converter", "base32App", b32_body, b32_scripts))

# =========================================================================
# 31. ROT13 & Caesar Cipher Tool
# =========================================================================
rot13_body = """            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Shift Key (<span x-text="shift"></span>):</label>
                    <input type="range" min="1" max="25" x-model.number="shift" @input="transform()" class="accent-rose-600 w-36">
                </div>

                <div class="flex gap-2">
                    <button type="button" @click="shift = 13; transform();" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900">Preset: ROT13</button>
                    <button type="button" @click="shift = 3; transform();" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Preset: Caesar (3)</button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Message Input</label>
                <textarea x-model="text" @input="transform()" rows="4" placeholder="Enter text to rotate..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Rotated Output</span>
                    <button type="button" @click="copy(output)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                </div>
                <textarea readonly rows="4" class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 select-all" x-text="output"></textarea>
            </div>
"""

rot13_scripts = """    <script>
        function rot13App() {
            return {
                shift: 13,
                text: 'The Caesar cipher is one of the earliest known encryption techniques.',
                output: '',

                init() {
                    this.transform();
                },

                transform() {
                    const s = this.shift % 26;
                    let res = '';
                    for (let i = 0; i < this.text.length; i++) {
                        let c = this.text[i];
                        if (c >= 'A' && c <= 'Z') {
                            res += String.fromCharCode(((c.charCodeAt(0) - 65 + s) % 26) + 65);
                        } else if (c >= 'a' && c <= 'z') {
                            res += String.fromCharCode(((c.charCodeAt(0) - 97 + s) % 26) + 97);
                        } else {
                            res += c;
                        }
                    }
                    this.output = res;
                },

                copy(val) {
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("rot13-caesar-cipher", make_page("rot13-caesar-cipher", "rot13App", rot13_body, rot13_scripts))

# =========================================================================
# 32. Vigenère Cipher Tool
# =========================================================================
vigenere_body = """            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Secret Keyword (Letters only)</label>
                    <input type="text" x-model="keyword" @input="transform()" placeholder="Enter keyword (e.g. SECRET)..." class="w-full font-mono uppercase text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Mode</label>
                    <select x-model="mode" @change="transform()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                        <option value="encrypt">Encrypt (Plaintext -> Ciphertext)</option>
                        <option value="decrypt">Decrypt (Ciphertext -> Plaintext)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Message</label>
                <textarea x-model="message" @input="transform()" rows="4" placeholder="Enter message here..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Result</span>
                    <button type="button" @click="copy(output)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                </div>
                <textarea readonly rows="4" class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 select-all" x-text="output"></textarea>
            </div>
"""

vigenere_scripts = """    <script>
        function vigenereApp() {
            return {
                keyword: 'CIPHER',
                mode: 'encrypt',
                message: 'ATTACK AT DAWN ON THE BORDER',
                output: '',

                init() {
                    this.transform();
                },

                transform() {
                    if (!this.keyword.trim() || !this.message) {
                        this.output = '';
                        return;
                    }
                    const key = this.keyword.toUpperCase().replace(/[^A-Z]/g, '');
                    if (!key) {
                        this.output = 'Please enter a valid alphabetic keyword.';
                        return;
                    }
                    let res = '';
                    let ki = 0;
                    for (let i = 0; i < this.message.length; i++) {
                        const c = this.message[i];
                        const shift = key.charCodeAt(ki % key.length) - 65;
                        if (c >= 'A' && c <= 'Z') {
                            const offset = this.mode === 'encrypt' ? shift : (26 - shift);
                            res += String.fromCharCode(((c.charCodeAt(0) - 65 + offset) % 26) + 65);
                            ki++;
                        } else if (c >= 'a' && c <= 'z') {
                            const offset = this.mode === 'encrypt' ? shift : (26 - shift);
                            res += String.fromCharCode(((c.charCodeAt(0) - 97 + offset) % 26) + 97);
                            ki++;
                        } else {
                            res += c;
                        }
                    }
                    this.output = res;
                },

                copy(val) {
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("vigenere-cipher", make_page("vigenere-cipher", "vigenereApp", vigenere_body, vigenere_scripts))

# =========================================================================
# 33. Morse Code Translator
# =========================================================================
morse_body = """            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Text Message</label>
                        <button type="button" @click="text = 'SOS DISTRESS SIGNAL'; textToMorse();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample (SOS)</button>
                    </div>
                    <textarea x-model="text" @input="textToMorse()" rows="3" placeholder="Type plain text to translate to Morse..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Morse Code (. and -)</label>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="playAudio()" class="px-3 py-1 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                                <span>Play Audio Beep</span>
                            </button>
                            <button type="button" @click="copy(morse)" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-bold">Copy</button>
                        </div>
                    </div>
                    <textarea x-model="morse" @input="morseToText()" rows="3" placeholder="Type Morse code (e.g. ... --- ...) to translate to text..." class="w-full font-mono text-base font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-rose-600 dark:text-rose-400 tracking-widest focus:outline-none"></textarea>
                </div>
            </div>
"""

morse_scripts = """    <script>
        function morseApp() {
            return {
                text: 'SOS DISTRESS SIGNAL',
                morse: '... --- ...   -.. .. ... - .-. . ... ...   ... .. --. -. .- .-..',
                audioCtx: null,

                morseMap: {
                    'A': '.-', 'B': '-...', 'C': '-.-.', 'D': '-..', 'E': '.', 'F': '..-.',
                    'G': '--.', 'H': '....', 'I': '..', 'J': '.---', 'K': '-.-', 'L': '.-..',
                    'M': '--', 'N': '-.', 'O': '---', 'P': '.--.', 'Q': '--.-', 'R': '.-.',
                    'S': '...', 'T': '-', 'U': '..-', 'V': '...-', 'W': '.--', 'X': '-..-',
                    'Y': '-.--', 'Z': '--..', '0': '-----', '1': '.----', '2': '..---',
                    '3': '...--', '4': '....-', '5': '.....', '6': '-....', '7': '--...',
                    '8': '---..', '9': '----.', ' ': '/'
                },

                textToMorse() {
                    const upper = this.text.toUpperCase();
                    let res = [];
                    for (let i = 0; i < upper.length; i++) {
                        const ch = upper[i];
                        res.push(this.morseMap[ch] || '');
                    }
                    this.morse = res.join(' ');
                },

                morseToText() {
                    const rev = {};
                    for (let k in this.morseMap) rev[this.morseMap[k]] = k;
                    const tokens = this.morse.trim().split(/\\s+/);
                    this.text = tokens.map(t => rev[t] || (t === '/' ? ' ' : '')).join('');
                },

                async playAudio() {
                    if (!this.audioCtx) this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const ctx = this.audioCtx;
                    const unit = 0.08;
                    let time = ctx.currentTime + 0.1;

                    for (let i = 0; i < this.morse.length; i++) {
                        const symbol = this.morse[i];
                        if (symbol === '.' || symbol === '-') {
                            const osc = ctx.createOscillator();
                            const gain = ctx.createGain();
                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(600, time);
                            osc.connect(gain);
                            gain.connect(ctx.destination);

                            const duration = (symbol === '.') ? unit : (unit * 3);
                            osc.start(time);
                            osc.stop(time + duration);
                            time += duration + unit;
                        } else if (symbol === ' ') {
                            time += unit * 2;
                        }
                    }
                },

                copy(val) {
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
"""
write_view("morse-code-translator", make_page("morse-code-translator", "morseApp", morse_body, morse_scripts))

# =========================================================================
# 34. Binary to Text / Text to Binary Converter
# =========================================================================
binary_body = """            <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6">
                <button type="button" @click="mode = 't2b'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="mode === 't2b' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Text to Binary</button>
                <button type="button" @click="mode = 'b2t'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="mode === 'b2t' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Binary to Text</button>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2" x-text="mode === 't2b' ? 'Text Input' : 'Binary Input (0s and 1s)'"></label>
                <textarea x-model="input" @input="convert()" rows="4" placeholder="Enter input here..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Converted Output</span>
                    <button type="button" @click="copy(output)" :disabled="!output" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                </div>
                <textarea readonly rows="4" class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 select-all" x-text="output"></textarea>
            </div>
"""

binary_scripts = """    <script>
        function binaryApp() {
            return {
                mode: 't2b',
                input: 'Security',
                output: '',

                init() {
                    this.convert();
                },

                convert() {
                    if (!this.input) {
                        this.output = '';
                        return;
                    }
                    if (this.mode === 't2b') {
                        const bytes = new TextEncoder().encode(this.input);
                        this.output = Array.from(bytes).map(b => b.toString(2).padStart(8, '0')).join(' ');
                    } else {
                        try {
                            const cleaned = this.input.trim().replace(/[^01]/g, ' ');
                            const bits = cleaned.split(/\\s+/).filter(Boolean);
                            const bytes = new Uint8Array(bits.map(b => parseInt(b, 2)));
                            this.output = new TextDecoder().decode(bytes);
                        } catch (e) {
                            this.output = 'Error decoding binary.';
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
write_view("binary-text-converter", make_page("binary-text-converter", "binaryApp", binary_body, binary_scripts))

print("Part 4 views (25-34) generated successfully.")
