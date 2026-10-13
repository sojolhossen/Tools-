# -*- coding: utf-8 -*-
"""
Sync config/tools.php, lang/en/tools.php, lang/bn/tools.php for all 34 security tools.
Ensures 100% PHP string escape safety.
"""
import os
import sys

BASE_DIR = r"d:\xampp\htdocs\tools"
sys.path.append(os.path.join(BASE_DIR, "scripts"))
from build_full_security_suite import ALL_TOOLS

def escape_php_str(val):
    if not isinstance(val, str):
        return str(val)
    # Replace backslash first, then single quotes
    val = val.replace("\\", "\\\\")
    val = val.replace("'", "\\'")
    return val

# 1. Update config/tools.php
def sync_config():
    config_path = os.path.join(BASE_DIR, "config", "tools.php")
    with open(config_path, "r", encoding="utf-8") as f:
        content = f.read()

    import re
    # Ensure hmac-generator has category security
    pattern = r"('hmac-generator'\s*=>\s*\[(?:[^\]]*?)'category'\s*=>\s*)'developer'"
    content = re.sub(pattern, r"\1'security'", content)

    last_items_bracket = content.rfind("    ],")
    if last_items_bracket == -1:
        print("Error: Could not locate items closing bracket in config/tools.php")
        return

    added = 0
    new_items_code = []
    for t in ALL_TOOLS:
        slug = t["slug"]
        snake = slug.replace("-", "_")
        if f"'{slug}' => [" in content:
            continue

        tags_str = ", ".join([f"'{escape_php_str(tag)}'" for tag in t["tags"]])
        item_str = f"""        '{slug}' => [
            'slug' => '{slug}',
            'name_key' => 'tools.{snake}.name',
            'description_key' => 'tools.{snake}.short_desc',
            'category' => 'security',
            'icon' => '{t.get("icon", "shield-check")}',
            'route_name' => 'tools.show',
            'status' => 'active',
            'supported_locales' => ['en', 'bn'],
            'view' => 'tools.{slug}',
            'is_featured' => False,
            'is_popular' => False,
            'meta_title_key' => 'tools.{snake}.meta_title',
            'meta_description_key' => 'tools.{snake}.meta_desc',
            'tags' => [{tags_str}],
        ],"""
        new_items_code.append(item_str)
        added += 1

    if new_items_code:
        updated = content[:last_items_bracket] + "\n".join(new_items_code) + "\n" + content[last_items_bracket:]
        with open(config_path, "w", encoding="utf-8") as f:
            f.write(updated)
        print(f"Added {added} new tools to config/tools.php")
    else:
        with open(config_path, "w", encoding="utf-8") as f:
            f.write(content)
        print("All tools already defined in config/tools.php")

# 2. Update lang/en/tools.php
def sync_lang_en():
    path = os.path.join(BASE_DIR, "lang", "en", "tools.php")
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    last_bracket = content.rfind("];")
    if last_bracket == -1:
        print("Error: Could not locate closing bracket in lang/en/tools.php")
        return

    added = 0
    new_blocks = []
    for t in ALL_TOOLS:
        slug = t["slug"]
        snake = slug.replace("-", "_")
        if f"'{snake}' => [" in content:
            continue

        steps_lines = "\n".join([f"            '{escape_php_str(s)}'," for s in t["how_to_steps_en"]])
        specs_lines = "\n".join([f"            '{escape_php_str(k)}' => '{escape_php_str(v)}'," for k, v in t["specs_en"].items()])
        faq_blocks = []
        for q in t["faq_en"]:
            q_esc = escape_php_str(q['q'])
            a_esc = escape_php_str(q['a'])
            faq_blocks.append(f"""            [
                'q' => '{q_esc}',
                'a' => '{a_esc}',
            ],""")
        faq_lines = "\n".join(faq_blocks)

        name_esc = escape_php_str(t['name_en'])
        short_esc = escape_php_str(t['short_desc_en'])
        meta_t_esc = escape_php_str(t['meta_title_en'])
        meta_d_esc = escape_php_str(t['meta_desc_en'])

        block = f"""    '{snake}' => [
        'name' => '{name_esc}',
        'short_desc' => '{short_esc}',
        'meta_title' => '{meta_t_esc}',
        'meta_desc' => '{meta_d_esc}',
        'how_to_title' => 'How to Use the {name_esc}',
        'how_to_steps' => [
{steps_lines}
        ],
        'specs_title' => 'Technical Specifications',
        'specs' => [
{specs_lines}
        ],
        'faq_title' => 'Frequently Asked Questions',
        'faq' => [
{faq_lines}
        ],
        'privacy_title' => '100% Client-Side Privacy Guarantee',
        'privacy_desc' => 'All cryptographic hashing, key generation, and cipher operations execute strictly inside your local browser memory using the native Web Crypto API. No plaintexts, secrets, or keys are ever sent across the network.',
    ],"""
        new_blocks.append(block)
        added += 1

    if new_blocks:
        updated = content[:last_bracket] + "\n".join(new_blocks) + "\n" + content[last_bracket:]
        with open(path, "w", encoding="utf-8") as f:
            f.write(updated)
        print(f"Added {added} tools to lang/en/tools.php")
    else:
        print("All tools already exist in lang/en/tools.php")

