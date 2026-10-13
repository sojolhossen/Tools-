# -*- coding: utf-8 -*-
import os

BASE_DIR = r"d:\xampp\htdocs\tools"

# 34 Security Tools Specifications
TOOLS = [
    # 1. Password & Credential Tools
    {
        "slug": "password-generator",
        "icon": "shield-check",
        "tags": ["password generator", "strong password", "random password", "secure password", "passcode creator"],
        "name_en": "Strong Password Generator",
        "name_bn": "স্ট্রং পাসওয়ার্ড জেনারেটর",
        "short_desc_en": "Generate highly secure, cryptographically random passwords with custom length, character sets, and symbols.",
        "short_desc_bn": "কাস্টম দৈর্ঘ্য, বড়-ছোট হাতের অক্ষর, সংখ্যা ও স্পেশাল ক্যারেক্টার দিয়ে অতি নিরাপদ ও রেন্ডম পাসওয়ার্ড তৈরি করুন।",
        "meta_title_en": "Strong Password Generator — Free & Secure Online Tool | ToolZeno",
        "meta_title_bn": "স্ট্রং পাসওয়ার্ড জেনারেটর — নিরাপদ ও ফ্রি অনলাইন পাসওয়ার্ড মেকার | ToolZeno",
        "meta_desc_en": "Create unbreakable, high-entropy passwords directly in your browser. Customize uppercase, lowercase, numbers, and symbols. 100% private.",
        "meta_desc_bn": "ব্রাউজারে সম্পূর্ণ বিনামূল্যে শক্তিশালী ও অনির্ভেদ্য পাসওয়ার্ড জেনারেট করুন। দৈর্ঘ্য ও বিভিন্ন ক্যারেক্টার ফিল্টার করার সুবিধা। শতভাগ নিরাপদ।",
        "how_to_steps_en": [
            "Adjust the password length slider to your desired character count (recommended: 16+ characters).",
            "Toggle character types: Uppercase, Lowercase, Numbers, and Special Symbols.",
            "Choose whether to exclude ambiguous characters (like 1, l, 0, O) for easier typing.",
            "Click 'Generate New Password' or select bulk count to generate multiple passwords at once.",
            "Click 'Copy' to securely copy the generated password to your clipboard."
        ],
        "how_to_steps_bn": [
            "পাসওয়ার্ডের দৈর্ঘ্য স্লাইডার টেনে আপনার পছন্দের সংখ্যায় সেট করুন (কমপক্ষে ১৬ ক্যারেক্টার বাঞ্ছনীয়)।",
            "বড় হাতের অক্ষর, ছোট হাতের অক্ষর, সংখ্যা এবং স্পেশাল চিহ্ন অন বা অফ করুন।",
            "টাইপ করার সুবিধার্থে অস্পষ্ট অক্ষর (যেমন 1, l, 0, O) বাদ দেওয়ার অপশন নির্বাচন করতে পারেন।",
            "'Generate New Password' বাটনে ক্লিক করুন অথবা একাধিক পাসওয়ার্ড তৈরি করতে বাল্ক কাউন্ট বাড়ান।",
            "ক্লিপবোর্ডে কপি করতে 'Copy' বাটনে ক্লিক করুন।"
        ],
        "specs_en": {
            "Entropy Engine": "Web Crypto API window.crypto.getRandomValues (CSPRNG)",
            "Length Range": "6 to 64 characters",
            "Character Pool": "Up to 94 printable ASCII characters",
            "Server Transmission": "0 bytes — 100% generated in client memory"
        },
        "specs_bn": {
            "এন্ট্রপি ইঞ্জিন": "ওয়েব ক্রিপ্টো API window.crypto.getRandomValues (CSPRNG)",
            "দৈর্ঘ্যের পরিধি": "৬ থেকে ৬৪ ক্যারেক্টার",
            "ক্যারেক্টার পুল": "সর্বোচ্চ ৯৪টি প্রিন্টযোগ্য ASCII ক্যারেক্টার",
            "সার্ভার ট্রান্সমিশন": "০ বাইট — ১০০% ক্লায়েন্ট মেমরিতে জেনারেটেড"
        },
        "faq_en": [
            {"q": "Are the generated passwords stored on your server?", "a": "Never. All passwords are generated purely in your browser using the cryptographically secure random number generator (CSPRNG) of the Web Crypto API. Nothing is sent to our servers."},
            {"q": "What makes a password strong?", "a": "Length is the most critical factor (16+ characters), followed by a mix of uppercase, lowercase, numbers, and symbols, creating high entropy that resists brute-force attacks."}
        ],
        "faq_bn": [
            {"q": "জেনারেট করা পাসওয়ার্ড কি আপনাদের সার্ভারে সংরক্ষিত হয়?", "a": "কখনই না। পাসওয়ার্ড সম্পূর্ণভাবে আপনার ব্রাউজারের নিজস্ব ক্রিপ্টোগ্রাফিক রেন্ডম ইঞ্জিন (Web Crypto API) দিয়ে তৈরি হয়। কোনো তথ্যই সার্ভারে পাঠানো হয় না।"},
            {"q": "একটি পাসওয়ার্ডকে কী শক্তিশালী করে?", "a": "দৈর্ঘ্য হলো সবচেয়ে গুরুত্বপূর্ণ উপাদান (১৬ বা তার বেশি ক্যারেক্টার), সাথে বড়-ছোট অক্ষর, সংখ্যা ও প্রতীকের সংমিশ্রণ যা ব্রুট-ফোর্স আক্রমণ প্রতিরোধ করে।"}
        ]
    },
    {
        "slug": "password-strength-checker",
        "icon": "shield-check",
        "tags": ["password strength checker", "password entropy", "crack time calculator", "brute force estimate"],
        "name_en": "Password Strength & Entropy Checker",
        "name_bn": "পাসওয়ার্ড স্ট্রেন্থ ও এন্ট্রপি চেকার",
        "short_desc_en": "Analyze password complexity, entropy score in bits, and estimated time to crack with brute-force attacks.",
        "short_desc_bn": "পাসওয়ার্ডের জটিলতা, এন্ট্রপি (বিটস) স্কোর এবং ব্রুট-ফোর্স অ্যাটাকে ক্র্যাক হতে কত সময় লাগবে তা পরীক্ষা করুন।",
        "meta_title_en": "Password Strength & Entropy Checker — Crack Time Calculator | ToolZeno",
        "meta_title_bn": "পাসওয়ার্ড স্ট্রেন্থ ও এন্ট্রপি চেকার — ক্র্যাক টাইম ক্যালকুলেটর | ToolZeno",
        "meta_desc_en": "Check password entropy, complexity score, and estimated crack time for free. 100% browser-based with zero network transmission.",
        "meta_desc_bn": "পাসওয়ার্ডের নিরাপত্তা ও এন্ট্রপি স্কোর রিয়েল-টাইমে যাচাই করুন। সম্পূর্ণ ব্রাউজার সাইডে নিরাপদ অ্যানালাইসিস।",
        "how_to_steps_en": [
            "Type or paste your password into the inspection field.",
            "View live entropy calculation (bits), security tier, and estimated brute force crack time.",
            "Review the checklist of security requirements (length, uppercase, lowercase, numbers, symbols).",
            "Adjust your password to reach 'Strong' or 'Very Strong' rating."
        ],
        "how_to_steps_bn": [
            "ইনপুট বক্সে আপনার পাসওয়ার্ড টাইপ বা পেস্ট করুন।",
            "রিয়েল-টাইমে এন্ট্রপি (বিটস), সিকিউরিটি গ্রেড এবং ক্র্যাক হতে আনুমানিক সময় দেখুন।",
            "নিরাপত্তা চেকলিস্টটি (দৈর্ঘ্য, বড়-ছোট অক্ষর, সংখ্যা, স্পেশাল চিহ্ন) পর্যালোচনা করুন।",
            "পাসওয়ার্ডটি পরিবর্তন করে 'Strong' বা 'Very Strong' লেভেলে নিয়ে আসুন।"
        ],
        "specs_en": {
            "Entropy Formula": "E = L × log2(R) where R is the character set size",
            "GPU Benchmark": "10 Billion guesses/second (Hashcat RTX 4090 standard)",
            "Online Benchmark": "1,000 guesses/second (throttled login API)",
            "Privacy": "100% Client-Side. Input never leaves browser memory."
        },
        "specs_bn": {
            "এন্ট্রপি সূত্র": "E = L × log2(R) যেখানে R হলো ক্যারেক্টার সেটের আকার",
            "GPU বেঞ্চমার্ক": "প্রতি সেকেন্ডে ১০ বিলিয়ন গেস (Hashcat RTX 4090 স্ট্যান্ডার্ড)",
            "অনলাইন বেঞ্চমার্ক": "প্রতি সেকেন্ডে ১,০০০ গেস (API লগইন লিমিট)",
            "প্রাইভেসি": "১০০% ক্লায়েন্ট-সাইড। কোনো ডাটা সার্ভারে যায় না।"
        },
        "faq_en": [
            {"q": "Is it safe to test my real password here?", "a": "Yes. This tool runs 100% inside your browser using client-side JavaScript. No network requests are made, and your input is immediately cleared upon closing the page."},
            {"q": "What is password entropy?", "a": "Entropy is a mathematical measure in bits of how unpredictable and difficult a password is to guess. Passwords with over 80 bits of entropy are virtually impossible to crack with modern supercomputers."}
        ],
        "faq_bn": [
            {"q": "এখানে আমার আসল পাসওয়ার্ড টেস্ট করা কি নিরাপদ?", "a": "হ্যাঁ, সম্পূর্ণ নিরাপদ। এই টুলটি শতভাগ আপনার ব্রাউজারের অভ্যন্তরে রান হয়। কোনো ডাটা ইন্টারনেটে বা সার্ভারে পাঠানো হয় না।"},
            {"q": "পাসওয়ার্ড এন্ট্রপি কী?", "a": "এন্ট্রপি হলো গাণিতিক পরিমাপ (বিটস) যা নির্দেশ করে পাসওয়ার্ডটি অনুমান করা কতটা কঠিন। ৮০ বিটের বেশি এন্ট্রপির পাসওয়ার্ড সুপারকম্পিউটার দিয়েও ক্র্যাক করা প্রায় অসম্ভব।"}
        ]
    },
    {
        "slug": "passphrase-generator",
        "icon": "shield-check",
        "tags": ["passphrase generator", "diceware generator", "memorable password", "correct horse battery staple"],
        "name_en": "Passphrase Generator (Diceware Style)",
        "name_bn": "পাসফ্রেজ জেনারেটর (ডাইসওয়্যার স্টাইল)",
        "short_desc_en": "Generate memorable yet mathematically unbreakable multi-word passphrases like 'correct-horse-battery-staple'.",
        "short_desc_bn": "ডাইসওয়্যার স্টাইলে সহজে মনে রাখার মতো কিন্তু ক্র্যাক করা অসম্ভব শক্তিশালী পাসফ্রেজ তৈরি করুন।",
        "meta_title_en": "Memorable Passphrase Generator — Diceware Password Maker | ToolZeno",
        "meta_title_bn": "পাসফ্রেজ জেনারেটর — সহজে মনে রাখার মতো শক্তিশালী পাসওয়ার্ড মেকার | ToolZeno",
        "meta_desc_en": "Create high-entropy memorable passphrases with custom word counts, separators, and capitalization. 100% private.",
        "meta_desc_bn": "শব্দ মিলিয়ে সহজে মনে রাখার মতো অতি শক্তিশালী পাসফ্রেজ তৈরি করুন। সম্পূর্ণ ক্লায়েন্ট-সাইডে সুরক্ষিত।",
        "how_to_steps_en": [
            "Choose the number of words (typically 4 to 6 words).",
            "Select your separator (hyphen '-', underscore '_', space, or dot '.').",
            "Optionally toggle Capitalize Words, Append Random Number, or Append Symbol.",
            "Click 'Generate Passphrase' to instantly produce a new high-entropy phrase.",
            "Click 'Copy' to copy the phrase to your clipboard."
        ],
        "how_to_steps_bn": [
            "শব্দের সংখ্যা নির্বাচন করুন (সাধারণত ৪ থেকে ৬টি শব্দ উপযুক্ত)।",
            "বিভাজন চিহ্ন বা সেপারেটর বেছে নিন (যেমন '-', '_', স্পেস বা '.')।",
            "প্রতিটি শব্দের প্রথম অক্ষর বড় হাতের করা বা শেষে সংখ্যা যোগ করার অপশন চালু করতে পারেন।",
            "'Generate Passphrase' বাটনে ক্লিক করে নতুন পাসফ্রেজ তৈরি করুন।",
            "'Copy' বাটনে ক্লিক করে ক্লিপবোর্ডে কপি করে নিন।"
        ],
        "specs_en": {
            "Dictionary Pool": "EFF Long Wordlist & Cryptographic Diceware list",
            "Entropy per Word": "~12.9 bits per random word",
            "PRNG Method": "crypto.getRandomValues",
            "Default Pattern": "4 words separated by hyphens"
        },
        "specs_bn": {
            "ডিকশনারি পুল": "EFF লং ওয়ার্ডলিস্ট ও ক্রিপ্টোগ্রাফিক ডাইসওয়্যার তালিকা",
            "প্রতি শব্দের এন্ট্রপি": "প্রতি শব্দে প্রায় ১২.৯ বিট এন্ট্রপি",
            "PRNG পদ্ধতি": "crypto.getRandomValues",
            "ডিফল্ট প্যাটার্ন": "হাইফেন দিয়ে যুক্ত ৪টি রেন্ডম শব্দ"
        },
        "faq_en": [
            {"q": "Why are passphrases better than traditional passwords?", "a": "Passphrases use longer strings of words, providing superior entropy against brute-force attacks while remaining far easier for humans to remember and type on mobile keyboards."},
            {"q": "How many words should I use?", "a": "4 words offer excellent security for standard accounts (~52 bits of entropy), while 5 or 6 words provide military-grade security (>77 bits) for master passwords and password managers."}
        ],
        "faq_bn": [
            {"q": "সাধারণ পাসওয়ার্ডের চেয়ে পাসফ্রেজ কেন ভালো?", "a": "পাসফ্রেজে একাধিক পূর্ণ শব্দ ব্যবহৃত হওয়ায় এটি অত্যন্ত উচ্চ এন্ট্রপি প্রদান করে যা ক্র্যাক করা অসম্ভব, অথচ মানুষের জন্য মনে রাখা ও টাইপ করা খুবই সহজ।"},
            {"q": "আমার কয়টি শব্দ ব্যবহার করা উচিত?", "a": "৪টি শব্দ সাধারণ অ্যাকাউন্টের জন্য চমৎকার নিরাপত্তা দেয়, আর ৫ বা ৬টি শব্দ মাস্টার পাসওয়ার্ডের জন্য মিলিটারি-গ্রেড নিরাপত্তা নিশ্চিত করে।"}
        ]
    },
    {
        "slug": "pin-code-generator",
        "icon": "shield-check",
        "tags": ["pin code generator", "random pin generator", "atm pin", "4 digit pin", "6 digit pin"],
        "name_en": "PIN Code Generator",
        "name_bn": "পিন কোড জেনারেটর",
        "short_desc_en": "Generate cryptographically secure 4, 6, 8, or custom digit PIN codes for ATMs, mobile apps, and device locks.",
        "short_desc_bn": "এটিএম, মোবাইল অ্যাপ এবং ডিভাইস লকের জন্য ক্রিপ্টোগ্রাফিক্যালি নিরাপদ ৪, ৬ বা ৮ ডিজিটের পিন কোড তৈরি করুন।",
        "meta_title_en": "Secure PIN Code Generator — 4, 6, 8 Digit Random PINs | ToolZeno",
        "meta_title_bn": "সিকিউর পিন কোড জেনারেটর — ৪, ৬, ৮ ডিজিটের রেন্ডম পিন | ToolZeno",
        "meta_desc_en": "Generate truly random 4-digit, 6-digit, and custom-length PIN codes using cryptographic randomness. Filter out easy sequences. Free & secure.",
        "meta_desc_bn": "৪ ও ৬ ডিজিটের ক্রিপ্টোগ্রাফিক রেন্ডম পিন কোড তৈরি করুন। সহজ বা অনুমানযোগ্য সিকোয়েন্স ফিল্টার করার সুবিধাসহ।",
        "how_to_steps_en": [
            "Select your desired PIN length (4 digits for ATM/Debit cards, 6 digits for Apple/Android passcodes, or custom).",
            "Choose quantity (generate 1, 5, 10, or 20 PINs at once).",
            "Optionally enable 'Avoid Repeating Digits' or 'Avoid Sequential Sequences' (e.g. 1234, 0000).",
            "Click 'Generate PINs' to produce fresh cryptographically random codes.",
            "Click 'Copy' next to any PIN or 'Copy All'."
        ],
        "how_to_steps_bn": [
            "পিনের দৈর্ঘ্য নির্বাচন করুন (এটিএম কার্ডের জন্য ৪ ডিজিট, মোবাইলের জন্য ৬ ডিজিট ইত্যাদি)।",
            "কতগুলো পিন একসাথে চান তা ঠিক করুন (১, ৫, ১০ বা ২০টি)।",
            "সহজ বা ক্রমানুসারী ডিজিট (যেমন ১২৩৪, ০০০০) এড়াতে ফিল্টার চালু করুন।",
            "'Generate PINs' বাটনে ক্লিক করে নতুন পিন তৈরি করুন।",
            "পছন্দের পিনটির পাশে 'Copy' বাটনে ক্লিক করুন।"
        ],
        "specs_en": {
            "RNG Engine": "Web Crypto CSPRNG (crypto.getRandomValues)",
            "Available Lengths": "3 to 16 digits",
            "Filters": "Sequential rejection (e.g. 1234, 4321) & Repeating rejection (e.g. 1111)",
            "Client Execution": "100% in-browser JavaScript"
        },
        "specs_bn": {
            "RNG ইঞ্জিন": "ওয়েব ক্রিপ্টো CSPRNG (crypto.getRandomValues)",
            "সমর্থিত দৈর্ঘ্য": "৩ থেকে ১৬ ডিজিট",
            "ফিল্টার": "ক্রমানুসারী সংখ্যা বর্জন ও পুনরাবৃত্ত সংখ্যা ফিল্টারিং",
            "ক্লায়েন্ট এক্সিকিউশন": "১০০% ব্রাউজার মেমরি"
        },
        "faq_en": [
            {"q": "Why should I use a random PIN generator instead of guessing my own?", "a": "Humans naturally choose predictable numbers like birth years, anniversaries, or simple patterns (1234, 2580). A cryptographic random generator prevents pattern-based guessing."},
            {"q": "Can someone hack a 6-digit PIN?", "a": "A 6-digit PIN has 1,000,000 possible combinations. When combined with modern device lockout limits (e.g., 5 or 10 failed attempts before lock), it is virtually unbreakable."}
        ],
        "faq_bn": [
            {"q": "নিজের মতো না বানিয়ে রেন্ডম পিন জেনারেটর কেন ব্যবহার করা উচিত?", "a": "মানুষ সাধারণত জন্মসাল বা সহজ প্যাটার্ন (যেমন ১২৩৪, ২৫৮০) বেছে নেয় যা হ্যাকারদের পক্ষে সহজে অনুমানযোগ্য। রেন্ডম জেনারেটর সম্পূর্ণ নিরপেক্ষ সংখ্যা তৈরি করে।"},
            {"q": "৬ ডিজিটের পিন কি নিরাপদ?", "a": "হ্যাঁ, ৬ ডিজিটের পিনে ১০ লক্ষ সম্ভাব্য কম্বিনেশন থাকে। ডিভাইসের রেট-লিমিটের সাথে এটি অত্যন্ত নিরাপদ।" }
        ]
    }
]

print(f"Loaded {len(TOOLS)} initial tools specifications.")
