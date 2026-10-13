# -*- coding: utf-8 -*-
"""
Full Security & Cryptography Suite Builder for ToolZeno
Builds all 34 tools:
- Blade Views
- Config items
- Translations (EN & BN)
- DB Synchronization
"""
import os
import sys
import json
import re

BASE_DIR = r"d:\xampp\htdocs\tools"

# 34 Tools Definitions
ALL_TOOLS = [
    # ---------------- 1. PASSWORD & CREDENTIAL TOOLS ----------------
    {
        "slug": "password-generator",
        "name_en": "Strong Password Generator",
        "name_bn": "স্ট্রং পাসওয়ার্ড জেনারেটর",
        "short_desc_en": "Generate highly secure, cryptographically random passwords with custom length, character sets, and symbols.",
        "short_desc_bn": "কাস্টম দৈর্ঘ্য, বড়-ছোট হাতের অক্ষর, সংখ্যা ও স্পেশাল ক্যারেক্টার দিয়ে অতি নিরাপদ ও রেন্ডম পাসওয়ার্ড তৈরি করুন।",
        "meta_title_en": "Strong Password Generator — Free & Secure Online Tool | ToolZeno",
        "meta_title_bn": "স্ট্রং পাসওয়ার্ড জেনারেটর — নিরাপদ ও ফ্রি অনলাইন পাসওয়ার্ড মেকার | ToolZeno",
        "meta_desc_en": "Create unbreakable, high-entropy passwords directly in your browser. Customize uppercase, lowercase, numbers, and symbols. 100% private.",
        "meta_desc_bn": "ব্রাউজারে সম্পূর্ণ বিনামূল্যে শক্তিশালী ও অনির্ভেদ্য পাসওয়ার্ড জেনারেট করুন। দৈর্ঘ্য ও বিভিন্ন ক্যারেক্টার ফিল্টার করার সুবিধা। শতভাগ নিরাপদ।",
        "tags": ["password generator", "strong password", "random password", "secure password", "passcode creator"],
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
        "name_en": "Password Strength & Entropy Checker",
        "name_bn": "পাসওয়ার্ড স্ট্রেন্থ ও এন্ট্রপি চেকার",
        "short_desc_en": "Analyze password complexity, entropy score in bits, and estimated time to crack with brute-force attacks.",
        "short_desc_bn": "পাসওয়ার্ডের জটিলতা, এন্ট্রপি (বিটস) স্কোর এবং ব্রুট-ফোর্স অ্যাটাকে ক্র্যাক হতে কত সময় লাগবে তা পরীক্ষা করুন।",
        "meta_title_en": "Password Strength & Entropy Checker — Crack Time Calculator | ToolZeno",
        "meta_title_bn": "পাসওয়ার্ড স্ট্রেন্থ ও এন্ট্রপি চেকার — ক্র্যাক টাইম ক্যালকুলেটর | ToolZeno",
        "meta_desc_en": "Check password entropy, complexity score, and estimated crack time for free. 100% browser-based with zero network transmission.",
        "meta_desc_bn": "পাসওয়ার্ডের নিরাপত্তা ও এন্ট্রপি স্কোর রিয়েল-টাইমে যাচাই করুন। সম্পূর্ণ ব্রাউজার সাইডে নিরাপদ অ্যানালাইসিস।",
        "tags": ["password strength checker", "password entropy", "crack time calculator", "brute force estimate"],
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
            "Entropy Formula": "E = L × log2(R) where R is character set size",
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
        "name_en": "Passphrase Generator (Diceware Style)",
        "name_bn": "পাসফ্রেজ জেনারেটর (ডাইসওয়্যার স্টাইল)",
        "short_desc_en": "Generate memorable yet mathematically unbreakable multi-word passphrases like 'correct-horse-battery-staple'.",
        "short_desc_bn": "ডাইসওয়্যার স্টাইলে সহজে মনে রাখার মতো কিন্তু ক্র্যাক করা অসম্ভব শক্তিশালী পাসফ্রেজ তৈরি করুন।",
        "meta_title_en": "Memorable Passphrase Generator — Diceware Password Maker | ToolZeno",
        "meta_title_bn": "পাসফ্রেজ জেনারেটর — সহজে মনে রাখার মতো শক্তিশালী পাসওয়ার্ড মেকার | ToolZeno",
        "meta_desc_en": "Create high-entropy memorable passphrases with custom word counts, separators, and capitalization. 100% private.",
        "meta_desc_bn": "শব্দ মিলিয়ে সহজে মনে রাখার মতো অতি শক্তিশালী পাসফ্রেজ তৈরি করুন। সম্পূর্ণ ক্লায়েন্ট-সাইডে সুরক্ষিত।",
        "tags": ["passphrase generator", "diceware generator", "memorable password", "correct horse battery staple"],
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
            "Dictionary Pool": "EFF Cryptographic Wordlist",
            "Entropy per Word": "~12.9 bits per random word",
            "PRNG Method": "crypto.getRandomValues",
            "Default Pattern": "4 words separated by hyphens"
        },
        "specs_bn": {
            "ডিকশনারি পুল": "EFF ক্রিপ্টোগ্রাফিক শব্দ তালিকা",
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
        "name_en": "PIN Code Generator",
        "name_bn": "পিন কোড জেনারেটর",
        "short_desc_en": "Generate cryptographically secure 4, 6, 8, or custom digit PIN codes for ATMs, mobile apps, and device locks.",
        "short_desc_bn": "এটিএম, মোবাইল অ্যাপ এবং ডিভাইস লকের জন্য ক্রিপ্টোগ্রাফিক্যালি নিরাপদ ৪, ৬ বা ৮ ডিজিটের পিন কোড তৈরি করুন।",
        "meta_title_en": "Secure PIN Code Generator — 4, 6, 8 Digit Random PINs | ToolZeno",
        "meta_title_bn": "সিকিউর পিন কোড জেনারেটর — ৪, ৬, ৮ ডিজিটের রেন্ডম পিন | ToolZeno",
        "meta_desc_en": "Generate truly random 4-digit, 6-digit, and custom-length PIN codes using cryptographic randomness. Filter out easy sequences. Free & secure.",
        "meta_desc_bn": "৪ ও ৬ ডিজিটের ক্রিপ্টোগ্রাফিক রেন্ডম পিন কোড তৈরি করুন। সহজ বা অনুমানযোগ্য সিকোয়েন্স ফিল্টার করার সুবিধাসহ।",
        "tags": ["pin code generator", "random pin generator", "atm pin", "4 digit pin", "6 digit pin"],
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
    },

    # ---------------- 2. CRYPTOGRAPHIC HASH GENERATORS ----------------
    {
        "slug": "md5-hash-generator",
        "name_en": "MD5 Hash Generator",
        "name_bn": "MD5 হ্যাশ জেনারেটর",
        "short_desc_en": "Generate fast 128-bit MD5 checksum hashes from any text string directly in your browser.",
        "short_desc_bn": "যেকোনো টেক্সট থেকে দ্রুত ১২৮-বিট MD5 হ্যাশ বের করুন সরাসরি ব্রাউজারে।",
        "meta_title_en": "MD5 Hash Generator — Online String Checksum Tool | ToolZeno",
        "meta_title_bn": "MD5 হ্যাশ জেনারেটর — টেক্সট ও স্ট্রিং হ্যাশ ক্যালকুলেটর | ToolZeno",
        "meta_desc_en": "Calculate 32-character hexadecimal MD5 hashes online. Supports lowercase, uppercase, and real-time live typing. 100% private.",
        "meta_desc_bn": "রিয়েল-টাইমে টেক্সট থেকে ৩২ অক্ষরের MD5 হ্যাশ তৈরি করুন। কোনো ডাটা সার্ভারে যায় না, শতভাগ সুরক্ষিত।",
        "tags": ["md5 generator", "md5 hash", "md5 checksum", "128 bit hash", "string hash"],
        "how_to_steps_en": [
            "Type or paste your text into the input message field.",
            "The 128-bit MD5 hash generates instantly in real-time as you type.",
            "Choose Lowercase Hex (standard) or Uppercase Hex format.",
            "Click 'Copy' to copy the 32-character hash digest to clipboard."
        ],
        "how_to_steps_bn": [
            "ইনপুট বক্সে আপনার টেক্সট লিখুন বা পেস্ট করুন।",
            "টাইপ করার সাথে সাথেই রিয়েল-টাইমে ৩২ অক্ষরের MD5 হ্যাশ তৈরি হবে।",
            "প্রয়োজন অনুযায়ী ছোট হাতের (Lowercase) বা বড় হাতের (Uppercase) হেক্স নির্বাচন করুন।",
            "'Copy' বাটনে ক্লিক করে হ্যাশ কপি করে নিন।"
        ],
        "specs_en": {
            "Digest Size": "128 bits (16 bytes)",
            "Hex String Length": "32 hexadecimal characters",
            "Algorithm": "RFC 1321 MD5 message-digest algorithm",
            "Encoding": "UTF-8 text encoding"
        },
        "specs_bn": {
            "হ্যাশ আকার": "১২৮ বিট (১৬ বাইট)",
            "হেক্স স্ট্রিং দৈর্ঘ্য": "৩২ হেক্সাডেসিমেল ক্যারেক্টার",
            "অ্যালগরিদম": "RFC 1321 MD5 অ্যালগরিদম",
            "এনকোডিং": "UTF-8 টেক্সট এনকোডিং"
        },
        "faq_en": [
            {"q": "Is MD5 safe for password storage?", "a": "No. MD5 is cryptographically broken and vulnerable to collision attacks. It should only be used for non-cryptographic checksums and legacy data verification. For passwords, use Bcrypt or Argon2."},
            {"q": "Can an MD5 hash be reversed?", "a": "MD5 is a one-way mathematical function. It cannot be mathematically 'decrypted', though rainbow tables can look up common pre-computed inputs."}
        ],
        "faq_bn": [
            {"q": "পাসওয়ার্ড সংরক্ষণের জন্য MD5 কি নিরাপদ?", "a": "না। MD5 আধুনিক ক্রিপ্টোগ্রাফিতে নিরাপদ নয়। পাসওয়ার্ডের জন্য Bcrypt বা Argon2 ব্যবহার করা উচিত। MD5 সাধারণত ফাইলের অখণ্ডতা যাচাইয়ে ব্যবহৃত হয়।"},
            {"q": "MD5 হ্যাশ কি রিভার্স বা ডিক্রিপ্ট করা যায়?", "a": "না, এটি ওয়ান-ওয়ে হ্যাশ ফাংশন। সরাসরি ডিক্রিপ্ট করা সম্ভব নয়, তবে রেইনবো টেবিলের মাধ্যমে সাধারণ পাসওয়ার্ড শনাক্ত করা হতে পারে।"}
        ]
    },
    {
        "slug": "sha1-hash-generator",
        "name_en": "SHA-1 Hash Generator",
        "name_bn": "SHA-1 হ্যাশ জেনারেটর",
        "short_desc_en": "Generate 160-bit SHA-1 cryptographic hashes for Git commits, legacy certificates, and data checksums.",
        "short_desc_bn": "গিট কমিট, লেগাসি সার্টিফিকেট এবং ডেটা ভেরিফিকেশনের জন্য ১৬০-বিট SHA-1 হ্যাশ তৈরি করুন।",
        "meta_title_en": "SHA-1 Hash Generator — Fast 160-bit Checksum Tool | ToolZeno",
        "meta_title_bn": "SHA-1 হ্যাশ জেনারেটর — ১৬০-বিট হ্যাশ ক্যালকুলেটর | ToolZeno",
        "meta_desc_en": "Create 40-character SHA-1 hash strings online instantly using browser Web Crypto. Real-time, free, and completely client-side.",
        "meta_desc_bn": "ব্রাউজারে সরাসরি টেক্সট থেকে ৪০ অক্ষরের SHA-1 হ্যাশ জেনারেট করুন। শতভাগ ক্লায়েন্ট সাইড ও নিরাপদ।",
        "tags": ["sha1 generator", "sha-1 hash", "160 bit hash", "git commit hash", "sha1 checksum"],
        "how_to_steps_en": [
            "Enter your text payload into the input area.",
            "The 160-bit SHA-1 digest calculates instantly via the Web Crypto API.",
            "View outputs in standard Hex (40 characters) and Base64 format.",
            "Click 'Copy' to copy the resulting hash."
        ],
        "how_to_steps_bn": [
            "ইনপুট বক্সে আপনার টেক্সট লিখুন।",
            "ওয়েব ক্রিপ্টো API এর মাধ্যমে সাথে সাথেই ১৬০-বিট SHA-1 হ্যাশ তৈরি হবে।",
            "৪০ অক্ষরের হেক্স এবং বেস৬৪ ফরম্যাটে আউটপুট দেখুন।",
            "'Copy' বাটনে ক্লিক করে হ্যাশ কপি করে নিন।"
        ],
        "specs_en": {
            "Digest Size": "160 bits (20 bytes)",
            "Hex String Length": "40 hexadecimal characters",
            "Engine": "Native window.crypto.subtle.digest('SHA-1')",
            "Standards": "FIPS PUB 180-4 Secure Hash Standard"
        },
        "specs_bn": {
            "হ্যাশ আকার": "১৬০ বিট (২০ বাইট)",
            "হেক্স স্ট্রিং দৈর্ঘ্য": "৪০ হেক্সাডেসিমেল ক্যারেক্টার",
            "ইঞ্জিন": "নেটিভ window.crypto.subtle.digest('SHA-1')",
            "স্ট্যান্ডার্ড": "FIPS PUB 180-4 সিকিউর হ্যাশ স্ট্যান্ডার্ড"
        },
        "faq_en": [
            {"q": "Where is SHA-1 commonly used?", "a": "SHA-1 is famously used by the Git version control system to identify commits, blobs, and trees. It is also used in legacy SSL certificates and software distribution checksums."},
            {"q": "Is SHA-1 secure for modern encryption?", "a": "No, theoretical collisions have been demonstrated (SHAttered attack in 2017). Modern security applications require SHA-256 or SHA-512."}
        ],
        "faq_bn": [
            {"q": "SHA-1 কোথায় বেশি ব্যবহৃত হয়?", "a": "গিট (Git) ভার্সন কন্ট্রোল সিস্টেমে কমিট ও অবজেক্ট শনাক্তকরণে SHA-1 ব্যাপকভাবে ব্যবহৃত হয়। এছাড়াও পুরানো সফটওয়্যার চেকসামে এর ব্যবহার আছে।"},
            {"q": "SHA-1 কি আধুনিক সিস্টেমে নিরাপদ?", "a": "না, ২০১৭ সালে এর কোলিশন প্রমাণিত হয়েছে। নতুন কোনো সিস্টেমের জন্য SHA-256 বা SHA-512 ব্যবহার করা বাধ্যতামূলক।"}
        ]
    },
    {
        "slug": "sha256-hash-generator",
        "name_en": "SHA-256 Hash Generator",
        "name_bn": "SHA-256 হ্যাশ জেনারেটর",
        "short_desc_en": "Compute military-grade 256-bit SHA-256 cryptographic hashes used in Bitcoin, SSL, and modern security protocols.",
        "short_desc_bn": "বিটকয়েন, এসএসএল এবং আধুনিক সিকিউরিটিতে ব্যবহৃত ২৫৬-বিট SHA-256 ক্রিপ্টোগ্রাফিক হ্যাশ তৈরি করুন।",
        "meta_title_en": "SHA-256 Hash Generator — Secure 256-bit Hash Online | ToolZeno",
        "meta_title_bn": "SHA-256 হ্যাশ জেনারেটর — ২৫৬-বিট ক্রিপ্টোগ্রাফিক হ্যাশ | ToolZeno",
        "meta_desc_en": "Generate standard 64-character SHA-256 hashes directly in browser via Web Crypto API. Free, private, and lightning fast.",
        "meta_desc_bn": "ব্রাউজারে রিয়েল-টাইমে ৬৪ অক্ষরের নিরাপদ SHA-256 হ্যাশ জেনারেট করুন। সম্পূর্ণ প্রাইভেট ও নির্ভুল।",
        "tags": ["sha256 generator", "sha-256 online", "bitcoin hash", "256 bit checksum", "crypto hash"],
        "how_to_steps_en": [
            "Input any text, secret, or string into the text box.",
            "SHA-256 digest is generated in real-time in your browser memory.",
            "View standard 64-character hexadecimal and Base64 encoded outputs.",
            "Click 'Copy' to copy the secure hash."
        ],
        "how_to_steps_bn": [
            "টেক্সট বক্সে যেকোনো লেখা বা স্ট্রিং টাইপ করুন।",
            "ওয়েব ক্রিপ্টো API দিয়ে তৎক্ষণাৎ ২৫৬-বিট হ্যাশ তৈরি হবে।",
            "৬৪ অক্ষরের হেক্স এবং বেস৬৪ ফরম্যাট প্রিভিউ দেখুন।",
            "'Copy' বাটনে ক্লিক করে হ্যাশ কপি করে নিন।"
        ],
        "specs_en": {
            "Digest Size": "256 bits (32 bytes)",
            "Hex String Length": "64 hexadecimal characters",
            "Engine": "Web Crypto API (crypto.subtle.digest('SHA-256'))",
            "Security Level": "128 bits of collision resistance (NIST standard)"
        },
        "specs_bn": {
            "হ্যাশ আকার": "২৫৬ বিট (৩২ বাইট)",
            "হেক্স স্ট্রিং দৈর্ঘ্য": "৬৪ হেক্সাডেসিমেল ক্যারেক্টার",
            "ইঞ্জিন": "ওয়েব ক্রিপ্টো API (crypto.subtle.digest('SHA-256'))",
            "নিরাপত্তা স্তর": "১২৮ বিট কোলিশন রেজিস্ট্যান্স (NIST স্ট্যান্ডার্ড)"
        },
        "faq_en": [
            {"q": "Can SHA-256 be cracked?", "a": "No. SHA-256 has never been compromised. There are 2^256 possible hash combinations, which exceeds the estimated number of atoms in the observable universe."},
            {"q": "Why is SHA-256 used in Bitcoin?", "a": "Bitcoin uses double SHA-256 for Proof of Work and address generation because of its uniform pseudorandom distribution and complete resistance to preimage attacks."}
        ],
        "faq_bn": [
            {"q": "SHA-256 কি ক্র্যাক করা সম্ভব?", "a": "না। এখন পর্যন্ত SHA-256 ক্র্যাক করা সম্ভব হয়নি। এর সম্ভাব্য কম্বিনেশনের সংখ্যা মহাবিশ্বের পরমাণুর সংখ্যার চেয়েও বেশি।"},
            {"q": "বিটকয়েনে কেন SHA-256 ব্যবহার করা হয়?", "a": "বিটকয়েনের প্রুফ-অব-ওয়ার্ক ও ব্লক চেইনে এর সর্বোচ্চ নিরাপত্তা ও প্রিইমেজ রেজিস্ট্যান্সের কারণে ডাবল SHA-256 ব্যবহৃত হয়।"}
        ]
    },
    {
        "slug": "sha512-hash-generator",
        "name_en": "SHA-512 Hash Generator",
        "name_bn": "SHA-512 হ্যাশ জেনারেটর",
        "short_desc_en": "Generate ultra-secure 512-bit cryptographic hashes optimized for 64-bit architectures and high-security enterprise systems.",
        "short_desc_bn": "৬৪-বিট আর্কিটেকচার ও এন্টারপ্রাইজ সিস্টেমের জন্য অতি-নিরাপদ ৫১২-বিট SHA-512 হ্যাশ তৈরি করুন।",
        "meta_title_en": "SHA-512 Hash Generator — 512-bit High Security Hash | ToolZeno",
        "meta_title_bn": "SHA-512 হ্যাশ জেনারেটর — ৫১২-বিট ক্রিপ্টোগ্রাফিক হ্যাশ | ToolZeno",
        "meta_desc_en": "Calculate 128-character SHA-512 hash digests online. Built on native browser cryptography with zero server latency.",
        "meta_desc_bn": "ব্রাউজারে সরাসরি ১২৮ অক্ষরের শক্তিশালী SHA-512 হ্যাশ জেনারেট করুন। সর্বোচ্চ নিরাপত্তা ও প্রাইভেসি।",
        "tags": ["sha512 generator", "sha-512 hash", "512 bit hash", "sha2 family", "secure hash"],
        "how_to_steps_en": [
            "Type or paste your data string into the input editor.",
            "The 512-bit digest generates live via hardware-accelerated Web Crypto.",
            "Inspect the 128-character hex digest and Base64 format.",
            "Click 'Copy' to copy the hash output."
        ],
        "how_to_steps_bn": [
            "ইনপুট এডিটরে আপনার তথ্য বা স্ট্রিং পেস্ট করুন।",
            "ওয়েব ক্রিপ্টোর মাধ্যমে সরাসরি ৫১২-বিট হ্যাশ তৈরি হয়ে যাবে।",
            "১২৮ অক্ষরের হেক্স আউটপুট ও বেস৬৪ ফরম্যাট দেখতে পাবেন।",
            "'Copy' বাটনে ক্লিক করে হ্যাশ কপি করে নিন।"
        ],
        "specs_en": {
            "Digest Size": "512 bits (64 bytes)",
            "Hex String Length": "128 hexadecimal characters",
            "Engine": "crypto.subtle.digest('SHA-512')",
            "Word Size": "64-bit operations (ultra-fast on modern CPUs)"
        },
        "specs_bn": {
            "হ্যাশ আকার": "৫১২ বিট (৬৪ বাইট)",
            "হেক্স স্ট্রিং দৈর্ঘ্য": "১২৮ হেক্সাডেসিমেল ক্যারেক্টার",
            "ইঞ্জিন": "crypto.subtle.digest('SHA-512')",
            "ওয়ার্ড সাইজ": "৬৪-বিট অপারেশনস"
        },
        "faq_en": [
            {"q": "Is SHA-512 faster than SHA-256?", "a": "On 64-bit modern processors, SHA-512 is often faster than SHA-256 because it operates directly on 64-bit words rather than 32-bit words."},
            {"q": "When should I choose SHA-512 over SHA-256?", "a": "SHA-512 is ideal for long-term data archiving, financial settlement layers, and cryptographic key derivation where maximum collision resistance is critical."}
        ],
        "faq_bn": [
            {"q": "SHA-512 কি SHA-256 এর চেয়ে দ্রুত?", "a": "হ্যাঁ, আধুনিক ৬৪-বিট প্রসেসরে SHA-512 সাধারণত SHA-256 এর চেয়ে দ্রুত কাজ করে কারণ এটি সরাসরি ৬৪-বিট ওয়ার্ড হ্যান্ডেল করে।"},
            {"q": "কখন SHA-512 ব্যবহার করা উচিত?", "a": "দীর্ঘমেয়াদী ডেটা আর্কাইভিং, আর্থিক লেনদেন এবং সর্বোচ্চ নিরাপত্তা নিশ্চিত করতে SHA-512 ব্যবহার করা শ্রেয়।"}
        ]
    },
    {
        "slug": "sha3-hash-generator",
        "name_en": "SHA-3 / Keccak Hash Generator",
        "name_bn": "SHA-3 ও কেকাক (Keccak) হ্যাশ জেনারেটর",
        "short_desc_en": "Generate modern sponge-construction hashes including NIST SHA3-256, SHA3-512, and Ethereum Keccak-256.",
        "short_desc_bn": "ইথেরিয়াম ও আধুনিক ব্লকচেইনে ব্যবহৃত কেকাক (Keccak-256) এবং NIST SHA3-256/512 হ্যাশ তৈরি করুন।",
        "meta_title_en": "SHA-3 & Keccak-256 Hash Generator — Web3 Ethereum Hash | ToolZeno",
        "meta_title_bn": "SHA-3 ও Keccak-256 হ্যাশ জেনারেটর — ইথেরিয়াম ও ব্লকচেইন হ্যাশ | ToolZeno",
        "meta_desc_en": "Calculate Keccak-256 (Ethereum smart contracts) and standardized SHA3-256/512 hashes online. 100% browser-based.",
        "meta_desc_bn": "স্মার্ট কন্ট্রাক্ট ও Web3 এর জন্য Keccak-256 এবং SHA-3 হ্যাশ জেনারেট করুন সম্পূর্ণ ক্লায়েন্ট সাইডে।",
        "tags": ["sha3 generator", "keccak 256", "ethereum hash", "sha3 512", "sponge hash"],
        "how_to_steps_en": [
            "Select algorithm: Keccak-256 (Ethereum standard), SHA3-256, or SHA3-512.",
            "Type or paste your text or hex data into the input field.",
            "View live sponge-construction hash in hexadecimal format.",
            "Click 'Copy' to copy the resulting hash."
        ],
        "how_to_steps_bn": [
            "অ্যালগরিদম সিলেক্ট করুন: Keccak-256 (ইথেরিয়াম স্ট্যান্ডার্ড), SHA3-256 অথবা SHA3-512।",
            "ইনপুট বক্সে আপনার টেক্সট লিখুন বা পেস্ট করুন।",
            "রিয়েল-টাইমে হেক্সাডেসিমেল হ্যাশ আউটপুট দেখতে পাবেন।",
            "'Copy' বাটনে ক্লিক করে হ্যাশ কপি করে নিন।"
        ],
        "specs_en": {
            "Family": "Keccak Permutation & Sponge Construction (FIPS 202)",
            "Supported Modes": "Keccak-256 (Ethereum), SHA3-256, SHA3-384, SHA3-512",
            "Output Size": "256 to 512 bits",
            "Client Execution": "Pure JavaScript Cryptographic Engine"
        },
        "specs_bn": {
            "ফ্যামিলি": "কেকাক পারমিউটেশন ও স্পঞ্জ কনস্ট্রাকশন (FIPS 202)",
            "মোডসমূহ": "Keccak-256 (Ethereum), SHA3-256, SHA3-384, SHA3-512",
            "আউটপুট আকার": "২৫৬ থেকে ৫১২ বিট",
            "ক্লায়েন্ট এক্সিকিউশন": "পিওর জাভাস্ক্রিপ্ট ক্রিপ্টোগ্রাফিক ইঞ্জিন"
        },
        "faq_en": [
            {"q": "What is the difference between Keccak-256 and SHA3-256?", "a": "Keccak won the NIST competition, but NIST slightly altered the padding rule before releasing the official SHA-3 standard. Ethereum uses the original Keccak-256 for addresses and function selectors."},
            {"q": "Why is SHA-3 considered superior to SHA-2?", "a": "SHA-3 uses a completely different mathematical sponge construction instead of the Merkle-Damgård structure, making it completely immune to length extension attacks."}
        ],
        "faq_bn": [
            {"q": "Keccak-256 এবং SHA3-256 এর মধ্যে পার্থক্য কী?", "a": "NIST প্রতিযোগিতায় Keccak বিজয়ী হলেও চূড়ান্ত SHA-3 স্পেসিফিকেশনে প্যাডিং সামান্য পরিবর্তন করা হয়। ইথেরিয়াম আসল Keccak-256 ব্যবহার করে।"},
            {"q": "SHA-3 কেন SHA-2 এর চেয়ে উন্নত?", "a": "SHA-3 স্পঞ্জ কনস্ট্রাকশন ব্যবহার করায় এটি 'লেন্থ এক্সটেনশন অ্যাটাক' থেকে সম্পূর্ণ নিরাপদ।" }
        ]
    },
    {
        "slug": "file-checksum-calculator",
        "name_en": "File Checksum & Hash Calculator",
        "name_bn": "ফাইল চেকসাম ও হ্যাশ ক্যালকুলেটর",
        "short_desc_en": "Verify file integrity by calculating MD5, SHA-1, SHA-256, and SHA-512 checksums entirely in your browser without uploading.",
        "short_desc_bn": "সার্ভারে ফাইল আপলোড না করেই ব্রাউজারে ফাইলের MD5, SHA-1, SHA-256 ও SHA-512 চেকসাম বের করুন।",
        "meta_title_en": "File Checksum Calculator — Browser-Side MD5, SHA-256 | ToolZeno",
        "meta_title_bn": "ফাইল চেকসাম ও হ্যাশ ক্যালকুলেটর — ফাইলের নিরাপত্তা যাচাই | ToolZeno",
        "meta_desc_en": "Compute file hashes locally in your browser with zero data upload. Verify downloaded software integrity against official checksums.",
        "meta_desc_bn": "কোনো ফাইল সার্ভারে আপলোড ছাড়াই লোকালি চেকসাম গণনা করুন। সফটওয়্যার ও ফাইলের অখণ্ডতা শতভাগ নিখুঁতভাবে যাচাই করুন।",
        "tags": ["file checksum", "file hash calculator", "sha256 file", "md5 file", "verify file integrity"],
        "how_to_steps_en": [
            "Drag & drop any file into the upload zone or click to select from your device.",
            "The browser computes cryptographic hashes in chunks using FileReader and Web Crypto.",
            "Compare with the official checksum by pasting it into the 'Verify Match' box.",
            "Get an instant green match confirmation or red mismatch alert."
        ],
        "how_to_steps_bn": [
            "যেকোনো ফাইল ড্র্যাগ অ্যান্ড ড্রপ করুন অথবা ব্রাউজ করে নির্বাচন করুন।",
            "ব্রাউজারের ভেতরেই লোকালি ফাইলের MD5, SHA-1 ও SHA-256 হ্যাশ বের হবে।",
            "অফিসিয়াল চেকসাম মিলিয়ে দেখতে 'Verify Match' বক্সে পেস্ট করুন।",
            "হ্যাশ মিলে গেলে সাথে সাথেই সবুজ রঙের ভেরিফিকেশন টিক চিহ্ন দেখতে পাবেন।"
        ],
        "specs_en": {
            "Processing": "100% Client-Side streaming via HTML5 FileReader (0 bytes uploaded)",
            "Algorithms": "SHA-256, SHA-1, SHA-512, MD5",
            "Max File Size": "Up to 2GB supported directly in browser memory",
            "Checksum Matching": "Case-insensitive hex comparator"
        },
        "specs_bn": {
            "প্রসেসিং": "HTML5 FileReader দিয়ে ১০০% লোকাল স্ট্রিমিং (কোনো আপলোড হয় না)",
            "অ্যালগরিদম": "SHA-256, SHA-1, SHA-512, MD5",
            "সর্বোচ্চ সাইজ": "২ গিগাবাইট পর্যন্ত ফাইল সরাসরি মেমরিতে টেস্টযোগ্য",
            "ম্যাচিং": "কেস-ইনসেনসিটিভ হেক্স কম্প্যারেটর"
        },
        "faq_en": [
            {"q": "Is my file uploaded to your server?", "a": "Absolutely not. The file never leaves your computer. The Web Crypto API streams the file byte-by-byte in your browser's private memory sandbox."},
            {"q": "How does checksum verification protect me?", "a": "Software developers publish checksums so users can verify that their downloaded installer has not been tampered with by hackers or corrupted by a faulty network download."}
        ],
        "faq_bn": [
            {"q": "আমার ফাইল কি আপনাদের সার্ভারে আপলোড হয়?", "a": "একদমই না। ফাইলটি আপনার কম্পিউটার থেকেই বের হয় না। ব্রাউজার লোকালি মেমরিতে হ্যাশ তৈরি করে।"},
            {"q": "চেকসাম ভেরিফিকেশন কীভাবে আমাকে রক্ষা করে?", "a": "ডাউনলোড করা সফটওয়্যারটি কোনো হ্যাকার দ্বারা পরিবর্তিত বা ম্যালওয়্যারযুক্ত কিনা তা চেকসাম মিলিয়ে শতভাগ নিশ্চিত হওয়া যায়।"}
        ]
    },
    {
        "slug": "hash-identifier",
        "name_en": "Hash Identifier & Analyzer",
        "name_bn": "হ্যাশ আইডেন্টিফায়ার ও অ্যানালাইজার",
        "short_desc_en": "Paste any unknown hash string to identify its probable algorithm (MD5, SHA-256, Bcrypt, Argon2, NTLM, and more).",
        "short_desc_bn": "অজানা যেকোনো হ্যাশ পেস্ট করে এটি কোন অ্যালগরিদমের (MD5, SHA-256, Bcrypt, NTLM ইত্যাদি) তা শনাক্ত করুন।",
        "meta_title_en": "Hash Identifier — Detect Hash Type & Algorithm Online | ToolZeno",
        "meta_title_bn": "হ্যাশ আইডেন্টিফায়ার — হ্যাশ অ্যালগরিদম শনাক্তকরণ টুল | ToolZeno",
        "meta_desc_en": "Identify hash algorithms by analyzing bit length, character sets, and salt prefixes. Detects 30+ hash types instantly.",
        "meta_desc_bn": "যেকোনো হ্যাশ পেস্ট করে এর অ্যালগরিদম ও টাইপ মুহূর্তেই শনাক্ত করুন। ৩০টিরও বেশি হ্যাশ ফরম্যাট সাপোর্ট করে।",
        "tags": ["hash identifier", "identify hash", "detect hash algorithm", "hash analyzer", "hash lookup"],
        "how_to_steps_en": [
            "Paste your mystery hash string into the text field.",
            "The identifier inspects character set, length, prefix, and pattern.",
            "Review detected algorithm candidates ranked by confidence level.",
            "View standard bit length, typical use-cases, and crackability status."
        ],
        "how_to_steps_bn": [
            "ইনপুট বক্সে আপনার অজানা হ্যাশ স্ট্রিংটি পেস্ট করুন।",
            "টুলটি ক্যারেক্টার সেট, দৈর্ঘ্য ও প্রিফিক্স বিশ্লেষণ করে অ্যালগরিদম শনাক্ত করবে।",
            "সম্ভাব্য অ্যালগরিদমের তালিকা এবং তাদের কনফিডেন্স স্কোর দেখুন।",
            "হ্যাশের বিট সাইজ এবং নিরাপত্তা বৈশিষ্ট্য পর্যালোচনা করুন।"
        ],
        "specs_en": {
            "Patterns Detected": "MD5, SHA-1, SHA-256, SHA-512, Bcrypt ($2a$, $2b$), Argon2, NTLM, MySQL, CRC32, etc.",
            "Detection Engine": "Heuristic length and regex pattern matcher",
            "Security Advice": "Recommends modern replacements for legacy hashes",
            "Execution": "Real-time client-side analysis"
        },
        "specs_bn": {
            "শনাক্তযোগ্য ফরম্যাট": "MD5, SHA-1, SHA-256, SHA-512, Bcrypt, Argon2, NTLM, MySQL, CRC32 ইত্যাদি",
            "ডিটেকশন ইঞ্জিন": "হিউরিস্টিক দৈর্ঘ্য ও রেজেক্স প্যাটার্ন ম্যাচিং",
            "নিরাপত্তা পরামর্শ": "পুরানো হ্যাশের পরিবর্তে আধুনিক অ্যালগরিদমের সুপারিশ",
            "এক্সিকিউশন": "রিয়েল-টাইম ক্লায়েন্ট-সাইড অ্যানালাইসিস"
        },
        "faq_en": [
            {"q": "Can two different algorithms produce identical length hashes?", "a": "Yes. For example, MD5 and NTLM both produce 32-character hexadecimal strings. The tool lists all possible matches with confidence indicators."},
            {"q": "How does it detect Bcrypt and Argon2?", "a": "Modular crypt format hashes use standardized prefixes (e.g. $2a$ for Bcrypt, $argon2id$ for Argon2) that allow deterministic identification."}
        ],
        "faq_bn": [
            {"q": "দুটি ভিন্ন অ্যালগরিদমের কি একই দৈর্ঘ্যের হ্যাশ হতে পারে?", "a": "হ্যাঁ। যেমন MD5 এবং NTLM উভয়েই ৩২ অক্ষরের হেক্স স্ট্রিং তৈরি করে। এই টুল সম্ভাব্য সকল অ্যালগরিদম প্রদর্শন করে।"},
            {"q": "Bcrypt এবং Argon2 কীভাবে শনাক্ত হয়?", "a": "এদের হ্যাশের শুরুতে নির্দিষ্ট প্রিফিক্স (যেমন $2a$ বা $argon2id$) থাকে যা দেখে নিশ্চিত হওয়া যায়।"}
        ]
    },

    # ---------------- 3. PASSWORD HASHING & DERIVATION ----------------
    {
        "slug": "bcrypt-generator-verifier",
        "name_en": "Bcrypt Hash Generator & Verifier",
        "name_bn": "Bcrypt হ্যাশ জেনারেটর ও ভেরিফায়ার",
        "short_desc_en": "Generate secure salted Bcrypt password hashes and verify plain text passwords against existing Bcrypt hashes.",
        "short_desc_bn": "কাস্টম সল্ট রাউন্ড দিয়ে Bcrypt হ্যাশ তৈরি করুন এবং পাসওয়ার্ডের সাথে আসল হ্যাশ ম্যাচ করে কিনা তা পরীক্ষা করুন।",
        "meta_title_en": "Bcrypt Generator & Verifier — Password Hash Test Tool | ToolZeno",
        "meta_title_bn": "Bcrypt হ্যাশ জেনারেটর ও ভেরিফায়ার — পাসওয়ার্ড হ্যাশ টেস্ট | ToolZeno",
        "meta_desc_en": "Create $2a$/$2b$ Bcrypt hashes with custom cost factors (4-14 rounds) and verify passwords online. 100% private in browser.",
        "meta_desc_bn": "ব্রাউজারে সরাসরি Bcrypt হ্যাশ তৈরি ও ভেরিফাই করুন। সম্পূর্ণ নিরাপদ ও ক্লায়েন্ট সাইডে প্রসেসড।",
        "tags": ["bcrypt generator", "bcrypt verifier", "password hash", "bcrypt check", "salt rounds"],
        "how_to_steps_en": [
            "To generate: Switch to 'Generate Hash', enter password, choose salt rounds (recommended: 10-12), click 'Generate'.",
            "To verify: Switch to 'Verify Match', enter plain text password and paste existing $2a$/$2b$ Bcrypt hash.",
            "Click 'Verify Password' to get an instant match/mismatch result.",
            "Copy the resulting hash or verification status."
        ],
        "how_to_steps_bn": [
            "হ্যাশ তৈরিতে: 'Generate Hash' ট্যাবে যান, পাসওয়ার্ড লিখুন, সল্ট রাউন্ড (১০-১২) সিলেক্ট করে 'Generate' বাটনে চাপ দিন।",
            "ভেরিফাই করতে: 'Verify Match' ট্যাবে যান, প্লেইন পাসওয়ার্ড ও আসল Bcrypt হ্যাশ পেস্ট করুন।",
            "'Verify Password' বাটনে ক্লিক করে হ্যাশ ম্যাচ করেছে কিনা যাচাই করুন।",
            "হ্যাশটি কপি করে আপনার ডাটাবেস বা কোডে ব্যবহার করুন।"
        ],
        "specs_en": {
            "Algorithm": "Bcrypt Blowfish-based adaptive password hashing",
            "Cost Factor": "4 to 14 logarithmic salt rounds (default 10)",
            "Format": "$2a$ or $2b$ modular crypt format (60 characters)",
            "Client Execution": "Compiled JavaScript Bcrypt Engine"
        },
        "specs_bn": {
            "অ্যালগরিদম": "Bcrypt ব্লোফিশ-ভিত্তিক অ্যাডাপ্টিভ পাসওয়ার্ড হ্যাশিং",
            "কস্ট ফ্যাক্টর": "৪ থেকে ১৪ লগারিদমিক সল্ট রাউন্ড (ডিফল্ট ১০)",
            "ফরম্যাট": "$2a$ বা $2b$ ফরম্যাট (৬০ ক্যারেক্টার)",
            "ক্লায়েন্ট এক্সিকিউশন": "পিওর জাভাস্ক্রিপ্ট Bcrypt ইঞ্জিন"
        },
        "faq_en": [
            {"q": "Why is Bcrypt preferred over SHA-256 for passwords?", "a": "Bcrypt is deliberately computationally slow and memory-intensive, with a tunable cost factor that neutralizes GPU and ASIC brute-force cracking hardware."},
            {"q": "Why does the hash change every time for the same password?", "a": "Bcrypt generates a cryptographically random 128-bit salt each time, ensuring identical passwords have completely different hashes to defeat rainbow table attacks."}
        ],
        "faq_bn": [
            {"q": "পাসওয়ার্ডের জন্য SHA-256 এর চেয়ে Bcrypt কেন সেরা?", "a": "Bcrypt ইচ্ছাকৃতভাবে কিছুটা ধীরগতির এবং এর কস্ট ফ্যাক্টর নিয়ন্ত্রণ করা যায়, ফলে হ্যাকারদের সুপারকম্পিউটার দিয়ে ব্রুট-ফোর্স করা অসম্ভব হয়ে পড়ে।"},
            {"q": "একই পাসওয়ার্ডে প্রতিবার ভিন্ন হ্যাশ আসে কেন?", "a": "Bcrypt প্রতিবার স্বয়ংক্রিয়ভাবে একটি নতুন রেন্ডম সল্ট (Salt) যুক্ত করে, ফলে রেইনবো টেবিল অ্যাটাক সম্পূর্ণ ব্যর্থ হয়।"}
        ]
    },
    {
        "slug": "argon2-hash-generator",
        "name_en": "Argon2 Hash Generator",
        "name_bn": "Argon2 হ্যাশ জেনারেটর",
        "short_desc_en": "Configure and generate next-generation Argon2id, Argon2i, and Argon2d memory-hard password hashes.",
        "short_desc_bn": "আধুনিক মেমরি-হার্ড পাসওয়ার্ড হ্যাশিং স্ট্যান্ডার্ড Argon2id, Argon2i এবং Argon2d হ্যাশ তৈরি করুন।",
        "meta_title_en": "Argon2 Hash Generator — Memory-Hard Password Hashing | ToolZeno",
        "meta_title_bn": "Argon2 হ্যাশ জেনারেটর — আধুনিক পাসওয়ার্ড হ্যাশ মেকার | ToolZeno",
        "meta_desc_en": "Generate modern Argon2id password hashes with custom iterations, memory cost, and parallelism directly in browser.",
        "meta_desc_bn": "পাসওয়ার্ড হ্যাশিং কম্পিটিশনের বিজয়ী Argon2id এর কাস্টম প্যারামিটার ও হ্যাশ স্ট্রিং তৈরি করুন সহজে।",
        "tags": ["argon2 generator", "argon2id", "password hashing competition", "memory hard hash"],
        "how_to_steps_en": [
            "Select Argon2 variant: Argon2id (recommended hybrid), Argon2i, or Argon2d.",
            "Enter password text and customize memory cost (KiB), time iterations, and parallelism threads.",
            "Generate the standardized PHC string: $argon2id$v=19$m=...,t=...,p=...$hash.",
            "Copy the resulting string for your backend implementation (PHP, Node.js, Go, Python)."
        ],
        "how_to_steps_bn": [
            "Argon2 ভ্যারিয়েন্ট বেছে নিন: Argon2id (সর্বাধিক সুপারিশকৃত), Argon2i অথবা Argon2d।",
            "পাসওয়ার্ড লিখুন এবং মেমরি কস্ট, ইটারেশন ও থ্রেড কাস্টমাইজ করুন।",
            "স্ট্যান্ডার্ড PHC ফরম্যাট স্ট্রিংটি জেনারেট করুন।",
            "কপি করে আপনার ব্যাকএন্ড কোডে সরাসরি ব্যবহার করুন।"
        ],
        "specs_en": {
            "Variants": "Argon2id (hybrid), Argon2i (side-channel resistant), Argon2d (GPU resistant)",
            "Standard": "Password Hashing Competition (PHC) Winner & RFC 9106",
            "Configurable": "Time cost (t), Memory size (m), Parallelism (p)",
            "Encoding": "Base64 PHC standardized string format"
        },
        "specs_bn": {
            "ভ্যারিয়েন্টসমূহ": "Argon2id, Argon2i, Argon2d",
            "স্ট্যান্ডার্ড": "PHC উইনার ও RFC 9106",
            "কনফিগারেবল": "টাইম কস্ট (t), মেমরি সাইজ (m), প্যারালালিজম (p)",
            "এনকোডিং": "Base64 PHC স্ট্যান্ডার্ড স্ট্রিং ফরম্যাট"
        },
        "faq_en": [
            {"q": "Why is Argon2id the modern gold standard?", "a": "Argon2id won the Password Hashing Competition because it provides both side-channel attack resistance and high memory-hardness to defeat GPU/ASIC attacks."},
            {"q": "What is the difference between Argon2i, Argon2d, and Argon2id?", "a": "Argon2d is optimized for resistance against GPU cracking, Argon2i defends against side-channel timing attacks, and Argon2id combines both for optimal real-world security."}
        ],
        "faq_bn": [
            {"q": "Argon2id কেন আধুনিক ক্রিপ্টোগ্রাফির সেরা স্ট্যান্ডার্ড?", "a": "Argon2id পাসওয়ার্ড হ্যাশিং কম্পিটিশনে বিজয়ী হয়েছে কারণ এটি মেমরি-হার্ড হওয়ায় গ্রাফিক্স কার্ড বা কাস্টম চিপ (ASIC) দিয়েও ক্র্যাক করা যায় না।"},
            {"q": "Argon2id ও Argon2i এর মধ্যে পার্থক্য কী?", "a": "Argon2d জিপিইউ ক্র্যাকিং প্রতিহত করে, Argon2i সাইড-চ্যানেল টাইমিং অ্যাটাক ঠেকায়, আর Argon2id এই দুটির সমন্বয় করে সেরা সুরক্ষা দেয়।"}
        ]
    },
    {
        "slug": "pbkdf2-key-derivator",
        "name_en": "PBKDF2 Key Derivator",
        "name_bn": "PBKDF2 কি ডিরাইভেটর",
        "short_desc_en": "Derive cryptographically strong symmetric keys from passwords and salts using Web Crypto PBKDF2.",
        "short_desc_bn": "ওয়েব ক্রিপ্টো PBKDF2 ব্যবহার করে পাসওয়ার্ড ও সল্ট থেকে কাস্টম ইটারেশনে ক্রিপ্টোগ্রাফিক কি (Key) তৈরি করুন।",
        "meta_title_en": "PBKDF2 Key Derivator — Password-Based Key Derivation | ToolZeno",
        "meta_title_bn": "PBKDF2 কি ডিরাইভেটর — সিকিউর কি জেনারেশন টুল | ToolZeno",
        "meta_desc_en": "Derive 128, 256, or 512-bit encryption keys from passwords using PBKDF2 with HMAC-SHA256 or SHA512. 100% in-browser.",
        "meta_desc_bn": "পাসওয়ার্ড ও সল্ট থেকে কাস্টম ইটারেশন দিয়ে এনক্রিপশন কি (Key) তৈরি করুন সম্পূর্ণ ব্রাউজারে।",
        "tags": ["pbkdf2", "key derivation", "hmac sha256 pbkdf2", "derive cryptographic key", "crypto salt"],
        "how_to_steps_en": [
            "Enter password and provide or generate a random salt string.",
            "Choose pseudo-random function: HMAC-SHA256 (standard) or HMAC-SHA512.",
            "Set iteration count (OWASP recommends 600,000 for SHA-256) and desired key length (e.g. 256 bits).",
            "Click 'Derive Key' to calculate the raw derived key in Hex and Base64 format."
        ],
        "how_to_steps_bn": [
            "পাসওয়ার্ড লিখুন এবং একটি সল্ট (Salt) দিন বা রেন্ডম সল্ট তৈরি করুন।",
            "ফাংশন নির্বাচন করুন: HMAC-SHA256 অথবা HMAC-SHA512।",
            "ইটারেশন সংখ্যা (যেমন ১,০০,০০০ বা ৬,০০,০০০) এবং কী দৈর্ঘ্য (২৫৬ বা ৫১২ বিট) নির্ধারণ করুন।",
            "'Derive Key' বাটনে ক্লিক করে হেক্স ও বেস৬৪ ফরম্যাটে কি (Key) পান।"
        ],
        "specs_en": {
            "Specification": "RFC 2898 / PKCS #5 v2.0",
            "PRFs Supported": "HMAC-SHA256, HMAC-SHA512",
            "Iterations": "1,000 to 1,000,000 rounds",
            "Engine": "Web Crypto API (crypto.subtle.deriveBits)"
        },
        "specs_bn": {
            "স্পেসিফিকেশন": "RFC 2898 / PKCS #5 v2.0",
            "PRF সমর্থিত": "HMAC-SHA256, HMAC-SHA512",
            "ইটারেশন": "১,০০০ থেকে ১০,০০,০০০ রাউন্ডস",
            "ইঞ্জিন": "ওয়েব ক্রিপ্টো API (crypto.subtle.deriveBits)"
        },
        "faq_en": [
            {"q": "What is PBKDF2 used for?", "a": "PBKDF2 turns human passwords into strong, fixed-length binary cryptographic keys suitable for symmetric algorithms like AES-256-GCM. It is used in WPA2 Wi-Fi and Apple Keychain."},
            {"q": "Why are high iterations important?", "a": "Higher iterations force an attacker to spend significantly more CPU time on each guess, making dictionary attacks prohibitively expensive."}
        ],
        "faq_bn": [
            {"q": "PBKDF2 কী কাজে ব্যবহৃত হয়?", "a": "মানুষের তৈরি পাসওয়ার্ড থেকে শক্তিশালী বাইনারি এনক্রিপশন কি (যেমন AES কি) তৈরি করতে PBKDF2 ব্যবহৃত হয়। WPA2 ওয়াইফাই ও অ্যাপল কি-চেইনে এটি ব্যবহৃত।"},
            {"q": "উচ্চ ইটারেশন কেন জরুরি?", "a": "বেশি ইটারেশন থাকলে প্রতি অনুমানে হ্যাকারের প্রসেসরে অনেক সময় ও পাওয়ার নষ্ট হয়, ফলে আক্রমণ চালানো অসম্ভব হয়ে পড়ে।"}
        ]
    },

    # ---------------- 4. SYMMETRIC ENCRYPTION ----------------
    {
        "slug": "aes-encryption-decryption",
        "name_en": "AES Encryption & Decryption (CBC/GCM)",
        "name_bn": "AES এনক্রিপশন ও ডিক্রিপশন (CBC/GCM)",
        "short_desc_en": "Encrypt and decrypt confidential text using industry-standard AES-128, AES-192, and AES-256 in CBC or GCM mode.",
        "short_desc_bn": "গোপনীয় টেক্সট নিরাপদ রাখতে মিলিটারি গ্রেড AES-128, 192 এবং 256 (CBC/GCM মোড) এনক্রিপ্ট ও ডিক্রিপ্ট করুন।",
        "meta_title_en": "AES Encryption & Decryption Tool — 256-bit CBC/GCM Online | ToolZeno",
        "meta_title_bn": "AES এনক্রিপশন ও ডিক্রিপশন টুল — ২৫৬-বিট অনলাইন এনক্রিপ্টর | ToolZeno",
        "meta_desc_en": "Perform AES-256-GCM authenticated encryption and AES-CBC encryption directly in your browser. Encrypt or decrypt with a secret password.",
        "meta_desc_bn": "পাসওয়ার্ড ও সিক্রেট কি দিয়ে যেকোনো বার্তা AES-256 অ্যালগরিদমে এনক্রিপ্ট ও ডিক্রিপ্ট করুন সম্পূর্ণ ক্লায়েন্ট সাইডে।",
        "tags": ["aes encryption", "aes decryption", "aes 256 gcm", "aes cbc", "symmetric encryption"],
        "how_to_steps_en": [
            "To Encrypt: Select mode (AES-256-GCM recommended), enter your secret passphrase and plaintext, then click 'Encrypt'.",
            "Copy the resulting Base64 or Hex ciphertext (includes IV and Auth Tag).",
            "To Decrypt: Paste the ciphertext, provide the exact secret passphrase, and click 'Decrypt'.",
            "View decrypted plain text instantly with zero data transmission."
        ],
        "how_to_steps_bn": [
            "এনক্রিপ্ট করতে: মোড বেছে নিন (AES-256-GCM বাঞ্ছনীয়), সিক্রেট পাসওয়ার্ড ও টেক্সট লিখে 'Encrypt' চাপুন।",
            "আউটপুট থেকে বেস৬৪ সাইফারটেক্সট কপি করে নিন।",
            "ডিক্রিপ্ট করতে: সাইফারটেক্সট পেস্ট করুন, সঠিক পাসওয়ার্ডটি দিন এবং 'Decrypt' বাটনে ক্লিক করুন।",
            "তৎক্ষণাৎ মূল টেক্সট ফিরে পাবেন।"
        ],
        "specs_en": {
            "Cipher Modes": "AES-GCM (Authenticated) and AES-CBC",
            "Key Sizes": "128-bit, 192-bit, 256-bit",
            "Key Derivation": "PBKDF2 with random 128-bit salt",
            "Engine": "Web Crypto API (crypto.subtle.encrypt / decrypt)"
        },
        "specs_bn": {
            "সাইফার মোড": "AES-GCM (অথেন্টিকেটেড) ও AES-CBC",
            "কী সাইজ": "১২৮-বিট, ১৯২-বিট, ২৫৬-বিট",
            "কী ডিরাইভেশন": "PBKDF2 উইথ রেন্ডম ১২৮-বিট সল্ট",
            "ইঞ্জিন": "ওয়েব ক্রিপ্টো API"
        },
        "faq_en": [
            {"q": "Why is AES-GCM preferred over AES-CBC?", "a": "AES-GCM provides authenticated encryption with associated data (AEAD), guaranteeing both confidentiality and data integrity while preventing tampering and padding oracle attacks."},
            {"q": "Can someone decrypt my AES-256 message without the key?", "a": "No. AES-256 is the gold standard used by governments and financial institutions worldwide. Without the correct secret key, it cannot be decrypted."}
        ],
        "faq_bn": [
            {"q": "AES-CBC এর চেয়ে AES-GCM কেন উত্তম?", "a": "AES-GCM এনক্রিপশনের সাথে সাথে ডেটা টেম্পারিং প্রতিরোধ করে। কেউ সাইফারটেক্সটে ১ বিটও পরিবর্তন করলে ডিক্রিপ্ট হতে বাধা দেয়।"},
            {"q": "সিক্রেট পাসওয়ার্ড ছাড়া কি AES-256 ডিক্রিপ্ট করা সম্ভব?", "a": "না। AES-256 বিশ্বের সবচেয়ে নিরাপদ এনক্রিপশন যা ব্যাংক ও সামরিক বাহিনী ব্যবহার করে। পাসওয়ার্ড ছাড়া এটি ভাঙ্গা অসম্ভব।"}
        ]
    },
    {
        "slug": "des-3des-encryption",
        "name_en": "DES & Triple DES (3DES) Tool",
        "name_bn": "DES ও ট্রিপল ডেস (3DES) এনক্রিপশন টুল",
        "short_desc_en": "Encrypt and decrypt text using legacy DES and Triple DES (3DES / DES-EDE3) for legacy enterprise compatibility.",
        "short_desc_bn": "লেগাসি সিস্টেম ও আর্থিক সফটওয়্যারের সামঞ্জস্যের জন্য DES ও Triple DES (3DES) এনক্রিপ্ট ও ডিক্রিপ্ট করুন।",
        "meta_title_en": "DES & 3DES Encryption Tool — Legacy Block Cipher | ToolZeno",
        "meta_title_bn": "DES ও Triple DES এনক্রিপশন টুল — লেগাসি সাইফার কনভার্টার | ToolZeno",
        "meta_desc_en": "Encrypt and decrypt text with legacy DES and Triple DES algorithms. Test legacy compatibility directly in browser.",
        "meta_desc_bn": "ব্রাউজারে DES ও 3DES অ্যালগরিদমের মাধ্যমে টেক্সট এনক্রিপ্ট ও ডিক্রিপ্ট করুন। লেগাসি সিস্টেম টেস্ট করার জন্য উপযোগী।",
        "tags": ["des encryption", "triple des", "3des", "des ede3", "legacy cipher"],
        "how_to_steps_en": [
            "Select algorithm: Triple DES (3DES) or Single DES.",
            "Enter your secret key (24 characters for 3DES, 8 characters for DES) and plain text.",
            "Click 'Encrypt' to generate ciphertext, or paste ciphertext to 'Decrypt'.",
            "Copy the resulting hex or Base64 string."
        ],
        "how_to_steps_bn": [
            "অ্যালগরিদম নির্বাচন করুন: Triple DES (3DES) অথবা Single DES।",
            "সিক্রেট কি লিখুন (3DES এর জন্য ২৪ ক্যারেক্টার, DES এর জন্য ৮ ক্যারেক্টার) এবং টেক্সট দিন।",
            "'Encrypt' বাটনে ক্লিক করে সাইফারটেক্সট তৈরি করুন অথবা ডিক্রিপ্ট ট্যাবে ডিক্রিপ্ট করুন।",
            "আউটপুট স্ট্রিংটি কপি করে নিন।"
        ],
        "specs_en": {
            "Standard": "NIST FIPS 46-3 (Triple Data Encryption Algorithm)",
            "Key Length": "56 bits (DES), 168 bits (3DES / 3-key mode)",
            "Block Size": "64 bits",
            "Status": "Legacy standard (use AES for new applications)"
        },
        "specs_bn": {
            "স্ট্যান্ডার্ড": "NIST FIPS 46-3 (Triple Data Encryption Algorithm)",
            "কী দৈর্ঘ্য": "৫৬ বিট (DES), ১৬৮ বিট (3DES)",
            "ব্লক সাইজ": "৬৪ বিট",
            "স্ট্যাটাস": "লেগাসি স্ট্যান্ডার্ড (নতুন প্রজেক্টে AES বাঞ্ছনীয়)"
        },
        "faq_en": [
            {"q": "Is Single DES still secure?", "a": "No. DES has a tiny 56-bit key that can be brute-forced in hours using modern hardware. Triple DES is stronger but slow, which is why AES replaced both."},
            {"q": "Where is 3DES still found?", "a": "Older banking terminals, ATM payment networks, and legacy mainframe systems often still support Triple DES for backwards compatibility."}
        ],
        "faq_bn": [
            {"q": "Single DES কি এখনও নিরাপদ?", "a": "না। ৫৬-বিট কী সাইজ থাকায় আধুনিক কম্পিউটারে কয়েক ঘণ্টাতেই DES ব্রুট-ফোর্স করা সম্ভব। তাই AES এর জন্ম হয়েছে।"},
            {"q": "3DES এখনও কোথায় ব্যবহৃত হয়?", "a": "পুরানো এটিএম কার্ড প্রসেসিং ও ব্যাংকিং লেগাসি সিস্টেমে ব্যাকওয়ার্ড কম্প্যাটিবিলিটির জন্য 3DES এখনও দেখা যায়।"}
        ]
    },
    {
        "slug": "chacha20-encryption",
        "name_en": "ChaCha20-Poly1305 Tool",
        "name_bn": "ChaCha20-Poly1305 এনক্রিপশন টুল",
        "short_desc_en": "High-speed modern stream cipher encryption and authentication designed by Daniel J. Bernstein (DJB).",
        "short_desc_bn": "আধুনিক উচ্চগতির স্ট্রিম সাইফার ChaCha20-Poly1305 দিয়ে ডেটা এনক্রিপ্ট ও প্রমাণীকরণ (AEAD) করুন।",
        "meta_title_en": "ChaCha20-Poly1305 Encryption Tool — High Speed Stream Cipher | ToolZeno",
        "meta_title_bn": "ChaCha20-Poly1305 এনক্রিপশন টুল — আধুনিক স্ট্রিম সাইফার | ToolZeno",
        "meta_desc_en": "Encrypt and decrypt text with ChaCha20-Poly1305 AEAD cipher. Faster than AES on mobile devices without hardware AES acceleration.",
        "meta_desc_bn": "ChaCha20-Poly1305 অ্যালগরিদমে টেক্সট এনক্রিপ্ট ও ডিক্রিপ্ট করুন। মোবাইলে ও লো-এন্ড প্রসেসরে অতি দ্রুতগতির সাইফার।",
        "tags": ["chacha20", "poly1305", "stream cipher", "chacha20 poly1305", "aead encryption"],
        "how_to_steps_en": [
            "Enter a 256-bit secret key (or generate a random key) and 96-bit nonce.",
            "Type your message into the plaintext box.",
            "Click 'Encrypt' to generate ciphertext combined with the 128-bit Poly1305 authentication tag.",
            "Switch to 'Decrypt' to verify authenticity and recover the original plaintext."
        ],
        "how_to_steps_bn": [
            "২৫৬-বিট সিক্রেট কি এবং ৯৬-বিট নন্স (Nonce) প্রবেশ করান বা রেন্ডম জেনারেট করুন।",
            "প্লেইনটেক্সট বক্সে আপনার বার্তাটি লিখুন।",
            "'Encrypt' বাটনে ক্লিক করে সাইফারটেক্সট ও Poly1305 ট্যাগ তৈরি করুন।",
            "'Decrypt' ট্যাবে গিয়ে আসল টেক্সট পুনরুদ্ধার ও ভেরিফাই করুন।"
        ],
        "specs_en": {
            "Specification": "RFC 8439 (ChaCha20 and Poly1305 for IETF Protocols)",
            "Key Size": "256 bits (32 bytes)",
            "Nonce Size": "96 bits (12 bytes)",
            "Tag Size": "128 bits (16 bytes Poly1305 MAC)"
        },
        "specs_bn": {
            "স্পেসিফিকেশন": "RFC 8439",
            "কী সাইজ": "২৫৬ বিট (৩২ বাইট)",
            "নন্স সাইজ": "৯৬ বিট (১২ বাইট)",
            "ট্যাগ সাইজ": "১২৮ বিট (১৬ বাইট Poly1305 MAC)"
        },
        "faq_en": [
            {"q": "Why is ChaCha20 used in WireGuard and TLS 1.3?", "a": "ChaCha20 is immune to cache-timing attacks and runs significantly faster than AES on mobile phones and IoT chips that lack dedicated hardware AES acceleration."},
            {"q": "What is the role of Poly1305?", "a": "Poly1305 is a high-speed cryptographic authenticator (MAC) that guarantees the ciphertext has not been modified in transit."}
        ],
        "faq_bn": [
            {"q": "WireGuard এবং TLS 1.3 এ কেন ChaCha20 ব্যবহৃত হয়?", "a": "ChaCha20 মোবাইল ফোনে হার্ডওয়্যার অ্যাকসিলারেশন ছাড়াও AES এর চেয়ে দ্রুত চলে এবং টাইমিং অ্যাটাক থেকে সম্পূর্ণ নিরাপদ।"},
            {"q": "Poly1305 এর কাজ কী?", "a": "এটি একটি ক্রিপ্টোগ্রাফিক অথেনটিকেশন ট্যাগ তৈরি করে যা নিশ্চিত করে সাইফারটেক্সটে কোনো পরিবর্তন করা হয়নি।"}
        ]
    },

    # ---------------- 5. ASYMMETRIC & PUBLIC-KEY ----------------
    {
        "slug": "rsa-key-generator",
        "name_en": "RSA Key Pair Generator",
        "name_bn": "RSA কি-পেয়ার জেনারেটর",
        "short_desc_en": "Generate 2048-bit or 4096-bit RSA public and private key pairs directly in browser in standard PEM format.",
        "short_desc_bn": "ব্রাউজারে সরাসরি ২০৪৮ বা ৪০৯৬ বিটের RSA পাবলিক ও প্রাইভেট কি তৈরি করুন স্ট্যান্ডার্ড PEM ফরম্যাটে।",
        "meta_title_en": "RSA Key Pair Generator — 2048/4096-bit PEM Keys Online | ToolZeno",
        "meta_title_bn": "RSA কি-পেয়ার জেনারেটর — পাবলিক ও প্রাইভেট কি মেকার | ToolZeno",
        "meta_desc_en": "Generate public (SPKI) and private (PKCS#8) RSA keys online for SSH, SSL, and encryption. 100% private in browser memory.",
        "meta_desc_bn": "SSH, SSL এবং এনক্রিপশনের জন্য ২০৪৮ ও ৪০৯৬ বিট RSA কি-পেয়ার তৈরি করুন। সম্পূর্ণ লোকাল ও গোপনীয়।",
        "tags": ["rsa key generator", "rsa 2048", "rsa 4096", "public private key", "pem key pair"],
        "how_to_steps_en": [
            "Select key size: 2048-bit (industry standard) or 4096-bit (maximum security).",
            "Click 'Generate RSA Key Pair'. The Web Crypto API computes prime numbers and keys.",
            "Download or copy the Public Key (-----BEGIN PUBLIC KEY-----).",
            "Download or copy the Private Key (-----BEGIN PRIVATE KEY-----). Never share your private key!"
        ],
        "how_to_steps_bn": [
            "কি-সাইজ নির্বাচন করুন: ২০৪৮-বিট (স্ট্যান্ডার্ড) অথবা ৪০৯৬-বিট (সর্বোচ্চ নিরাপত্তা)।",
            "'Generate RSA Key Pair' বাটনে ক্লিক করুন। ওয়েব ক্রিপ্টো API দিয়ে কি-পেয়ার তৈরি হবে।",
            "পাবলিক কি (Public Key) কপি বা ডাউনলোড করুন।",
            "প্রাইভেট কি (Private Key) কপি বা ডাউনলোড করে নিরাপদে সংরক্ষণ করুন।"
        ],
        "specs_en": {
            "Key Formats": "X.509 SPKI (Public Key) & PKCS #8 (Private Key)",
            "Modulus Sizes": "2048-bit, 3072-bit, 4096-bit",
            "Public Exponent": "65537 (0x10001)",
            "Engine": "crypto.subtle.generateKey('RSA-OAEP')"
        },
        "specs_bn": {
            "কি ফরম্যাট": "X.509 SPKI (পাবলিক) ও PKCS #8 (প্রাইভেট)",
            "মডুলাস সাইজ": "২০৪৮-বিট, ৩০৭২-বিট, ৪০৯৬-বিট",
            "পাবলিক এক্সপোনেন্ট": "৬৫৫৩৭",
            "ইঞ্জিন": "ওয়েব ক্রিপ্টো API"
        },
        "faq_en": [
            {"q": "Is 2048-bit RSA still secure?", "a": "Yes. NIST recommends 2048-bit RSA keys through at least 2030. For ultra-sensitive long-term security, 4096-bit keys are recommended."},
            {"q": "Is the private key safe when generated here?", "a": "Yes. Keys are generated exclusively inside your browser using the browser's native cryptography engine. Nothing is transmitted over the internet."}
        ],
        "faq_bn": [
            {"q": "২০৪৮-বিট RSA কি এখনও নিরাপদ?", "a": "হ্যাঁ, ২০৪৮-বিট RSA ২০৩০ সাল পর্যন্ত আন্তর্জাতিকভাবে নিরাপদ হিসেবে স্বীকৃত। দীর্ঘমেয়াদী সুরক্ষার জন্য ৪০৯৬-বিট বাঞ্ছনীয়।"},
            {"q": "এখানে প্রাইভেট কি তৈরি করা কি নিরাপদ?", "a": "শতভাগ নিরাপদ। ব্রাউজারের নিজস্ব মেমরির ভেতরে ক্রিপ্টো ইঞ্জিনে এটি তৈরি হয়, ইন্টারনেটে কোনো ডাটা যায় না।"}
        ]
    },
    {
        "slug": "rsa-encrypt-decrypt",
        "name_en": "RSA Encrypt & Decrypt Tool",
        "name_bn": "RSA এনক্রিপ্ট ও ডিক্রিপ্ট টুল",
        "short_desc_en": "Encrypt messages with an RSA Public Key and decrypt them with the matching RSA Private Key using RSA-OAEP.",
        "short_desc_bn": "RSA পাবলিক কি দিয়ে বার্তা এনক্রিপ্ট করুন এবং প্রাইভেট কি দিয়ে ডিক্রিপ্ট করুন ব্রাউজারে।",
        "meta_title_en": "RSA Encrypt & Decrypt Tool — RSA-OAEP Public Key Encryption | ToolZeno",
        "meta_title_bn": "RSA এনক্রিপ্ট ও ডিক্রিপ্ট টুল — পাবলিক ও প্রাইভেট কি এনক্রিপশন | ToolZeno",
        "meta_desc_en": "Encrypt plaintext with an RSA public key and decrypt ciphertext with your private key online. Pure client-side RSA-OAEP.",
        "meta_desc_bn": "ব্রাউজারে সরাসরি পাবলিক কি দিয়ে এনক্রিপ্ট এবং প্রাইভেট কি দিয়ে ডিক্রিপ্ট করুন। ১০০% নিরাপদ ও প্রাইভেট।",
        "tags": ["rsa encrypt", "rsa decrypt", "rsa-oaep", "public key encryption", "asymmetric encryption"],
        "how_to_steps_en": [
            "To Encrypt: Paste recipient's Public Key PEM, type message, click 'Encrypt with Public Key'.",
            "Copy the resulting Base64 ciphertext.",
            "To Decrypt: Paste your Private Key PEM and ciphertext, click 'Decrypt with Private Key'.",
            "View decrypted plain text instantly."
        ],
        "how_to_steps_bn": [
            "এনক্রিপ্ট করতে: প্রাপকের পাবলিক কি পেস্ট করুন, বার্তা লিখুন এবং 'Encrypt' বাটনে ক্লিক করুন।",
            "প্রাপ্ত বেস৬৪ সাইফারটেক্সট কপি করে প্রাপককে পাঠান।",
            "ডিক্রিপ্ট করতে: আপনার প্রাইভেট কি এবং সাইফারটেক্সট পেস্ট করে 'Decrypt' বাটনে ক্লিক করুন।",
            "মুহূর্তেই মূল বার্তাটি দেখতে পাবেন।"
        ],
        "specs_en": {
            "Padding Scheme": "RSA-OAEP with SHA-256 hash",
            "Supported Keys": "PEM formatted PKCS#8 and SPKI keys",
            "Output Format": "Standard Base64 string",
            "Engine": "Web Crypto API (crypto.subtle.encrypt / decrypt)"
        },
        "specs_bn": {
            "প্যাডিং স্কিম": "RSA-OAEP উইথ SHA-256",
            "কী ফরম্যাট": "PEM ফরম্যাট (PKCS#8 ও SPKI)",
            "আউটপুট ফরম্যাট": "স্ট্যান্ডার্ড Base64",
            "ইঞ্জিন": "ওয়েব ক্রিপ্টো API"
        },
        "faq_en": [
            {"q": "Can everyone read my message encrypted with a public key?", "a": "No. Anyone can encrypt using your public key, but only the person holding the secret private key can decrypt and read it."},
            {"q": "Why is RSA limited in message length?", "a": "RSA cannot encrypt data larger than its key modulus (around 190 bytes for 2048-bit keys). In practice, RSA encrypts a symmetric AES key, which encrypts large payloads."}
        ],
        "faq_bn": [
            {"q": "পাবলিক কি দিয়ে এনক্রিপ্ট করা মেসেজ কি সবাই পড়তে পারে?", "a": "না। পাবলিক কি দিয়ে যে কেউ এনক্রিপ্ট করতে পারে, কিন্তু এটি খোলার ক্ষমতা কেবল প্রাইভেট কি এর মালিকেরই থাকে।"},
            {"q": "RSA তে বড় মেসেজ দেওয়া যায় না কেন?", "a": "RSA এর গণিত অনুযায়ী এটি নির্দিষ্ট বাইটের বেশি এনক্রিপ্ট করতে পারে না। বাস্তব জগতে বড় ফাইলের জন্য হাইব্রিড এনক্রিপশন (RSA + AES) ব্যবহৃত হয়।"}
        ]
    },
    {
        "slug": "ecdsa-key-generator",
        "name_en": "ECDSA / Ed25519 Key Generator",
        "name_bn": "ECDSA ও Ed25519 কি-পেয়ার জেনারেটর",
        "short_desc_en": "Generate modern Elliptic Curve cryptography key pairs (NIST P-256, P-384, P-521, and Ed25519) in PEM and JWK.",
        "short_desc_bn": "আধুনিক ইলিপ্টিক কার্ভ ক্রিপ্টোগ্রাফি কি-পেয়ার (P-256, P-384, P-521 ও Ed25519) তৈরি করুন।",
        "meta_title_en": "ECDSA Key Pair Generator — Elliptic Curve P-256, P-384 | ToolZeno",
        "meta_title_bn": "ECDSA কি-পেয়ার জেনারেটর — ইলিপ্টিক কার্ভ কি মেকার | ToolZeno",
        "meta_desc_en": "Create high-performance Elliptic Curve key pairs for digital signatures, WebAuthn, and TLS. Free, private, and client-side.",
        "meta_desc_bn": "ডিজিটাল সিগনেচার ও ওয়েব অথেনটিকেশনের জন্য ইলিপ্টিক কার্ভ কি-পেয়ার তৈরি করুন সরাসরি ব্রাউজারে।",
        "tags": ["ecdsa key generator", "elliptic curve", "nist p256", "ed25519", "jwk key generator"],
        "how_to_steps_en": [
            "Select Named Curve: P-256 (prime256v1 / secp256r1), P-384, or P-521.",
            "Click 'Generate EC Key Pair'.",
            "View outputs formatted as standard PEM keys and JSON Web Key (JWK) objects.",
            "Download or copy keys directly to your project."
        ],
        "how_to_steps_bn": [
            "কার্ভ নির্বাচন করুন: P-256 (সবচেয়ে বেশি ব্যবহৃত), P-384 অথবা P-521।",
            "'Generate EC Key Pair' বাটনে ক্লিক করুন।",
            "PEM এবং JSON Web Key (JWK) উভয় ফরম্যাটেই কি-পেয়ার তৈরি হবে।",
            "কপি বা ডাউনলোড করে আপনার প্রজেক্টে ব্যবহার করুন।"
        ],
        "specs_en": {
            "Curves Supported": "NIST P-256, P-384, P-521",
            "Formats": "PEM (SubjectPublicKeyInfo & PKCS #8) and JSON Web Key (JWK)",
            "Engine": "Web Crypto API (crypto.subtle.generateKey('ECDSA'))",
            "Standard": "ANSI X9.62 & FIPS 186-4"
        },
        "specs_bn": {
            "সমর্থিত কার্ভ": "NIST P-256, P-384, P-521",
            "ফরম্যাট": "PEM ও JSON Web Key (JWK)",
            "ইঞ্জিন": "ওয়েব ক্রিপ্টো API",
            "স্ট্যান্ডার্ড": "ANSI X9.62 ও FIPS 186-4"
        },
        "faq_en": [
            {"q": "Why is ECC preferred over RSA in modern protocols?", "a": "Elliptic Curve Cryptography provides equivalent security to RSA with drastically smaller keys (a 256-bit ECC key equals a 3072-bit RSA key), reducing bandwidth and CPU usage."},
            {"q": "Where is ECDSA used?", "a": "ECDSA is used in TLS/HTTPS certificates, Bitcoin/Ethereum transaction signing, Apple Touch ID/Face ID secure enclaves, and SSH keys."}
        ],
        "faq_bn": [
            {"q": "RSA এর চেয়ে ইলিপ্টিক কার্ভ (ECC) কেন জনপ্রিয়?", "a": "ECC অনেক ছোট কি-সাইজে RSA এর চেয়ে বেশি নিরাপত্তা দেয়। ২৫৬-বিট ECC কি প্রায় ৩০৭২-বিট RSA এর সমান শক্তিশালী, ফলে ব্যান্ডউইথ ও প্রসেসর সাশ্রয় হয়।"},
            {"q": "ECDSA কোথায় ব্যবহৃত হয়?", "a": "আধুনিক HTTPS, ব্লকচেইন ট্রানজেকশন, অ্যাপল টাচ আইডি এবং SSH কি-তে ECDSA ব্যাপকভাবে ব্যবহৃত হয়।"}
        ]
    },
    {
        "slug": "digital-signature-tool",
        "name_en": "Digital Signature Signer & Verifier",
        "name_bn": "ডিজিটাল সিগনেচার সাইনার ও ভেরিফায়ার",
        "short_desc_en": "Sign documents or text with a private key and verify cryptographic digital signatures with the matching public key.",
        "short_desc_bn": "প্রাইভেট কি দিয়ে ডকুমেন্টে ডিজিটাল স্বাক্ষর করুন এবং পাবলিক কি দিয়ে স্বাক্ষরের সত্যতা যাচাই করুন।",
        "meta_title_en": "Digital Signature Signer & Verifier — Public Key Signing | ToolZeno",
        "meta_title_bn": "ডিজিটাল সিগনেচার সাইনার ও ভেরিফায়ার — স্বাক্ষর যাচাই টুল | ToolZeno",
        "meta_desc_en": "Cryptographically sign text payloads and verify authenticity and tamper-resistance using RSA and ECDSA public key cryptography.",
        "meta_desc_bn": "ব্রাউজারে সরাসরি টেক্সট ডিজিটাল সাইন করুন এবং পাবলিক কি দিয়ে ভেরিফাই করুন। শতভাগ ক্লায়েন্ট সাইড ও সুরক্ষিত।",
        "tags": ["digital signature", "sign data", "verify signature", "ecdsa sign", "rsa sign verify"],
        "how_to_steps_en": [
            "To Sign: Provide private key and text message, click 'Sign Message' to produce cryptographic signature in Base64.",
            "To Verify: Provide public key, text message, and signature, click 'Verify Signature'.",
            "Or click 'Generate Sample Keys & Test' for an instant 1-click demonstration.",
            "Confirm validity: Signature passes (green) or fails tamper detection (red)."
        ],
        "how_to_steps_bn": [
            "সাইন করতে: প্রাইভেট কি এবং মেসেজ দিয়ে 'Sign Message' বাটনে ক্লিক করে বেস৬৪ সিগনেচার তৈরি করুন।",
            "ভেরিফাই করতে: পাবলিক কি, মূল মেসেজ এবং সিগনেচার দিয়ে 'Verify Signature' বাটনে চাপ দিন।",
            "তাৎক্ষণিক পরীক্ষার জন্য 'Generate Sample Keys & Test' বাটনে ক্লিক করতে পারেন।",
            "স্বাক্ষর সঠিক হলে সবুজ রঙের ভেরিফিকেশন মেসেজ প্রদর্শিত হবে।"
        ],
        "specs_en": {
            "Algorithms": "ECDSA (P-256 with SHA-256) and RSASSA-PKCS1-v1_5",
            "Signature Format": "Base64 and Hexadecimal",
            "Verification Status": "Tamper-evident mathematical verification",
            "Engine": "Web Crypto API (crypto.subtle.sign / verify)"
        },
        "specs_bn": {
            "অ্যালগরিদম": "ECDSA (P-256 with SHA-256) এবং RSASSA-PKCS1-v1_5",
            "সিগনেচার ফরম্যাট": "Base64 ও Hexadecimal",
            "স্ট্যাটাস": "ম্যাথমেটিক্যাল টেম্পার ডিটেকশন",
            "ইঞ্জিন": "ওয়েব ক্রিপ্টো API"
        },
        "faq_en": [
            {"q": "What does a digital signature prove?", "a": "A digital signature proves two essential things: Authenticity (the message was signed by the private key holder) and Integrity (the message was not modified in transit by even 1 character)."},
            {"q": "What happens if someone modifies the signed message?", "a": "Even changing a single comma or space will cause mathematical verification to immediately fail, warning the recipient of tampering."}
        ],
        "faq_bn": [
            {"q": "ডিজিটাল সিগনেচার কী প্রমাণ করে?", "a": "এটি প্রমাণ করে দুটি বিষয়: তথ্যের উৎস আসল (প্রাইভেট কি এর মালিকই পাঠিয়েছেন) এবং তথ্যের অখণ্ডতা (মেসেজে কোনো পরিবর্তন করা হয়নি)।"},
            {"q": "মেসেজের ১টি অক্ষর পরিবর্তন করলে কী হবে?", "a": "মেসেজে সামান্যতম পরিবর্তন হলেও গাণিতিক ভেরিফিকেশন সাথে সাথেই ফেইল করবে এবং টেম্পারিং ধরা পড়বে।"}
        ]
    },

    # ---------------- 6. HMAC & TOKENS ----------------
    {
        "slug": "hmac-generator",
        "name_en": "HMAC Hash Generator",
        "name_bn": "HMAC হ্যাশ জেনারেটর",
        "short_desc_en": "Generate keyed-hash message authentication codes (HMAC-SHA256, HMAC-SHA512, HMAC-MD5) for API request authentication.",
        "short_desc_bn": "এপিআই রিকোয়েস্ট অথেনটিকেশনের জন্য সিক্রেট কি দিয়ে HMAC-SHA256 ও HMAC-SHA512 হ্যাশ সিগনেচার তৈরি করুন।",
        "meta_title_en": "HMAC Generator — Keyed Hash Message Authentication Code | ToolZeno",
        "meta_title_bn": "HMAC হ্যাশ জেনারেটর — এপিআই সিক্রেট কি হ্যাশার | ToolZeno",
        "meta_desc_en": "Calculate keyed HMAC signatures for Stripe, AWS, GitHub, and custom webhooks online. 100% private in browser.",
        "meta_desc_bn": "API সিক্রেট কি ও পেলোড দিয়ে রিয়েল-টাইমে HMAC সিগনেচার বের করুন। সম্পূর্ণ ক্লায়েন্ট সাইড ও নিরাপদ।",
        "tags": ["hmac generator", "hmac sha256", "hmac sha512", "api secret hash", "message authentication code"],
        "how_to_steps_en": [
            "Select HMAC algorithm: SHA-256 (most common for modern APIs), SHA-512, SHA-384, or SHA-1.",
            "Enter your shared secret key and the message payload to sign.",
            "HMAC Hex digest and Base64 output update automatically in real-time.",
            "Click 'Copy Hex' or 'Copy Base64' to use in your API headers or webhooks."
        ],
        "how_to_steps_bn": [
            "অ্যালগরিদম সিলেক্ট করুন: SHA-256 (সবচেয়ে জনপ্রিয়), SHA-512, SHA-384 অথবা SHA-1।",
            "আপনার এপিআই সিক্রেট কি (Secret Key) এবং পেলোড টেক্সটটি লিখুন।",
            "টাইপ করার সাথে সাথেই রিয়েল-টাইমে হেক্স ও বেস৬৪ সিগনেচার তৈরি হবে।",
            "'Copy' বাটনে ক্লিক করে সিগনেচারটি আপনার কোড বা হেডারে ব্যবহার করুন।"
        ],
        "specs_en": {
            "Specification": "RFC 2104 (HMAC: Keyed-Hashing for Message Authentication)",
            "Hash Functions": "SHA-256, SHA-512, SHA-384, SHA-1",
            "Engine": "Web Crypto API (crypto.subtle.sign('HMAC'))",
            "Output Encodings": "Hexadecimal (lowercase/uppercase) and Base64"
        },
        "specs_bn": {
            "স্পেসিফিকেশন": "RFC 2104",
            "হ্যাশ ফাংশনসমূহ": "SHA-256, SHA-512, SHA-384, SHA-1",
            "ইঞ্জিন": "ওয়েব ক্রিপ্টো API",
            "আউটপুট এনকোডিং": "Hexadecimal ও Base64"
        },
        "faq_en": [
            {"q": "What is an HMAC used for?", "a": "HMACs verify both data integrity and message authenticity. Payment gateways (Stripe, PayPal) and webhooks use HMAC to guarantee requests originate from legitimate partners without revealing the secret key."},
            {"q": "How is HMAC different from a standard hash?", "a": "A standard hash (like SHA-256) only checks integrity. An HMAC combines the message with a shared secret key, proving that only someone holding the key could have created the signature."}
        ],
        "faq_bn": [
            {"q": "HMAC কী কাজে লাগে?", "a": "পেমেন্ট গেটওয়ে (Stripe, SSLCommerz) ও ওয়েবহুক ভেরিফিকেশনে HMAC ব্যবহৃত হয়, যাতে নিশ্চিত হওয়া যায় মেসেজটি সঠিক ব্যক্তি পাঠিয়েছে এবং পথে পরিবর্তিত হয়নি।"},
            {"q": "সাধারণ হ্যাশের চেয়ে HMAC আলাদা কেন?", "a": "সাধারণ হ্যাশে কোনো পাসওয়ার্ড থাকে না। HMAC এ একটি গোপন সিক্রেট কি যুক্ত থাকে, ফলে প্রমাণ হয় প্রেরক আসল কি এর মালিক।"}
        ]
    },
    {
        "slug": "jwt-debugger",
        "name_en": "JWT Debugger & Signature Validator",
        "name_bn": "JWT ডিবাগার ও সিগনেচার ভ্যালিডেটর",
        "short_desc_en": "Decode JSON Web Tokens (JWT), inspect claims, check expiry status, and verify HMAC-SHA256 signatures.",
        "short_desc_bn": "জেসন ওয়েব টোকেন (JWT) ডিকোড করুন, ক্লেইমস ও মেয়াদ পরীক্ষা করুন এবং সিক্রেট কি দিয়ে সিগনেচার ভ্যালিডেট করুন।",
        "meta_title_en": "JWT Debugger & Decoder — Verify JWT Signatures Online | ToolZeno",
        "meta_title_bn": "JWT ডিবাগার ও ডিকোডার — জেসন ওয়েব টোকেন ভ্যালিডেটর | ToolZeno",
        "meta_desc_en": "Paste any JWT to parse Header and Payload JSON, verify expiration timestamp, and validate HMAC signatures. 100% private.",
        "meta_desc_bn": "ব্রাউজারে নিরাপদে JWT টোকেন ডিকোড ও ডিবাগ করুন। এক্সপায়ারি টাইমার ও সিগনেচার ভেরিফিকেশন সুবিধাসহ।",
        "tags": ["jwt debugger", "jwt decoder", "json web token", "verify jwt signature", "jwt claims"],
        "how_to_steps_en": [
            "Paste your encoded JWT string (header.payload.signature) into the token input box.",
            "View automatically parsed Header and Payload formatted JSON.",
            "Check expiration status: active, expired, or never expires.",
            "Enter your HMAC secret key to verify the cryptographic signature live."
        ],
        "how_to_steps_bn": [
            "ইনপুট বক্সে আপনার এনকোডেড JWT টোকেনটি পেস্ট করুন।",
            "টোকেনের হেডার এবং পেলোড সাথে সাথেই সুন্দর ফরম্যাট করা JSON আকারে দেখতে পাবেন।",
            "টোকেনের মেয়াদ শেষ হয়েছে কিনা তা স্ট্যাটাস বক্সে দেখুন।",
            "সিক্রেট কি প্রবেশ করিয়ে সিগনেচার ম্যাচ করেছে কিনা যাচাই করুন।"
        ],
        "specs_en": {
            "Specification": "RFC 7519 JSON Web Token (JWT)",
            "Algorithms": "HS256 (HMAC-SHA256), HS384, HS512, RS256 decoder",
            "Time Parsing": "Epoch timestamps (exp, iat, nbf) converted to local human dates",
            "Execution": "100% Client-side. Secrets never sent over network."
        },
        "specs_bn": {
            "স্পেসিফিকেশন": "RFC 7519 JSON Web Token (JWT)",
            "অ্যালগরিদম": "HS256, HS384, HS512, RS256",
            "টাইম পার্সিং": "ইপোক টাইমস্ট্যাম্প থেকে লোকাল ডেট কনভার্সন",
            "এক্সিকিউশন": "১০০% ক্লায়েন্ট-সাইড"
        },
        "faq_en": [
            {"q": "Is it safe to paste production JWTs here?", "a": "Yes. This tool runs 100% in your local browser window. No token data, claims, or secret keys are sent to our servers."},
            {"q": "What does an expired JWT mean?", "a": "When the 'exp' claim is in the past, API servers will reject the token with HTTP 401 Unauthorized, requiring the client to refresh its session."}
        ],
        "faq_bn": [
            {"q": "প্রোডাকশন JWT টোকেন এখানে পেস্ট করা কি নিরাপদ?", "a": "হ্যাঁ, সম্পূর্ণ নিরাপদ। কোনো টোকেন বা সিক্রেট কি আমাদের সার্ভারে পাঠানো হয় না, সম্পূর্ণ ব্রাউজারে ডিকোড হয়।"},
            {"q": "JWT টোকেন এক্সপায়ার হলে কী হয়?", "a": "টোকেনের 'exp' পার হয়ে গেলে সার্ভার রিকোয়েস্ট রিজেক্ট করে ৪০১ আনঅথরাইজড ফেরত দেয়, ফলে ব্যবহারকারীকে পুনরায় লগইন করতে হয়।"}
        ]
    },
    {
        "slug": "totp-authenticator-generator",
        "name_en": "TOTP / HOTP Authenticator Code Generator",
        "name_bn": "TOTP / HOTP অথেন্টিকেটর কোড জেনারেটর",
        "short_desc_en": "Generate live 6-digit two-factor authentication (2FA) OTP codes from Base32 secrets with a real-time countdown timer.",
        "short_desc_bn": "২-ফ্যাক্টর অথেনটিকেশন (2FA) সিক্রেট কি থেকে লাইভ ৬-ডিজিট ওটিপি কোড এবং কাউন্টডাউন টাইমার দেখুন।",
        "meta_title_en": "TOTP Authenticator Generator — Online 2FA OTP Codes | ToolZeno",
        "meta_title_bn": "TOTP অথেন্টিকেটর কোড জেনারেটর — লাইভ 2FA ওটিপি কোড | ToolZeno",
        "meta_desc_en": "Generate time-based OTP codes from Base32 secrets (Google Authenticator compatible). Real-time 30-second countdown wheel. 100% private.",
        "meta_desc_bn": "গুগল অথেন্টিকেটর সামঞ্জস্যপূর্ণ ৬-ডিজিট 2FA কোড তৈরি করুন সরাসরি ব্রাউজারে। লাইভ টাইমার ও সম্পূর্ণ নিরাপদ।",
        "tags": ["totp generator", "2fa code generator", "google authenticator online", "hotp code", "otp generator"],
        "how_to_steps_en": [
            "Enter your Base32 secret key (e.g. JBSWY3DPEHPK3PXP) or click 'Sample Key'.",
            "The tool computes the current 6-digit TOTP code in real-time.",
            "Watch the 30-second circular countdown timer wheel before the code refreshes.",
            "Click 'Copy Code' to use for 2FA login verification."
        ],
        "how_to_steps_bn": [
            "আপনার Base32 সিক্রেট কি লিখুন (অথবা 'Sample Key' বাটনে ক্লিক করুন)।",
            "মুহূর্তেই বর্তমান সময়ের ৬-ডিজিট 2FA কোড তৈরি হবে।",
            "৩০ সেকেন্ডের কাউন্টডাউন টাইমারটি দেখুন যা কোডটি রিফ্রেশ হওয়ার সময় নির্দেশ করে।",
            "'Copy Code' বাটনে ক্লিক করে লগইন ভেরিফিকেশনে ব্যবহার করুন।"
        ],
        "specs_en": {
            "RFC Standard": "RFC 6238 (TOTP) & RFC 4226 (HOTP)",
            "Secret Encoding": "RFC 4648 Base32 alphabet",
            "Time Step": "30 seconds window (HMAC-SHA1)",
            "Privacy": "100% local time calculations via Web Crypto API"
        },
        "specs_bn": {
            "RFC স্ট্যান্ডার্ড": "RFC 6238 (TOTP) ও RFC 4226 (HOTP)",
            "এনকোডিং": "RFC 4648 Base32",
            "টাইম উইন্ডো": "৩০ সেকেন্ড (HMAC-SHA1)",
            "প্রাইভেসি": "১০০% লোকাল ব্রাউজার ক্যালকুলেশন"
        },
        "faq_en": [
            {"q": "Is this compatible with Google Authenticator and Authy?", "a": "Yes. Google Authenticator, Microsoft Authenticator, and Authy all adhere to standard RFC 6238 TOTP using HMAC-SHA1 and 30-second time intervals."},
            {"q": "What happens if my device clock is wrong?", "a": "TOTP relies on the Unix timestamp. If your system clock is out of sync by more than 30 seconds, the generated code may not match the server's expected code."}
        ],
        "faq_bn": [
            {"q": "এটি কি গুগল অথেন্টিকেটরের সাথে মিলবে?", "a": "হ্যাঁ। গুগল অথেন্টিকেটর ও মাইক্রোসফট অথেন্টিকেটর উভয়েই RFC 6238 TOTP স্ট্যান্ডার্ড অনুসরণ করে, তাই একই কোড পাওয়া যাবে।"},
            {"q": "ডিভাইসের ঘড়ির সময় ভুল থাকলে কি সমস্যা হবে?", "a": "হ্যাঁ, TOTP সঠিক সময়ের ওপর নির্ভরশীল। ঘড়ির সময় ৩০ সেকেন্ডের বেশি এদিক-ওদিক থাকলে কোড ম্যাচ করবে না।"}
        ]
    },

    # ---------------- 7. SSL & CERTIFICATES ----------------
    {
        "slug": "csr-decoder",
        "name_en": "CSR (Certificate Signing Request) Decoder",
        "name_bn": "CSR ডিকোডার (Certificate Signing Request)",
        "short_desc_en": "Decode and inspect PEM-encoded Certificate Signing Requests (CSR) to verify Common Name, Organization, and SANs.",
        "short_desc_bn": "PEM ফরম্যাটের CSR ফাইল ডিকোড করে কমন নেম (CN), অর্গানাইজেশন, কান্ট্রি ও অল্টারনেটিভ নেম (SAN) দেখুন।",
        "meta_title_en": "CSR Decoder — Inspect Certificate Signing Requests Online | ToolZeno",
        "meta_title_bn": "CSR ডিকোডার — সার্টিফিকেট সাইনিং রিকোয়েস্ট ডিকোডার | ToolZeno",
        "meta_desc_en": "Decode PEM CSR requests online. Verify domain name, organization, public key size, and signature algorithm before purchasing an SSL certificate.",
        "meta_desc_bn": "এসএসএল সার্টিফিকেট ক্রয়ের আগে CSR ফাইল ডিকোড করে ডোমেইন নেম ও কোম্পানি তথ্য যাচাই করুন সম্পূর্ণ ফ্রিতে।",
        "tags": ["csr decoder", "decode csr", "certificate signing request", "ssl csr viewer", "x509 csr"],
        "how_to_steps_en": [
            "Paste your CSR beginning with '-----BEGIN CERTIFICATE REQUEST-----' into the input box.",
            "The parser extracts ASN.1 structures and decoded subject attributes.",
            "Review Common Name (CN), Organization (O), Country (C), Key Size, and Subject Alternative Names (SANs).",
            "Verify all details match your domain before submitting to your Certificate Authority (CA)."
        ],
        "how_to_steps_bn": [
            "ইনপুট বক্সে আপনার '-----BEGIN CERTIFICATE REQUEST-----' দিয়ে শুরু হওয়া CSR পেস্ট করুন।",
            "টুলটি সাথে সাথেই CSR এর ভেতরের সকল তথ্য ডিকোড করে সাজিয়ে দেবে।",
            "কমন নেম (CN), কোম্পানি (O), কান্ট্রি এবং অল্টারনেটিভ ডোমেইন (SAN) ঠিক আছে কিনা মিলিয়ে নিন।",
            "সিএ (CA) প্রোভাইডারের কাছে সাবমিট করার আগে নিশ্চিত হয়ে নিন।"
        ],
        "specs_en": {
            "Format": "PKCS #10 PEM formatted Certificate Signing Request",
            "Attributes Parsed": "CN, O, OU, L, ST, C, SANs, Public Key Algorithm, Bit Length",
            "Engine": "Client-side ASN.1 Parser",
            "Privacy Guarantee": "CSR processed entirely in browser memory"
        },
        "specs_bn": {
            "ফরম্যাট": "PKCS #10 PEM ফরম্যাট",
            "ডিকোডেড তথ্য": "CN, O, OU, L, ST, C, SANs, পাবলিক কি টাইপ ও সাইজ",
            "ইঞ্জিন": "ক্লায়েন্ট-সাইড ASN.1 পার্সার",
            "প্রাইভেসি": "১০০% ব্রাউজার মেমরিতে প্রসেসড"
        },
        "faq_en": [
            {"q": "Does a CSR contain my private key?", "a": "No! A CSR only contains your Public Key and identity details. Your private key stays securely on your server and should never be shared."},
            {"q": "What are SANs (Subject Alternative Names)?", "a": "SANs allow a single SSL certificate to secure multiple domains or subdomains (e.g., example.com, www.example.com, api.example.com)."}
        ],
        "faq_bn": [
            {"q": "CSR ফাইলে কি প্রাইভেট কি থাকে?", "a": "না! CSR ফাইলে কেবল পাবলিক কি এবং আপনার ডোমেইন তথ্য থাকে। প্রাইভেট কি সবসময় নিজের সার্ভারে নিরাপদে গোপন রাখতে হয়।"},
            {"q": "SAN (Subject Alternative Name) কী?", "a": "SAN এর মাধ্যমে একটিমাত্র এসএসএল সার্টিফিকেট দিয়ে একাধিক ডোমেইন বা সাব-ডোমেইন (যেমন www ও api) সুরক্ষিত রাখা যায়।"}
        ]
    },
    {
        "slug": "ssl-certificate-decoder",
        "name_en": "SSL / X.509 Certificate Decoder",
        "name_bn": "SSL / X.509 সার্টিফিকেট ডিকোডার",
        "short_desc_en": "Decode PEM-encoded X.509 SSL certificates (.crt, .pem) to view issuer, validity dates, subject, and fingerprints.",
        "short_desc_bn": "PEM ফরম্যাটের SSL সার্টিফিকেট ডিকোড করে মেয়াদ, ইস্যুয়ার, কমন নেম ও ফিঙ্গারপ্রিন্ট তথ্য দেখুন।",
        "meta_title_en": "SSL Certificate Decoder — X.509 PEM Certificate Viewer | ToolZeno",
        "meta_title_bn": "SSL সার্টিফিকেট ডিকোডার — X.509 PEM সার্টিফিকেট প্রিভিউ | ToolZeno",
        "meta_desc_en": "Parse and inspect SSL certificates online. Check validity period, days remaining, issuing CA, serial number, and SHA-256 fingerprint.",
        "meta_desc_bn": "ব্রাউজারে সরাসরি SSL সার্টিফিকেট ডিকোড করে এর মেয়াদ, ইস্যুকারী কর্তৃপক্ষ ও নিরাপত্তা বৈশিষ্ট্য যাচাই করুন।",
        "tags": ["ssl decoder", "x509 decoder", "certificate viewer", "ssl cert check", "pem certificate decoder"],
        "how_to_steps_en": [
            "Paste your PEM certificate beginning with '-----BEGIN CERTIFICATE-----'.",
            "The parser automatically decodes the X.509 certificate hierarchy.",
            "Inspect Subject, Issuer (e.g. Let's Encrypt, DigiCert), Not Before, Not After, and days left.",
            "Verify SHA-256 fingerprint and serial number."
        ],
        "how_to_steps_bn": [
            "ইনপুট বক্সে '-----BEGIN CERTIFICATE-----' দিয়ে শুরু হওয়া সার্টিফিকেটটি পেস্ট করুন।",
            "টুলটি স্বয়ংক্রিয়ভাবে X.509 সার্টিফিকেট বিশ্লেষণ করে বিস্তারিত তুলে ধরবে।",
            "ইস্যুকারী (যেমন Let's Encrypt, Cloudflare), মেয়াদের তারিখ এবং কত দিন বাকি আছে তা দেখুন।",
            "SHA-256 ফিঙ্গারপ্রিন্ট ও সিরিয়াল নম্বর পরীক্ষা করুন।"
        ],
        "specs_en": {
            "Standard": "ITU-T X.509 v3 / RFC 5280",
            "Fields Parsed": "Subject, Issuer, Validity Dates, Serial Number, Signature Algorithm, Fingerprint",
            "Format": "PEM (Base64-encoded DER)",
            "Execution": "Client-Side Parser"
        },
        "specs_bn": {
            "স্ট্যান্ডার্ড": "ITU-T X.509 v3 / RFC 5280",
            "পার্সড ফিল্ডসমূহ": "Subject, Issuer, Validity Dates, Serial Number, Signature Algorithm, Fingerprint",
            "ফরম্যাট": "PEM (Base64 DER)",
            "এক্সিকিউশন": "ক্লায়েন্ট-সাইড পার্সার"
        },
        "faq_en": [
            {"q": "What happens when an SSL certificate expires?", "a": "Web browsers show a full-page security warning ('Your connection is not private', ERR_CERT_DATE_INVALID) and block visitors from accessing your website."},
            {"q": "What is the certificate fingerprint?", "a": "The fingerprint is a unique cryptographic hash (typically SHA-256) of the entire certificate binary, used to verify that the certificate matches the intended certificate authority."}
        ],
        "faq_bn": [
            {"q": "SSL সার্টিফিকেট এক্সপায়ার হয়ে গেলে কী ঘটে?", "a": "ব্রাউজার ভিজিটরদের লাল রঙের ওয়ার্নিং স্ক্রিন ('Your connection is not private') দেখায় এবং ওয়েবসাইটে ঢুকতে বাধা দেয়।"},
            {"q": "সার্টিফিকেট ফিঙ্গারপ্রিন্ট কী?", "a": "ফিঙ্গারপ্রিন্ট হলো সম্পূর্ণ সার্টিফিকেটের একটি অনন্য ক্রিপ্টোগ্রাফিক হ্যাশ (SHA-256) যা দিয়ে এর সত্যতা নিশ্চিত করা যায়।"}
        ]
    },
    {
        "slug": "ssl-certificate-expiry-checker",
        "name_en": "SSL Certificate Expiry Checker",
        "name_bn": "SSL সার্টিফিকেট এক্সপায়ারি চেকার",
        "short_desc_en": "Check SSL certificate expiration date, days remaining, trust status, and renewal reminders for any domain or PEM certificate.",
        "short_desc_bn": "নির্দিষ্ট ডোমেইনের SSL সার্টিফিকেট কবে শেষ হবে, কত দিন মেয়াদ বাকি এবং রিনিউয়াল স্ট্যাটাস পরীক্ষা করুন।",
        "meta_title_en": "SSL Certificate Expiry Checker — Days Remaining & Renewal | ToolZeno",
        "meta_title_bn": "SSL সার্টিফিকেট এক্সপায়ারি চেকার — মেয়াদের বাকি দিন ক্যালকুলেটর | ToolZeno",
        "meta_desc_en": "Check how many days remain before your website's SSL certificate expires. Avoid downtime with expiry status alerts and renewal checklist.",
        "meta_desc_bn": "আপনার ওয়েবসাইটের SSL সার্টিফিকেট কবে এক্সপায়ার হবে তা চেক করুন। ওয়েবসাইট ডাউন হওয়া এড়াতে প্রয়োজনীয় সতর্কতা।",
        "tags": ["ssl expiry checker", "check ssl expiration", "ssl cert days left", "ssl renewal reminder", "ssl validity"],
        "how_to_steps_en": [
            "Paste your domain name (or paste your current PEM certificate).",
            "View expiration date, days remaining gauge, and renewal urgency status.",
            "Green indicates safe (>30 days), yellow indicates renewal due (<30 days), red indicates expired.",
            "Follow the best-practice SSL checklist to renew in time."
        ],
        "how_to_steps_bn": [
            "আপনার ডোমেইন নাম লিখুন অথবা বর্তমান PEM সার্টিফিকেটটি পেস্ট করুন।",
            "মেয়াদ শেষের তারিখ, বাকি থাকা দিন এবং রিনিউ করার তাগিদ স্ট্যাটাস দেখুন।",
            "৩০ দিনের বেশি থাকলে সবুজ (নিরাপদ), ৩০ দিনের কম থাকলে হলুদ (রিনিউ প্রয়োজন) এবং মেয়াদ শেষ হলে লাল দেখাবে।",
            "চেকলিস্ট দেখে সময়মতো সার্টিফিকেট রিনিউ করে নিন।"
        ],
        "specs_en": {
            "Indicators": "Safe (>30 days), Expiring Soon (1-30 days), Expired (0 days)",
            "Inspection": "Real-time date difference and validity period calculator",
            "Checklist": "ACME automatic renewal, CAA records, TLS 1.3 configuration",
            "Zero Data Tracking": "All analysis executed locally"
        },
        "specs_bn": {
            "নির্দেশক": "নিরাপদ (>৩০ দিন), দ্রুত রিনিউ প্রয়োজন (১-৩০ দিন), এক্সপায়ার্ড (০ দিন)",
            "অ্যানালাইসিস": "রিয়েল-টাইম ডেট ডিফারেন্স ও ভ্যালিডিটি ক্যালকুলেশন",
            "চেকলিস্ট": "অটো-রিনিউয়াল, CAA রেকর্ড, TLS 1.3 কনফিগারেশন",
            "প্রাইভেসি": "১০০% লোকাল ব্রাউজারে সংরক্ষিত"
        },
        "faq_en": [
            {"q": "How far in advance should I renew an SSL certificate?", "a": "Industry standard recommends renewing at least 15 to 30 days before expiration to account for CA validation delays or DNS propagation issues."},
            {"q": "What is the maximum validity for public SSL certificates today?", "a": "Modern public SSL certificates are limited to a maximum validity of 398 days (approx 13 months), with automated services like Let's Encrypt issuing 90-day certificates."}
        ],
        "faq_bn": [
            {"q": "মেয়াদ শেষের কত দিন আগে SSL রিনিউ করা উচিত?", "a": "অন্তত ১৫ থেকে ৩০ দিন আগে রিনিউ করা বাঞ্ছনীয়, যাতে ভ্যালিডেশনে কোনো জটিলতা তৈরি হলেও ওয়েবসাইট সচল থাকে।"},
            {"q": "বর্তমানে পাবলিক SSL সার্টিফিকেটের সর্বোচ্চ মেয়াদ কত?", "a": "আন্তর্জাতিক নিয়মানুযায়ী পাবলিক SSL এর সর্বোচ্চ মেয়াদ ৩৯৮ দিন। Let's Encrypt এর মতো অটোমেটেড সার্ভিসের ক্ষেত্রে মেয়াদ ৯০ দিন।"}
        ]
    },
    {
        "slug": "self-signed-certificate-generator",
        "name_en": "Self-Signed SSL Certificate Generator",
        "name_bn": "সেলফ-সাইনড SSL সার্টিফিকেট জেনারেটর",
        "short_desc_en": "Generate self-signed X.509 SSL certificates and RSA private keys directly in your browser for local development and staging.",
        "short_desc_bn": "লোকাল ডেভেলপমেন্ট ও টেস্টিংয়ের জন্য ব্রাউজারেই সেলফ-সাইনড SSL সার্টিফিকেট ও প্রাইভেট কি তৈরি করুন।",
        "meta_title_en": "Self-Signed SSL Certificate Generator — Create .crt & .key Online | ToolZeno",
        "meta_title_bn": "সেলফ-সাইনড SSL সার্টিফিকেট জেনারেটর — লোকাল ডেভেলপমেন্ট SSL | ToolZeno",
        "meta_desc_en": "Generate self-signed X.509 certificates and RSA private keys for localhost, Docker, and Nginx testing. Free instant download.",
        "meta_desc_bn": "লোকালহোস্ট ও অ্যাপ ডেভেলপমেন্টের জন্য সেলফ-সাইনড সার্টিফিকেট ও কি তৈরি এবং ডাউনলোড করুন সম্পূর্ণ ফ্রিতে।",
        "tags": ["self signed certificate", "generate self signed ssl", "localhost ssl", "dev ssl certificate", "create crt key"],
        "how_to_steps_en": [
            "Enter your Common Name / Domain (e.g. 'localhost' or 'dev.local') and organization name.",
            "Choose validity duration in days (e.g. 365 days).",
            "Click 'Generate Self-Signed Certificate'. Browser creates an RSA keypair and self-signs the certificate.",
            "Download 'cert.crt' and 'key.key' directly to configure your local Apache, Nginx, or Node server."
        ],
        "how_to_steps_bn": [
            "ডোমেইন নাম দিন (যেমন 'localhost' বা 'test.local') এবং কোম্পানির নাম লিখুন।",
            "মেয়াদের দিন সংখ্যা নির্ধারণ করুন (যেমন ৩৬৫ দিন)।",
            "'Generate Self-Signed Certificate' বাটনে ক্লিক করুন।",
            "সাথে সাথেই 'cert.crt' এবং 'key.key' ফাইল ডাউনলোড করে লোকাল সার্ভারে ব্যবহার করুন।"
        ],
        "specs_en": {
            "Standard": "X.509 v3 Self-Signed Certificate",
            "Key Type": "2048-bit RSA Private Key",
            "Signature Algorithm": "SHA-256 with RSA Encryption",
            "Output Files": "PEM-encoded cert.crt and key.key with 1-click download"
        },
        "specs_bn": {
            "স্ট্যান্ডার্ড": "X.509 v3 সেলফ-সাইনড সার্টিফিকেট",
            "কী টাইপ": "২০৪৮-বিট RSA প্রাইভেট কি",
            "সিগনেচার অ্যালগরিদম": "SHA-256 উইথ RSA",
            "আউটপুট ফাইল": "সরাসরি ডাউনলোডযোগ্য cert.crt ও key.key"
        },
        "faq_en": [
            {"q": "Can I use a self-signed certificate on a production public website?", "a": "No. Browsers will show an untrusted certificate warning because the certificate is not signed by a recognized Certificate Authority (CA). Use free Let's Encrypt for public sites."},
            {"q": "Where are self-signed certificates used?", "a": "They are ideal for local software development, Docker containers, internal intranet services, and IoT device testing."}
        ],
        "faq_bn": [
            {"q": "পাবলিক ওয়েবসাইটে কি সেলফ-সাইনড সার্টিফিকেট ব্যবহার করা যাবে?", "a": "না। ব্রাউজার ভিজিটরদের ওয়ার্নিং দেবে কারণ এটি কোনো ট্রাস্টেড সিএ দ্বারা অনুমোদিত নয়। পাবলিক সাইটের জন্য Let's Encrypt ব্যবহার করুন।"},
            {"q": "সেলফ-সাইনড সার্টিফিকেট কোথায় ব্যবহার হয়?", "a": "লোকালহোস্টে ডেভেলপমেন্ট, ডকার কনটেইনার ও অভ্যন্তরীণ টেস্টিংয়ের জন্য এটি নিখুঁত সমাধান।"}
        ]
    },

    # ---------------- 8. ENCODING & CLASSICAL CIPHERS ----------------
    {
        "slug": "base64-converter",
        "name_en": "Base64 Encoder & Decoder",
        "name_bn": "Base64 এনকোডার ও ডিকোডার",
        "short_desc_en": "Encode and decode plain text, binary strings, and URLs with standard RFC 4648 Base64 and URL-Safe Base64.",
        "short_desc_bn": "নিরাপদ ডাটা ট্রান্সফারের জন্য সাধারণ টেক্সট ও বাইনারি ডাটা Base64 ও URL-Safe Base64 ফরম্যাটে রূপান্তর করুন।",
        "meta_title_en": "Base64 Encoder & Decoder — Text to Base64 Online | ToolZeno",
        "meta_title_bn": "Base64 এনকোডার ও ডিকোডার — অনলাইন Base64 কনভার্টার | ToolZeno",
        "meta_desc_en": "Encode and decode Base64 online with full UTF-8 Unicode support. Toggle standard or URL-Safe Base64. Free & fast.",
        "meta_desc_bn": "বাংলা ও ইউনিকোড টেক্সট Base64 এনকোড ও ডিকোড করুন সম্পূর্ণ নির্ভুলভাবে। রিয়েল-টাইম কনভার্সন।",
        "tags": ["base64 encoder", "base64 decoder", "url safe base64", "base64 convert", "text to base64"],
        "how_to_steps_en": [
            "Switch between 'Encode' (Text to Base64) and 'Decode' (Base64 to Text) tabs.",
            "Type or paste your input into the main text editor.",
            "Optionally enable 'URL-Safe' mode to replace '+' with '-' and '/' with '_'.",
            "Click 'Copy' to copy the converted result to your clipboard."
        ],
        "how_to_steps_bn": [
            "'Encode' (টেক্সট থেকে Base64) অথবা 'Decode' (Base64 থেকে টেক্সট) ট্যাব নির্বাচন করুন।",
            "ইনপুট বক্সে আপনার লেখা পেস্ট করুন।",
            "ইউআরএল এ ব্যবহারের জন্য 'URL-Safe' অপশন চালু করতে পারেন।",
            "'Copy' বাটনে ক্লিক করে ফলাফল কপি করে নিন।"
        ],
        "specs_en": {
            "Charset Support": "Full UTF-8 Unicode (Bengali, Emoji, Latin, CJK)",
            "Variants": "Standard Base64 (RFC 4648) and URL-Safe Base64",
            "Performance": "Real-time browser-based byte transformation",
            "Privacy": "100% Client-Side memory execution"
        },
        "specs_bn": {
            "ক্যারেক্টার সাপোর্ট": "সম্পূর্ণ UTF-8 ইউনিকোড (বাংলা, ইমোজি ইত্যাদি)",
            "ভ্যারিয়েন্ট": "স্ট্যান্ডার্ড Base64 ও URL-Safe Base64",
            "পারফরম্যান্স": "রিয়েল-টাইম ব্রাউজার ট্রান্সফর্মেশন",
            "প্রাইভেসি": "১০০% ক্লায়েন্ট-সাইড"
        },
        "faq_en": [
            {"q": "Is Base64 considered encryption?", "a": "No. Base64 is an encoding format, not encryption. It provides zero confidentiality because anyone can decode it instantly without a key."},
            {"q": "Why is Base64 used?", "a": "Base64 represents binary data using only 64 printable ASCII characters, ensuring safe transmission across networks, emails (MIME), and JSON payloads without corruption."}
        ],
        "faq_bn": [
            {"q": "Base64 কি এনক্রিপশন?", "a": "না। Base64 কোনো এনক্রিপশন নয়, এটি কেবল একটি এনকোডিং ফরম্যাট। চাবি ছাড়াই যে কেউ এটিকে ডিকোড করতে পারে।"},
            {"q": "তাহলে Base64 কেন ব্যবহার করা হয়?", "a": "ইন্টারনেটে বা ইমেইলে বাইনারি ফাইল বা স্পেশাল ক্যারেক্টার নষ্ট হওয়া ছাড়াই নিরাপদে ট্রান্সফার করার জন্য Base64 ব্যবহৃত হয়।"}
        ]
    },
    {
        "slug": "base32-base58-converter",
        "name_en": "Base32 / Base58 Encoder & Decoder",
        "name_bn": "Base32 ও Base58 এনকোডার ও ডিকোডার",
        "short_desc_en": "Convert text between Base32 (RFC 4648 2FA standard) and Base58 (Bitcoin & IPFS crypto alphabet).",
        "short_desc_bn": "২-ফ্যাক্টর অথেনটিকেশনের Base32 এবং বিটকয়েন ও ক্রিপ্টো অ্যাড্রেসে ব্যবহৃত Base58 এনকোড ও ডিকোড করুন।",
        "meta_title_en": "Base32 & Base58 Encoder Decoder — Crypto & 2FA Tool | ToolZeno",
        "meta_title_bn": "Base32 ও Base58 কনভার্টার — বিটকয়েন ও 2FA এনকোডার | ToolZeno",
        "meta_desc_en": "Encode and decode text to Base32 and Base58 online. Used in Bitcoin addresses, IPFS hashes, and 2FA secret keys.",
        "meta_desc_bn": "বিটকয়েন ও IPFS এ ব্যবহৃত Base58 এবং 2FA এর Base32 এনকোড ও ডিকোড করুন সহজে। সম্পূর্ণ ক্লায়েন্ট সাইড।",
        "tags": ["base32", "base58", "bitcoin address encoding", "base32 decoder", "crypto base58"],
        "how_to_steps_en": [
            "Select encoding type: Base32 (RFC 4648 standard) or Base58 (Bitcoin alphabet).",
            "Choose 'Encode' to turn text into Base32/Base58, or 'Decode' to revert back to plain text.",
            "Type your string and view instant live conversion.",
            "Click 'Copy' to copy the encoded or decoded string."
        ],
        "how_to_steps_bn": [
            "এনকোডিং টাইপ নির্বাচন করুন: Base32 অথবা Base58 (Bitcoin alphabet)।",
            "টেক্সট থেকে কনভার্ট করতে 'Encode' অথবা আসল লেখায় ফিরতে 'Decode' সিলেক্ট করুন।",
            "ইনপুট বক্সে লিখুন এবং সাথে সাথেই আউটপুট দেখুন।",
            "'Copy' বাটনে ক্লিক করে ফলাফল কপি করুন।"
        ],
        "specs_en": {
            "Base32 Alphabet": "A-Z and 2-7 (RFC 4648)",
            "Base58 Alphabet": "Bitcoin Base58 (excludes 0, O, I, l to prevent human visual confusion)",
            "Unicode Support": "Full UTF-8 byte stream processing",
            "Execution": "Client-side pure JavaScript"
        },
        "specs_bn": {
            "Base32 বর্ণমালা": "A-Z এবং ২-৭ (RFC 4648)",
            "Base58 বর্ণমালা": "Bitcoin Base58 (কনফিউশন এড়াতে 0, O, I, l বাদ দেওয়া)",
            "ইউনিকোড সাপোর্ট": "UTF-8 বাইট প্রসেসিং",
            "এক্সিকিউশন": "পিওর জাভাস্ক্রিপ্ট ইঞ্জিন"
        },
        "faq_en": [
            {"q": "Why does Bitcoin use Base58 instead of Base64?", "a": "Base58 deliberately eliminates similar-looking characters (0, O, I, and l) and special characters (+ and /), making wallet addresses much safer for humans to read, write, and double-click to select."},
            {"q": "Where is Base32 used?", "a": "Base32 is case-insensitive, making it ideal for voice dictation and 2FA Authenticator secret keys."}
        ],
        "faq_bn": [
            {"q": "বিটকয়েন Base64 এর বদলে Base58 কেন ব্যবহার করে?", "a": "মানুষের দেখতে ভুল হতে পারে এমন অক্ষরগুলো (যেমন 0, O, I, l) Base58 থেকে বাদ দেওয়া হয়েছে, যাতে ক্রিপ্টো ওয়ালেট অ্যাড্রেস টাইপ বা কপি করতে ভুল না হয়।"},
            {"q": "Base32 কোথায় ব্যবহৃত হয়?", "a": "Base32 বড় ও ছোট হাতের অক্ষরের ওপর নির্ভরশীল নয়, তাই ২-ফ্যাক্টর অথেনটিকেশন কি এবং ফাইলসিস্টেমে এটি ব্যবহৃত হয়।"}
        ]
    },
    {
        "slug": "rot13-caesar-cipher",
        "name_en": "ROT13 & Caesar Cipher Tool",
        "name_bn": "ROT13 ও সিজার সাইফার (Caesar Cipher) টুল",
        "short_desc_en": "Encode and decode classical Caesar shift ciphers with custom shifts (1-25) and one-click standard ROT13 rotation.",
        "short_desc_bn": "প্রাচীন সিজার সাইফার এবং বহুল ব্যবহৃত ROT13 শিফট সাইফার দিয়ে টেক্সট এনকোড ও ডিকোড করুন।",
        "meta_title_en": "ROT13 & Caesar Cipher Tool — Classical Shift Decoder | ToolZeno",
        "meta_title_bn": "ROT13 ও সিজার সাইফার টুল — প্রাচীন শিফট সাইফার কনভার্টার | ToolZeno",
        "meta_desc_en": "Encode and decode text using Caesar Cipher and ROT13. Adjust letter shifts from 1 to 25 with interactive alphabet wheel.",
        "meta_desc_bn": "১ থেকে ২৫ শিফট সমন্বিত সিজার সাইফার ও ROT13 টেক্সট কনভার্সন টুল। সম্পূর্ণ ইন্টারেক্টিভ ও ফ্রি।",
        "tags": ["rot13", "caesar cipher", "shift cipher", "caesar decoder", "classical cryptography"],
        "how_to_steps_en": [
            "Type or paste your text into the message box.",
            "Use the shift slider to choose any shift between 1 and 25 (default 13 for ROT13).",
            "Or click the 'ROT13 Preset' button for instant symmetric rotation.",
            "The cipher preserves upper and lower case letters while keeping numbers and punctuation unchanged."
        ],
        "how_to_steps_bn": [
            "ইনপুট বক্সে আপনার টেক্সট লিখুন।",
            "স্লাইডার টেনে ১ থেকে ২৫ এর মধ্যে যেকোনো শিফট সিলেক্ট করুন (ROT13 এর জন্য ১৩)।",
            "অথবা সরাসরি 'ROT13 Preset' বাটনে ক্লিক করুন।",
            "বড় ও ছোট হাতের অক্ষরের বিন্যাস বজায় রেখে টেক্সট স্বয়ংক্রিয়ভাবে পরিবর্তিত হবে।"
        ],
        "specs_en": {
            "Algorithm": "Modular shift substitution cipher: C = (P + k) mod 26",
            "Shift Range": "1 to 25 positions",
            "ROT13 Property": "Self-inverse: applying ROT13 twice restores original text",
            "Preservation": "Preserves case, punctuation, spaces, and numbers"
        },
        "specs_bn": {
            "অ্যালগরিদম": "মডিউলার শিফট সাবস্টিটিউশন সাইফার",
            "শিফটের পরিধি": "১ থেকে ২৫ পজিশন",
            "ROT13 বৈশিষ্ট্য": "সেলফ-ইনভার্স: দুইবার ROT13 চালালে মূল টেক্সট ফিরে আসে",
            "সংরক্ষণ": "সংখ্যা, বিরামচিহ্ন ও স্পেস অপরিবর্তিত থাকে"
        },
        "faq_en": [
            {"q": "What is the historical Caesar Cipher?", "a": "Named after Julius Caesar, who used a shift of 3 to protect military communications. It is the most famous classical substitution cipher in history."},
            {"q": "Why is ROT13 still used on online forums?", "a": "ROT13 is widely used on Reddit, Usenet, and puzzle forums to obscure spoilers, movie endings, and puzzle solutions without acting as heavy encryption."}
        ],
        "faq_bn": [
            {"q": "সিজার সাইফার কী?", "a": "রোমান সম্রাট জুলিয়াস সিজার তাঁর সামরিক চিঠিপত্র সুরক্ষিত রাখতে ৩ ঘর শিফটের এই পদ্ধতি ব্যবহার করতেন। এটি ইতিহাসের অন্যতম প্রাচীন সাইফার।"},
            {"q": "ROT13 অনলাইন ফোরামে কেন ব্যবহার হয়?", "a": "মুভি স্পয়লার বা ধাঁধার উত্তর লুকিয়ে রাখতে ROT13 বহুল ব্যবহৃত, যাতে পাঠক ইচ্ছা করে ক্লিক না করা পর্যন্ত উত্তর না দেখে।"}
        ]
    },
    {
        "slug": "vigenere-cipher",
        "name_en": "Vigenère Cipher Tool",
        "name_bn": "ভিজিনের সাইফার (Vigenère Cipher) টুল",
        "short_desc_en": "Encrypt and decrypt secret messages using the keyword-based Vigenère polyalphabetic substitution cipher.",
        "short_desc_bn": "কী-ওয়ার্ড ভিত্তিক পলিঅ্যালফাবেটিক ভিজিনের সাইফার (Vigenère) দিয়ে টেক্সট এনক্রিপ্ট ও ডিক্রিপ্ট করুন।",
        "meta_title_en": "Vigenère Cipher Tool — Polyalphabetic Keyword Cipher | ToolZeno",
        "meta_title_bn": "ভিজিনের সাইফার টুল — কী-ওয়ার্ড ভিত্তিক সাইফার কনভার্টার | ToolZeno",
        "meta_desc_en": "Encrypt and decrypt text with the historical Vigenère Cipher using a secret keyword. Visual keystream alignment and step-by-step decoding.",
        "meta_desc_bn": "গোপন কী-ওয়ার্ড ব্যবহার করে ঐতিহাসিক ভিজিনের সাইফারে মেসেজ এনক্রিপ্ট ও ডিক্রিপ্ট করুন। সম্পূর্ণ ফ্রি।",
        "tags": ["vigenere cipher", "vigenere decoder", "polyalphabetic cipher", "keyword cipher", "classical cipher"],
        "how_to_steps_en": [
            "Select mode: 'Encrypt' or 'Decrypt'.",
            "Enter your secret Keyword (letters only, e.g., 'SECRET' or 'KEY').",
            "Type your message into the text field.",
            "View live polyalphabetic substitution with keystream visualization, and click 'Copy'."
        ],
        "how_to_steps_bn": [
            "মোড নির্বাচন করুন: 'Encrypt' অথবা 'Decrypt'।",
            "একটি গোপন কী-ওয়ার্ড (Keyword) লিখুন (যেমন: 'SECRET' বা 'KEY')।",
            "ইনপুট বক্সে আপনার বার্তা লিখুন।",
            "সাথে সাথেই ভিজিনের সাইফার অনুযায়ী এনক্রিপ্টেড বার্তা পেয়ে যাবেন।"
        ],
        "specs_en": {
            "Algorithm": "Polyalphabetic Caesar shift based on keyword letter indices",
            "Keystream": "Keyword repeats cyclically across message length",
            "Case Handling": "Preserves original uppercase and lowercase casing",
            "Execution": "Real-time client-side calculation"
        },
        "specs_bn": {
            "অ্যালগরিদম": "পলিঅ্যালফাবেটিক সাবস্টিটিউশন সাইফার",
            "কিস্ট্রিম": "কী-ওয়ার্ড চক্রাকারে পুনরাবৃত্ত হয়",
            "কেস হ্যান্ডলিং": "বড় ও ছোট হাতের অক্ষর বজায় রাখে",
            "এক্সিকিউশন": "রিয়েল-টাইম ক্লায়েন্ট সাইড"
        },
        "faq_en": [
            {"q": "Why was the Vigenère Cipher called 'le chiffre indéchiffrable'?", "a": "For over 300 years it was believed to be unbreakable because it uses multiple Caesar alphabets, flattening simple letter-frequency analysis that easily cracked monoalphabetic ciphers."},
            {"q": "Who finally broke the Vigenère cipher?", "a": "Charles Babbage and Friedrich Kasiski independently broke it in the 19th century by identifying repeating patterns to deduce the keyword length."}
        ],
        "faq_bn": [
            {"q": "ভিজিনের সাইফারকে কেন 'অভেদ্য সাইফার' বলা হতো?", "a": "প্রায় ৩০০ বছর ধরে এটিকে ক্র্যাক করা অসম্ভব মনে করা হতো, কারণ এটি একাধিক বর্ণমালার সমন্বয় ব্যবহার করায় সহজ ফ্রিকোয়েন্সি অ্যানালাইসিস ব্যর্থ হতো।"},
            {"q": "এটি কীভাবে ক্র্যাক করা হয়েছিল?", "a": "১৯শ শতকে চার্লস ব্যাবেজ ও ক্যাসিস্কি পুনরাবৃত্ত প্যাটার্ন লক্ষ্য করে কী-ওয়ার্ডের দৈর্ঘ্য বের করার মাধ্যমে এটি ক্র্যাক করেন।"}
        ]
    },
    {
        "slug": "morse-code-translator",
        "name_en": "Morse Code Translator & Audio Player",
        "name_bn": "মোর্স কোড অনুবাদক ও অডিও প্লেয়ার",
        "short_desc_en": "Translate text to International Morse Code and Morse to text, complete with Web Audio sound playback and visual flashes.",
        "short_desc_bn": "টেক্সট থেকে মোর্স কোড এবং মোর্স থেকে টেক্সটে রূপান্তর করুন, সাথে রয়েছে আসল সাউন্ড প্লেব্যাক ও লাইট ফ্লাশ।",
        "meta_title_en": "Morse Code Translator — Text to Morse Audio Player | ToolZeno",
        "meta_title_bn": "মোর্স কোড অনুবাদক — টেক্সট ও সাউন্ড প্লেয়ার অনলাইন | ToolZeno",
        "meta_desc_en": "Convert text to Morse code dots and dashes with real Web Audio 600Hz sound beeps. Decodes Morse code back to text. 100% free.",
        "meta_desc_bn": "টেক্সটকে মোর্স কোডের ডট ও ড্যাশে রূপান্তর করুন এবং সাউন্ড শুনুন। মোর্স কোড থেকে সহজে টেক্সটে অনুবাদ করুন।",
        "tags": ["morse code translator", "text to morse", "morse code audio", "morse decoder", "sos morse code"],
        "how_to_steps_en": [
            "Type text in the 'Text' field to automatically generate Morse code (dots '.' and dashes '-').",
            "Or type dots and dashes in the 'Morse' field to translate back into English text.",
            "Click 'Play Audio' to hear authentic 600Hz radio telegraph beeps generated via Web Audio API.",
            "Use the 'SOS Preset' for a quick emergency signal demonstration."
        ],
        "how_to_steps_bn": [
            "'Text' বক্সে লিখলে স্বয়ংক্রিয়ভাবে মোর্স কোড (. এবং -) তৈরি হবে।",
            "অথবা 'Morse' বক্সে ডট ও ড্যাশ টাইপ করে সাধারণ টেক্সটে অনুবাদ করুন।",
            "'Play Audio' বাটনে ক্লিক করে আসল টেলিগ্রাফ সাউন্ড (Beep) শুনুন।",
            "'SOS Preset' বাটনে ক্লিক করে ইমার্জেন্সি সিগন্যাল ডেমো দেখতে পারেন।"
        ],
        "specs_en": {
            "Standard": "International Morse Code (ITU-R M.1677-1)",
            "Audio Synthesis": "Web Audio API Sine wave oscillator (600 Hz)",
            "Timing Standard": "1 unit dot, 3 units dash, 3 units letter space, 7 units word space",
            "Special Symbols": "Full alphabet (A-Z), numbers (0-9), punctuation"
        },
        "specs_bn": {
            "স্ট্যান্ডার্ড": "ইন্টারন্যাশনাল মোর্স কোড (ITU-R)",
            "অডিও সিন্থেসিস": "ওয়েব অডিও API সাইন ওয়েভ (৬০০ Hz)",
            "টাইমিং": "১ ইউনিট ডট, ৩ ইউনিট ড্যাশ, ৭ ইউনিট স্পেস",
            "প্রতীকসমূহ": "A-Z, ০-৯ এবং বিরামচিহ্ন"
        },
        "faq_en": [
            {"q": "What is the most famous Morse code signal?", "a": "The distress signal SOS: three dots, three dashes, three dots (... --- ...). Chosen because it is distinctive and easily recognized even in heavy radio interference."},
            {"q": "Is Morse code still used today?", "a": "While no longer mandatory in maritime communications, it remains widely used by amateur radio (ham radio) operators, aviation navigational aids (VOR/NDB identifiers), and emergency signalling."}
        ],
        "faq_bn": [
            {"q": "মোর্স কোডের সবচেয়ে বিখ্যাত সংকেত কোনটি?", "a": "আন্তর্জাতিক বিপদ সংকেত এসওএস (SOS): তিনটি ডট, তিনটি ড্যাশ, তিনটি ডট (... --- ...)। যা রেডিওতে সহজে শনাক্ত করা যায়।"},
            {"q": "মোর্স কোড কি এখনও ব্যবহৃত হয়?", "a": "হ্যাঁ, অ্যামেচার রেডিও অপারেটর, বিমান চালনায় নেভিগেশনাল বীকন এবং জরুরি পরিস্থিতিতে এখনও মোর্স কোড ব্যবহৃত হয়।"}
        ]
    },
    {
        "slug": "binary-text-converter",
        "name_en": "Binary to Text / Text to Binary Converter",
        "name_bn": "বাইনারি টু টেক্সট ও টেক্সট টু বাইনারি কনভার্টার",
        "short_desc_en": "Convert plain text to 8-bit binary code (0s and 1s) and decode binary streams back into readable text.",
        "short_desc_bn": "সাধারণ লেখাকে ৮-বিট বাইনারি কোডে (০ ও ১) রূপান্তর করুন এবং বাইনারি কোড থেকে মূল টেক্সট ফিরে পান।",
        "meta_title_en": "Binary to Text & Text to Binary Converter — ASCII 0101 Online | ToolZeno",
        "meta_title_bn": "বাইনারি টু টেক্সট ও টেক্সট টু বাইনারি কনভার্টার — ৮-বিট কোডার | ToolZeno",
        "meta_desc_en": "Translate text to 8-bit binary and binary to text online. Choose space, comma, or continuous delimiters. Fast & 100% free.",
        "meta_desc_bn": "টেক্সট থেকে বাইনারি এবং বাইনারি কোড থেকে টেক্সট রূপান্তর করুন নিমেষেই। সম্পূর্ণ ফ্রি ও নির্ভুল।",
        "tags": ["binary to text", "text to binary", "binary converter", "ascii binary", "0101 converter"],
        "how_to_steps_en": [
            "Switch between 'Text to Binary' and 'Binary to Text' mode.",
            "Type or paste your input into the editor.",
            "Select delimiter format: Space-separated ('01000001 01000010'), Comma-separated, or None.",
            "Click 'Copy' to copy the converted 8-bit binary output."
        ],
        "how_to_steps_bn": [
            "'Text to Binary' অথবা 'Binary to Text' মোড বেছে নিন।",
            "ইনপুট বক্সে আপনার লেখা বা বাইনারি কোড পেস্ট করুন।",
            "বিভাজন চিহ্ন সিলেক্ট করুন: স্পেস (Space), কমা (Comma) অথবা চিহ্ন ছাড়া।",
            "'Copy' বাটনে ক্লিক করে ফলাফল কপি করে নিন।"
        ],
        "specs_en": {
            "Representation": "8-bit byte representation (padded with leading zeros)",
            "Encoding Standard": "ASCII & UTF-8 character encoding",
            "Delimiters": "Space, Comma, No delimiter, Prefix ('0b')",
            "Stats Tracked": "Character count, byte count, total bits"
        },
        "specs_bn": {
            "রিপ্রেজেন্টেশন": "৮-বিট বাইট ফরম্যাট",
            "এনকোডিং স্ট্যান্ডার্ড": "ASCII ও UTF-8",
            "ডিলিমিটার": "স্পেস, কমা, প্রিফিক্স ('0b')",
            "পরিসংখ্যান": "মোট ক্যারেক্টার, বাইট ও বিটস কাউন্ট"
        },
        "faq_en": [
            {"q": "How does a computer represent letters in binary?", "a": "Each character is assigned a number (e.g. 'A' is 65 in ASCII), which is converted to 8 binary bits: 01000001 (64 + 1)."},
            {"q": "Can I convert Bengali or emojis to binary?", "a": "Yes! This tool encodes Unicode characters into UTF-8 byte sequences, accurately representing any language in binary format."}
        ],
        "faq_bn": [
            {"q": "কম্পিউটার কীভাবে লেখাকে বাইনারিতে রূপান্তর করে?", "a": "প্রতিটি অক্ষরের একটি মান থাকে (যেমন 'A' এর ASCII মান ৬৫)। এই ৬৫ কে বাইনারিতে ৮টি বিটে লেখা হয়: ০১০০০০০১।"},
            {"q": "বাংলা লেখা বা ইমোজি কি বাইনারি করা যায়?", "a": "হ্যাঁ! এই টুলটি UTF-8 স্ট্যান্ডার্ডে কাজ করে, তাই বাংলা সহ যেকোনো ভাষা নির্ভুলভাবে বাইনারিতে রূপান্তর করা যায়।"}
        ]
    }
]

print(f"Total tools defined: {len(ALL_TOOLS)}")
