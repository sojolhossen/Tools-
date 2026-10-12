<x-layouts.app
    :title="__('pages.contact.meta_title')"
    :description="__('pages.contact.meta_desc')"
>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <x-breadcrumbs :items="[
            __('pages.contact.title') => null,
        ]" />

        <div class="space-y-10">
            <!-- Header -->
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                    {{ __('pages.contact.title') }}
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-1">
                    {{ __('pages.contact.title') }}
                </h1>
                <p class="mt-3 text-lg text-slate-600 leading-relaxed">
                    {{ __('pages.contact.subtitle') }}
                </p>
            </div>

            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                <!-- Contact Form -->
                <div class="md:col-span-7 bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
                    <form method="POST" action="{{ route('pages.contact.submit', ['locale' => $locale]) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="contact-name" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                                {{ __('pages.contact.name_label') }}
                            </label>
                            <input
                                id="contact-name"
                                type="text"
                                name="name"
                                required
                                value="{{ old('name') }}"
                                placeholder="{{ __('pages.contact.name_placeholder') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600"
                            >
                            @error('name')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact-email" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                                {{ __('pages.contact.email_label') }}
                            </label>
                            <input
                                id="contact-email"
                                type="email"
                                name="email"
                                required
                                value="{{ old('email') }}"
                                placeholder="{{ __('pages.contact.email_placeholder') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600"
                            >
                            @error('email')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact-subject" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                                {{ __('pages.contact.subject_label') }}
                            </label>
                            <input
                                id="contact-subject"
                                type="text"
                                name="subject"
                                required
                                value="{{ old('subject') }}"
                                placeholder="{{ __('pages.contact.subject_placeholder') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600"
                            >
                            @error('subject')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact-message" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                                {{ __('pages.contact.message_label') }}
                            </label>
                            <textarea
                                id="contact-message"
                                name="message"
                                rows="5"
                                required
                                placeholder="{{ __('pages.contact.message_placeholder') }}"
                                class="w-full p-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600"
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="w-full py-3 px-6 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-colors shadow-2xs focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        >
                            {{ __('pages.contact.submit') }}
                        </button>
                    </form>
                </div>

                <!-- Info Sidebar -->
                <div class="md:col-span-5 space-y-6">
                    <div class="p-6 rounded-3xl bg-slate-100/70 border border-slate-200/80 space-y-4">
                        <h3 class="text-base font-bold text-slate-900">
                            {{ __('pages.contact.info_title') }}
                        </h3>

                        <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-indigo-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                <span>{{ __('pages.contact.info_email') }}</span>
                            </div>

                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-indigo-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                <span>{{ __('pages.contact.info_location') }}</span>
                            </div>

                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-indigo-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span>{{ __('pages.contact.info_hours') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
