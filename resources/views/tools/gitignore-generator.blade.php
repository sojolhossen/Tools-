<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="gitignoreGenerator()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool->getDescription() }}
            </p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-10">
            <!-- Preset selector pills -->
            <div class="p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-3">Select Technologies & Tools</span>
                <div class="flex flex-wrap gap-2">
                    <template x-for="tech in availableTech" :key="tech.id">
                        <button type="button" @click="toggleTech(tech.id)" :class="selectedTech.includes(tech.id) ? 'bg-primary-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-primary-400'" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5">
                            <span x-text="selectedTech.includes(tech.id) ? '✓' : '+'"></span>
                            <span x-text="tech.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Generated .gitignore preview -->
            <div class="p-5 sm:p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">.gitignore Content</span>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="downloadGitignore()" class="text-xs font-medium text-slate-600 dark:text-slate-400 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-download"></i> Download
                        </button>
                        <button type="button" @click="copyCode()" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1">
                            <i class="fa-regular fa-copy"></i>
                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>
                </div>
                <textarea x-model="output" readonly rows="18" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-4 text-slate-900 dark:text-slate-100 focus:outline-none resize-y"></textarea>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="htaccess-generator" />
    </div>

    @push('scripts')
    <script>
        function gitignoreGenerator() {
            return {
                selectedTech: ['node', 'vscode', 'env'],
                availableTech: [
                    { id: 'node', name: 'Node.js / npm' },
                    { id: 'laravel', name: 'Laravel / PHP' },
                    { id: 'python', name: 'Python' },
                    { id: 'flutter', name: 'Flutter / Dart' },
                    { id: 'react', name: 'React / Next.js' },
                    { id: 'vscode', name: 'Visual Studio Code' },
                    { id: 'jetbrains', name: 'JetBrains (PhpStorm/IntelliJ)' },
                    { id: 'macos', name: 'macOS (.DS_Store)' },
                    { id: 'windows', name: 'Windows (Thumbs.db)' },
                    { id: 'env', name: '.env Secrets' }
                ],
                templates: {
                    node: "# Node.js\nnode_modules/\nnpm-debug.log*\nyarn-debug.log*\nyarn-error.log*\npackage-lock.json\n.pnpm-debug.log*\n",
                    laravel: "# Laravel\n/vendor/\nnode_modules/\npublic/storage\npublic/hot\nstorage/*.key\n.phpunit.result.cache\nHomestead.json\nHomestead.yaml\nauth.json\n",
                    python: "# Python\n__pycache__/\n*.py[cod]\n*$py.class\n*.so\n.Python\nbuild/\ndevelop-eggs/\ndist/\ndownloads/\neggs/\n.eggs/\nlib/\nlib64/\nparts/\nsdist/\nvar/\nwheels/\n*.egg-info/\n.installed.cfg\n*.egg\n.env\n.venv\nenv/\nvenv/\n",
                    flutter: "# Flutter & Dart\n.dart_tool/\n.flutter-plugins\n.flutter-plugins-dependencies\n.packages\n.pub-cache/\n.pub/\nbuild/\n/android/app/debug\n/android/app/profile\n/android/app/release\n",
                    react: "# React & Next.js\n.next/\nout/\nbuild/\n.cache/\n",
                    vscode: "# VS Code\n.vscode/*\n!.vscode/settings.json\n!.vscode/tasks.json\n!.vscode/launch.json\n!.vscode/extensions.json\n*.code-workspace\n",
                    jetbrains: "# JetBrains IDEs\n.idea/\n*.iws\n*.iml\n*.ipr\n",
                    macos: "# macOS System Files\n.DS_Store\n.AppleDouble\n.LSOverride\n._*\n",
                    windows: "# Windows System Files\nThumbs.db\nThumbs.db:encryptable\nehthumbs.db\nehthumbs_vista.db\n*.stackdump\n[Dd]esktop.ini\n",
                    env: "# Environment and Secrets\n.env\n.env.backup\n.env.production\n*.local\n*.pem\n*.key\n"
                },
                output: '',
                copied: false,
                init() {
                    this.buildOutput();
                },
                toggleTech(id) {
                    if (this.selectedTech.includes(id)) {
                        this.selectedTech = this.selectedTech.filter(t => t !== id);
                    } else {
                        this.selectedTech.push(id);
                    }
                    this.buildOutput();
                },
                buildOutput() {
                    let out = "# Created by ToolZeno .gitignore Generator\n# ----------------------------------------\n\n";
                    this.selectedTech.forEach(id => {
                        if (this.templates[id]) {
                            out += this.templates[id] + '\n';
                        }
                    });
                    this.output = out.trim();
                },
                copyCode() {
                    navigator.clipboard.writeText(this.output).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                },
                downloadGitignore() {
                    const blob = new Blob([this.output], { type: 'text/plain' });
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = '.gitignore';
                    a.click();
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
