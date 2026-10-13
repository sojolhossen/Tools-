<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="dummyJsonTool()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mt-6 mb-8 text-center sm:text-left">
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
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool->getShortDescription() }}
            </p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-10">
            <!-- Controls toolbar -->
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-4 text-xs">
                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300 mr-2">Schema Type:</label>
                        <select x-model="schemaType" @change="generate()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-1 text-slate-900 dark:text-slate-100">
                            <option value="users">Users / Profiles</option>
                            <option value="products">E-Commerce Products</option>
                            <option value="posts">Blog Posts / Articles</option>
                            <option value="orders">Orders & Transactions</option>
                        </select>
                    </div>
                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300 mr-2">Records:</label>
                        <select x-model.number="count" @change="generate()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-1 text-slate-900 dark:text-slate-100">
                            <option value="3">3</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="generate()" class="px-4 py-2 rounded-lg text-xs font-bold bg-primary-600 hover:bg-primary-700 text-white shadow-sm transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-arrows-rotate"></i> Regenerate
                    </button>
                    <button type="button" @click="copyOutput()" class="px-3 py-2 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors flex items-center gap-1">
                        <i class="fa-regular fa-copy"></i>
                        <span x-text="copied ? 'Copied!' : 'Copy JSON'"></span>
                    </button>
                </div>
            </div>

            <!-- JSON Output view -->
            <div class="p-5 sm:p-6">
                <textarea x-model="jsonOutput" readonly rows="18" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-4 text-slate-900 dark:text-slate-100 focus:outline-none resize-y"></textarea>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="json-formatter" />
    </div>

    @push('scripts')
    <script>
        function dummyJsonTool() {
            return {
                schemaType: 'users',
                count: 5,
                jsonOutput: '',
                copied: false,
                init() {
                    this.generate();
                },
                generate() {
                    const firstNames = ['James', 'Emma', 'Liam', 'Olivia', 'Noah', 'Ava', 'Ethan', 'Sophia', 'Lucas', 'Mia'];
                    const lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Wilson', 'Anderson', 'Taylor'];
                    const cities = ['New York', 'London', 'Tokyo', 'San Francisco', 'Toronto', 'Sydney', 'Berlin', 'Paris'];
                    const items = [];

                    for (let i = 1; i <= this.count; i++) {
                        const fn = firstNames[Math.floor(Math.random() * firstNames.length)];
                        const ln = lastNames[Math.floor(Math.random() * lastNames.length)];
                        const city = cities[Math.floor(Math.random() * cities.length)];

                        if (this.schemaType === 'users') {
                            items.push({
                                id: i,
                                name: `${fn} ${ln}`,
                                email: `${fn.toLowerCase()}.${ln.toLowerCase()}@example.com`,
                                role: i === 1 ? 'admin' : 'member',
                                active: Math.random() > 0.2,
                                address: {
                                    city: city,
                                    zipCode: 10000 + Math.floor(Math.random() * 80000)
                                },
                                createdAt: new Date(Date.now() - Math.floor(Math.random() * 10000000000)).toISOString()
                            });
                        } else if (this.schemaType === 'products') {
                            const cats = ['Electronics', 'Home & Kitchen', 'Footwear', 'Apparel', 'Accessories'];
                            items.push({
                                id: 100 + i,
                                title: `Premium ${cats[i % cats.length]} Item ${i}`,
                                category: cats[i % cats.length],
                                price: parseFloat((19.99 + Math.random() * 200).toFixed(2)),
                                rating: parseFloat((3.5 + Math.random() * 1.5).toFixed(1)),
                                inStock: Math.random() > 0.15,
                                inventoryCount: Math.floor(Math.random() * 150)
                            });
                        } else if (this.schemaType === 'posts') {
                            items.push({
                                id: i,
                                title: `How to build modern web tools part ${i}`,
                                slug: `modern-web-tools-part-${i}`,
                                author: `${fn} ${ln}`,
                                views: Math.floor(Math.random() * 5000),
                                published: true,
                                tags: ['development', 'javascript', 'laravel'],
                                publishedAt: new Date(Date.now() - Math.floor(Math.random() * 5000000000)).toISOString()
                            });
                        } else if (this.schemaType === 'orders') {
                            items.push({
                                orderId: 'ORD-' + (5000 + i),
                                customerName: `${fn} ${ln}`,
                                amount: parseFloat((49.00 + Math.random() * 450).toFixed(2)),
                                currency: 'USD',
                                status: ['pending', 'processing', 'completed', 'shipped'][Math.floor(Math.random() * 4)],
                                itemsCount: Math.floor(Math.random() * 5) + 1,
                                date: new Date().toISOString()
                            });
                        }
                    }

                    this.jsonOutput = JSON.stringify(items, null, 2);
                },
                copyOutput() {
                    navigator.clipboard.writeText(this.jsonOutput).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
