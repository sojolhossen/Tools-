<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $categoriesData = [
            'developer' => [
                'name_en' => 'Developer Tools',
                'name_bn' => 'ডেভেলপার টুলস',
                'description_en' => 'Formatters, validators, and essential utilities for programmers.',
                'description_bn' => 'প্রোগ্রামারদের জন্য ফরম্যাটার, ভ্যালিডেটর এবং প্রয়োজনীয় ইউটিলিটি।',
                'icon' => 'code-bracket',
                'color' => 'amber',
                'sort_order' => 1,
                'is_active' => true,
            ],
            'text' => [
                'name_en' => 'Text & Content Tools',
                'name_bn' => 'টেক্সট ও কনটেন্ট টুলস',
                'description_en' => 'Tools for counting, formatting, and converting text instantly.',
                'description_bn' => 'টেক্সট গণনা, ফরম্যাটিং এবং কনভার্ট করার কার্যকরী টুলস।',
                'icon' => 'document-text',
                'color' => 'indigo',
                'sort_order' => 2,
                'is_active' => true,
            ],
            'image' => [
                'name_en' => 'Image & Media Tools',
                'name_bn' => 'ইমেজ ও মিডিয়া টুলস',
                'description_en' => 'Compress, resize, and convert images 100% in your browser.',
                'description_bn' => 'ব্রাউজারেই নিরাপদে ছবি কম্প্রেস, রিসাইজ এবং কনভার্ট করুন।',
                'icon' => 'photo',
                'color' => 'violet',
                'sort_order' => 3,
                'is_active' => true,
            ],
            'calculators' => [
                'name_en' => 'Calculators',
                'name_bn' => 'ক্যালকুলেটর',
                'description_en' => 'Accurate calculators for percentages, discounts, and finances.',
                'description_bn' => 'শতকরা, ছাড় এবং আর্থিক হিসেবের নির্ভুল ক্যালকুলেটর।',
                'icon' => 'calculator',
                'color' => 'emerald',
                'sort_order' => 4,
                'is_active' => true,
            ],
            'unit-converters' => [
                'name_en' => 'Unit Converters',
                'name_bn' => 'ইউনিট কনভার্টার',
                'description_en' => 'Convert units of length, weight, data storage, speed, and temperature.',
                'description_bn' => 'দৈর্ঘ্য, ওজন, ডিজিটাল স্টোরেজ, গতি এবং তাপমাত্রার ইউনিট সহজে রূপান্তর করুন।',
                'icon' => 'scale',
                'color' => 'cyan',
                'sort_order' => 5,
                'is_active' => true,
            ],
            'finance' => [
                'name_en' => 'Finance & Investment Tools',
                'name_bn' => 'ফাইন্যান্স ও বিনিয়োগ টুলস',
                'description_en' => 'Calculate EMI, compound interest, profit margins, VAT/tax, and live currencies.',
                'description_bn' => 'ইএমআই, চক্রবৃদ্ধি সুদ, মুনাফার মার্জিন, ভ্যাট/ট্যাক্স এবং মুদ্রা রূপান্তর হিসাব করুন।',
                'icon' => 'banknotes',
                'color' => 'green',
                'sort_order' => 6,
                'is_active' => true,
            ],
            'security' => [
                'name_en' => 'Security & Cryptography',
                'name_bn' => 'সিকিউরিটি ও ক্রিপ্টোগ্রাফি',
                'description_en' => 'Generate strong passwords, create MD5/SHA hashes, and encrypt text.',
                'description_bn' => 'শক্তিশালী পাসওয়ার্ড তৈরি, MD5/SHA হ্যাশ জেনারেশন এবং টেক্সট এনক্রিপ্ট করুন।',
                'icon' => 'shield-check',
                'color' => 'rose',
                'sort_order' => 7,
                'is_active' => true,
            ],
            'network' => [
                'name_en' => 'Network & Webmaster Tools',
                'name_bn' => 'নেটওয়ার্ক ও ওয়েবমাস্টার টুলস',
                'description_en' => 'Check your IP, DNS records, WHOIS lookup, user-agent details, and ping servers.',
                'description_bn' => 'নিজের আইপি চেক, ডিএনএস রেকর্ড, WHOIS অনুসন্ধান, ইউজার-এজেন্ট ও পিং টেস্ট করুন।',
                'icon' => 'globe',
                'color' => 'blue',
                'sort_order' => 8,
                'is_active' => true,
            ],
            'seo' => [
                'name_en' => 'SEO & Social Media Tools',
                'name_bn' => 'এসইও ও সোশ্যাল মিডিয়া টুলস',
                'description_en' => 'Generate meta tags, preview Open Graph social cards, make slugs, and check keyword density.',
                'description_bn' => 'মেটা ট্যাগ তৈরি, সোশ্যাল মিডিয়া কার্ড প্রিভিউ, ইউআরএল স্ল্যাগ ও কি-ওয়ার্ড ঘনত্ব বিশ্লেষণ করুন।',
                'icon' => 'chart-bar',
                'color' => 'purple',
                'sort_order' => 9,
                'is_active' => true,
            ],
            'color-design' => [
                'name_en' => 'Color & UI/UX Tools',
                'name_bn' => 'কালার ও ইউআই/ইউএক্স টুলস',
                'description_en' => 'Pick colors, test WCAG contrast, convert HEX/RGB/HSL, and craft CSS gradients.',
                'description_bn' => 'কালার পিকার, কনট্রাস্ট চেকার, HEX/RGB/HSL রূপান্তর এবং চমৎকার গ্রেডিয়েন্ট তৈরি করুন।',
                'icon' => 'swatch',
                'color' => 'pink',
                'sort_order' => 10,
                'is_active' => true,
            ],
            'pdf' => [
                'name_en' => 'PDF Tools',
                'name_bn' => 'পিডিএফ টুলস',
                'description_en' => 'Convert, merge, and organize PDF documents with privacy.',
                'description_bn' => 'ব্যক্তিগত গোপনীয়তা বজায় রেখে পিডিএফ ডকুমেন্ট কনভার্ট করুন।',
                'icon' => 'document-duplicate',
                'color' => 'red',
                'sort_order' => 11,
                'is_active' => true,
            ],
            'mobile-dev' => [
                'name_en' => 'Mobile App Developer Tools',
                'name_bn' => 'মোবাইল অ্যাপ ডেভেলপার টুলস',
                'description_en' => 'Resize app icon sets, convert vector drawables, test deep links, and inspect keystores.',
                'description_bn' => 'মোবাইল অ্যাপ আইকন রিসাইজ, অ্যান্ড্রয়েড ভেক্টর কনভার্ট এবং ডিপ লিংক টেস্ট করুন।',
                'icon' => 'device-phone-mobile',
                'color' => 'teal',
                'sort_order' => 12,
                'is_active' => true,
            ],
            'database' => [
                'name_en' => 'Database & Data Modeling',
                'name_bn' => 'ডাটাবেস ও ডাটা মডেলিং',
                'description_en' => 'Format SQL queries, convert between CSV and JSON, and generate realistic mock datasets.',
                'description_bn' => 'SQL কুয়েরি ফরম্যাট, CSV থেকে JSON রূপান্তর এবং ডামি মক ডাটা জেনারেট করুন।',
                'icon' => 'circle-stack',
                'color' => 'orange',
                'sort_order' => 13,
                'is_active' => true,
            ],
            'audio-video' => [
                'name_en' => 'Audio & Video Tools',
                'name_bn' => 'অডিও ও ভিডিও টুলস',
                'description_en' => 'Trim audio, convert video clips to GIF, and compute screen aspect ratios.',
                'description_bn' => 'অডিও ট্রিম, ভিডিও থেকে অ্যানিমেটেড জিআইএফ এবং অ্যাসপেক্ট রেশিও হিসাব করুন।',
                'icon' => 'video-camera',
                'color' => 'fuchsia',
                'sort_order' => 14,
                'is_active' => true,
            ],
            'file-archive' => [
                'name_en' => 'File & Archive Tools',
                'name_bn' => 'ফাইল ও আর্কাইভ টুলস',
                'description_en' => 'Extract and create ZIP archives, compute checksums, and preview base64 files.',
                'description_bn' => 'জিপ (ZIP) আর্কাইভ তৈরি ও আনজিপ, ফাইলের চেকসাম এবং Base64 ফাইল প্রিভিউ করুন।',
                'icon' => 'archive-box',
                'color' => 'yellow',
                'sort_order' => 15,
                'is_active' => true,
            ],
            'productivity' => [
                'name_en' => 'Time & Productivity Tools',
                'name_bn' => 'টাইম ও প্রোডাক্টিভিটি টুলস',
                'description_en' => 'Smart utilities to streamline daily digital workflow and tasks.',
                'description_bn' => 'দৈনন্দিন ডিজিটাল কাজের গতি বাড়ানোর স্মার্ট ইউটিলিটি।',
                'icon' => 'clock',
                'color' => 'sky',
                'sort_order' => 16,
                'is_active' => true,
            ],
            'education' => [
                'name_en' => 'Student & Education Tools',
                'name_bn' => 'স্টুডেন্ট ও এডুকেশন টুলস',
                'description_en' => 'Calculate GPA/CGPA, generate citations (APA/MLA), and convert Roman numerals.',
                'description_bn' => 'জিপিএ/সিজিপিএ ক্যালকুলেটর, রেফারেন্স সাইটেশন এবং রোমান সংখ্যা রূপান্তর করুন।',
                'icon' => 'academic-cap',
                'color' => 'lime',
                'sort_order' => 17,
                'is_active' => true,
            ],
            'ecommerce' => [
                'name_en' => 'eCommerce & Business Tools',
                'name_bn' => 'ই-কমার্স ও বিজনেস টুলস',
                'description_en' => 'Create invoices, generate barcodes, create SKU codes, and compute trade discounts.',
                'description_bn' => 'ইনভয়েস তৈরি, বারকোড জেনারেটর, এসকেইউ কোড মেকার এবং ডিসকাউন্ট হিসাব করুন।',
                'icon' => 'shopping-cart',
                'color' => 'amber',
                'sort_order' => 18,
                'is_active' => true,
            ],
            'generators' => [
                'name_en' => 'Generators & Utilities',
                'name_bn' => 'জেনারেটর ও ইউটিলিটিজ',
                'description_en' => 'Generate custom QR codes, RFC-compliant UUID/GUIDs, and true random numbers.',
                'description_bn' => 'কাস্টম কিউআর কোড, UUID/GUID কোড এবং র্যান্ডম নাম্বার/লটারি পিকার জেনারেট করুন।',
                'icon' => 'sparkles',
                'color' => 'indigo',
                'sort_order' => 19,
                'is_active' => true,
            ],
            'ai-tools' => [
                'name_en' => 'AI & Prompt Utilities',
                'name_bn' => 'এআই ও প্রম্পট ইউটিলিটিজ',
                'description_en' => 'Count LLM tokens (GPT/Claude), format and clean prompts, and check prompt length.',
                'description_bn' => 'এআই টোকেন কাউন্টার, এআই প্রম্পট ফরম্যাটার ও দৈর্ঘ্য নিখুঁতভাবে চেক করুন।',
                'icon' => 'cpu-chip',
                'color' => 'violet',
                'sort_order' => 20,
                'is_active' => true,
            ],
        ];

        foreach ($categoriesData as $slug => $data) {
            Category::updateOrCreate(['slug' => $slug], $data);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep categories safe
    }
};