# 3. Update lang/bn/tools.php
def sync_lang_bn():
    path = os.path.join(BASE_DIR, "lang", "bn", "tools.php")
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    last_bracket = content.rfind("];")
    if last_bracket == -1:
        print("Error: Could not locate closing bracket in lang/bn/tools.php")
        return

    added = 0
    new_blocks = []
    for t in ALL_TOOLS:
        slug = t["slug"]
        snake = slug.replace("-", "_")
        if f"'{snake}' => [" in content:
            continue

        steps_lines = "\n".join([f"            '{escape_php_str(s)}'," for s in t["how_to_steps_bn"]])
        specs_lines = "\n".join([f"            '{escape_php_str(k)}' => '{escape_php_str(v)}'," for k, v in t["specs_bn"].items()])
        faq_blocks = []
        for q in t["faq_bn"]:
            q_esc = escape_php_str(q['q'])
            a_esc = escape_php_str(q['a'])
            faq_blocks.append(f"""            [
                'q' => '{q_esc}',
                'a' => '{a_esc}',
            ],""")
        faq_lines = "\n".join(faq_blocks)

        name_esc = escape_php_str(t['name_bn'])
        short_esc = escape_php_str(t['short_desc_bn'])
        meta_t_esc = escape_php_str(t['meta_title_bn'])
        meta_d_esc = escape_php_str(t['meta_desc_bn'])

        block = f"""    '{snake}' => [
        'name' => '{name_esc}',
        'short_desc' => '{short_esc}',
        'meta_title' => '{meta_t_esc}',
        'meta_desc' => '{meta_d_esc}',
        'how_to_title' => '{name_esc} কীভাবে ব্যবহার করবেন',
        'how_to_steps' => [
{steps_lines}
        ],
        'specs_title' => 'প্রযুক্তিগত বৈশিষ্ট্যসমূহ',
        'specs' => [
{specs_lines}
        ],
        'faq_title' => 'সচরাচর জিজ্ঞাসিত প্রশ্নাবলী (FAQ)',
        'faq' => [
{faq_lines}
        ],
        'privacy_title' => '১০০% ক্লায়েন্ট-সাইড প্রাইভেসি গ্যারান্টি',
        'privacy_desc' => 'সকল ক্রিপ্টোগ্রাফিক হ্যাশিং, কি-পেয়ার জেনারেশন এবং এনক্রিপশন অপারেশন সম্পূর্ণভাবে আপনার লোকাল ব্রাউজারের ওয়েব ক্রিপ্টো API মেমরিতে সম্পন্ন হয়। কোনো ডাটা, কি বা পাসওয়ার্ড ইন্টারনেটে বা আমাদের সার্ভারে পাঠানো হয় না।',
    ],"""
        new_blocks.append(block)
        added += 1

    if new_blocks:
        updated = content[:last_bracket] + "\n".join(new_blocks) + "\n" + content[last_bracket:]
        with open(path, "w", encoding="utf-8") as f:
            f.write(updated)
        print(f"Added {added} tools to lang/bn/tools.php")
    else:
        print("All tools already exist in lang/bn/tools.php")

if __name__ == '__main__':
    sync_config()
    sync_lang_en()
    sync_lang_bn()
    print("Escape-safe sync completed!")
