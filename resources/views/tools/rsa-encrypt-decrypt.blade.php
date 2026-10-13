<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="rsaEncryptApp()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mt-6 mb-8 text-center sm:text-left">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool->getDescription() }}
            </p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6 mb-10">
            <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6">
                <button type="button" @click="tab = 'encrypt'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="tab === 'encrypt' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Encrypt with Public Key</button>
                <button type="button" @click="tab = 'decrypt'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="tab === 'decrypt' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Decrypt with Private Key</button>
            </div>

            <!-- Encrypt Tab -->
            <div x-show="tab === 'encrypt'" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Recipient's RSA Public Key (PEM)</label>
                    <textarea x-model="publicKeyPem" rows="5" placeholder="Paste -----BEGIN PUBLIC KEY----- here..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Plaintext Message</label>
                    <input type="text" x-model="plainMessage" placeholder="Enter short message to encrypt with RSA..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="encryptMessage()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">Encrypt Message</button>
                    <button type="button" @click="loadSampleKeypair()" class="px-4 py-2 text-xs font-bold rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Load Test Keypair</button>
                </div>

                <div x-show="cipherResult">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Ciphertext (Base64)</span>
                        <button type="button" @click="copy(cipherResult)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all" x-text="cipherResult"></div>
                </div>
            </div>

            <!-- Decrypt Tab -->
            <div x-show="tab === 'decrypt'" class="space-y-4" style="display:none;">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Your RSA Private Key (PEM)</label>
                    <textarea x-model="privateKeyPem" rows="5" placeholder="Paste -----BEGIN PRIVATE KEY----- here..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Ciphertext (Base64)</label>
                    <textarea x-model="cipherInput" rows="3" placeholder="Paste Base64 ciphertext here..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                </div>

                <button type="button" @click="decryptMessage()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">Decrypt Message</button>

                <div x-show="decryptedMessage">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Decrypted Plaintext</span>
                        <button type="button" @click="copy(decryptedMessage)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all" x-text="decryptedMessage"></div>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function rsaEncryptApp() {
            return {
                tab: 'encrypt',
                publicKeyPem: '',
                plainMessage: 'Secret token: 9482-XYZA-8821',
                cipherResult: '',
                privateKeyPem: '',
                cipherInput: '',
                decryptedMessage: '',

                async loadSampleKeypair() {
                    const keyPair = await window.crypto.subtle.generateKey(
                        { name: 'RSA-OAEP', modulusLength: 2048, publicExponent: new Uint8Array([1, 0, 1]), hash: 'SHA-256' },
                        true,
                        ['encrypt', 'decrypt']
                    );
                    const spki = await window.crypto.subtle.exportKey('spki', keyPair.publicKey);
                    this.publicKeyPem = this.toPem(spki, 'PUBLIC KEY');
                    const pkcs8 = await window.crypto.subtle.exportKey('pkcs8', keyPair.privateKey);
                    this.privateKeyPem = this.toPem(pkcs8, 'PRIVATE KEY');
                    this.encryptMessage();
                },

                toPem(buffer, label) {
                    const bytes = new Uint8Array(buffer);
                    let binary = '';
                    for (let i = 0; i < bytes.byteLength; i++) binary += String.fromCharCode(bytes[i]);
                    const b64 = btoa(binary);
                    return `-----BEGIN ${label}-----\n${b64.match(/.{1,64}/g).join('\n')}\n-----END ${label}-----`;
                },

                pemToArrayBuffer(pem) {
                    const lines = pem.trim().split('\n').filter(line => !line.startsWith('-----'));
                    const b64 = lines.join('');
                    const str = atob(b64);
                    const bytes = new Uint8Array(str.length);
                    for (let i = 0; i < str.length; i++) bytes[i] = str.charCodeAt(i);
                    return bytes.buffer;
                },

                async encryptMessage() {
                    if (!this.publicKeyPem || !this.plainMessage) return;
                    try {
                        const buffer = this.pemToArrayBuffer(this.publicKeyPem);
                        const key = await window.crypto.subtle.importKey(
                            'spki',
                            buffer,
                            { name: 'RSA-OAEP', hash: 'SHA-256' },
                            false,
                            ['encrypt']
                        );
                        const data = new TextEncoder().encode(this.plainMessage);
                        const enc = await window.crypto.subtle.encrypt({ name: 'RSA-OAEP' }, key, data);
                        const bArr = new Uint8Array(enc);
                        let bin = '';
                        bArr.forEach(b => bin += String.fromCharCode(b));
                        this.cipherResult = btoa(bin);
                        this.cipherInput = this.cipherResult;
                    } catch (e) {
                        alert('Encryption error: ' + e.message);
                    }
                },

                async decryptMessage() {
                    if (!this.privateKeyPem || !this.cipherInput) return;
                    try {
                        const buffer = this.pemToArrayBuffer(this.privateKeyPem);
                        const key = await window.crypto.subtle.importKey(
                            'pkcs8',
                            buffer,
                            { name: 'RSA-OAEP', hash: 'SHA-256' },
                            false,
                            ['decrypt']
                        );
                        const raw = atob(this.cipherInput);
                        const arr = new Uint8Array(raw.length);
                        for (let i = 0; i < raw.length; i++) arr[i] = raw.charCodeAt(i);
                        const dec = await window.crypto.subtle.decrypt({ name: 'RSA-OAEP' }, key, arr.buffer);
                        this.decryptedMessage = new TextDecoder().decode(dec);
                    } catch (e) {
                        alert('Decryption failed: ' + e.message);
                    }
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>

    @endpush
</x-layouts.app>
