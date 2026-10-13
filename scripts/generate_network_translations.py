import re
from network_definitions import NETWORK_TOOLS

def escape_php_str(val):
    if not isinstance(val, str):
        return str(val)
    return val.replace('\\', '\\\\').replace("'", "\\'")

def get_en_content(t):
    slug = t['slug']
    name = t['name_en']
    desc = t['desc_en']
    meta_title = f"{name} — Free Online Webmaster Tool | ToolZeno"
    meta_desc = f"{desc} Completely free, fast, and privacy-friendly network utility on ToolZeno."

    steps = [
        f"Enter the target hostname, domain, IP, or input parameters into the {name} interface.",
        "Adjust any specific configuration options, flags, or test protocols as required.",
        "Click the action button to execute the query or generate your output in real time.",
        "Inspect the formatted results, copy the data, or export/download the generated configuration."
    ]

    specs = [
        "Network Protocol: IPv4, IPv6, DNS over HTTPS (DoH), HTTP/1.1, HTTP/2, RFC Standards",
        "Execution Environment: High-speed browser processing with zero tracking or unnecessary server storage",
        "Export & Formats: Raw text, structured JSON, downloadable configuration files, one-click clipboard copy",
        "Compatibility: Fully responsive on desktop workstations, tablets, and mobile devices"
    ]

    faqs = [
        {
            "question": f"What is the primary purpose of the {name}?",
            "answer": f"The {name} allows webmasters, system administrators, and developers to analyze, validate, or generate networking parameters accurately without installing third-party command-line utilities."
        },
        {
            "question": "Is my network query or input data kept private?",
            "answer": "Yes. Operations are executed either completely in your browser or through standard, non-logged DNS and public network lookups. We do not store or log your queries."
        },
        {
            "question": f"Can I use the output of {name} in production environments?",
            "answer": "Absolutely. All outputs follow official IETF RFC standards and industry best practices for server administration and webmaster workflows."
        }
    ]

    return {
        "name": name,
        "short_description": desc,
        "meta_title": meta_title,
        "meta_description": meta_desc,
        "how_to_guide": {
            "title": f"How to Use the {name}",
            "steps": steps
        },
        "technical_specs": {
            "title": f"Technical Specifications for {name}",
            "items": specs
        },
        "faqs": faqs
    }

def get_bn_content(t):
    slug = t['slug']
    name = t['name_bn']
    desc = t['desc_bn']
    meta_title = f"{name} — ফ্রি অনলাইন নেটওয়ার্ক ও ওয়েবমাস্টার টুল | ToolZeno"
    meta_desc = f"{desc} সম্পূর্ণ বিনামূল্যে, দ্রুত এবং নিরাপদে ব্যবহার করুন ToolZeno-তে।"

    steps = [
        f"{name} ইন্টারফেসে টার্গেট ডোমেইন, আইপি, হোস্টনেম বা প্রয়োজনীয় ইনপুট দিন।",
        "প্রয়োজন অনুযায়ী স্পেসিফিক ফিল্টার, প্রোটোকল বা কনফিগারেশন অপশন নির্বাচন করুন।",
        "ফলাফল পেতে বা কনফিগারেশন তৈরি করতে অ্যাকশন বাটনে ক্লিক করুন।",
        "বিশ্লেষিত আউটপুট পর্যবেক্ষণ করুন, এক ক্লিকে কপি করুন অথবা কনফিগ ফাইল ডাউনলোড করুন।"
    ]

    specs = [
        "নেটওয়ার্ক প্রোটোকল: IPv4, IPv6, ডিএনএস ওভার এইচটিটিপিএস (DoH), RFC স্ট্যান্ডার্ডস",
        "প্রসেসিং মোড: ব্রাউজার-চালিত নিরাপদ প্রসেসিং, কোনো ব্যক্তিগত ডাটা সার্ভারে সংরক্ষিত হয় না",
        "আউটপুট ফরম্যাট: টেক্সট, স্ট্রাকচার্ড ডাটা, ডাউনলোডযোগ্য কনফিগারেশন ও ওয়ান-ক্লিক কপি সুবিধা",
        "ডিভাইস সাপোর্ট: ডেস্কটপ, ল্যাপটপ এবং মোবাইল ব্রাউজারে সম্পূর্ণ রেসপন্সিভ ইন্টারফেস"
    ]

    faqs = [
        {
            "question": f"{name} কেন ব্যবহার করবেন?",
            "answer": f"{name} ব্যবহার করে ডেভেলপার, নেটওয়ার্ক ইঞ্জিনিয়ার ও ওয়েবমাস্টাররা কোনো কমান্ড-লাইন টুল ইনস্টল ছাড়াই ব্রাউজারে নেটওয়ার্ক এবং সার্ভারের তথ্য দ্রুত ও নির্ভুলভাবে বিশ্লেষণ করতে পারেন।"
        },
        {
            "question": "আমার সার্চ করা ডোমেইন বা আইপি ডাটা কি সুরক্ষিত থাকে?",
            "answer": "হ্যাঁ, সম্পূর্ণ সুরক্ষিত। টুলটিতে কোনো সার্চ হিস্টোরি বা কুয়েরি ডাটা আমাদের সার্ভারে সংরক্ষণ করা হয় না।"
        },
        {
            "question": "এই টুলের আউটপুট কি লাইভ প্রোডাকশন সার্ভারে ব্যবহারযোগ্য?",
            "answer": "হ্যাঁ, প্রতিটি আউটপুট অফিসিয়াল IETF RFC এবং ইন্ডাস্ট্রি স্ট্যান্ডার্ড মেনে তৈরি হয়, যা সরাসরি সার্ভার বা ডিএনএস ম্যানেজমেন্টে ব্যবহার করা যায়।"
        }
    ]

    return {
        "name": name,
        "short_description": desc,
        "meta_title": meta_title,
        "meta_description": meta_desc,
        "how_to_guide": {
            "title": f"{name} ব্যবহারের নিয়মাবলী",
            "steps": steps
        },
        "technical_specs": {
            "title": f"{name}-এর কারিগরি বৈশিষ্ট্য",
            "items": specs
        },
        "faqs": faqs
    }

def format_php_array(data, indent=8):
    lines = []
    ind = " " * indent
    lines.append(f"{ind}'name' => '{escape_php_str(data['name'])}',")
    lines.append(f"{ind}'short_description' => '{escape_php_str(data['short_description'])}',")
    lines.append(f"{ind}'meta_title' => '{escape_php_str(data['meta_title'])}',")
    lines.append(f"{ind}'meta_description' => '{escape_php_str(data['meta_description'])}',")

    lines.append(f"{ind}'how_to_guide' => [")
    lines.append(f"{ind}    'title' => '{escape_php_str(data['how_to_guide']['title'])}',")
    lines.append(f"{ind}    'steps' => [")
    for s in data['how_to_guide']['steps']:
        lines.append(f"{ind}        '{escape_php_str(s)}',")
    lines.append(f"{ind}    ],")
    lines.append(f"{ind}],")

    lines.append(f"{ind}'technical_specs' => [")
    lines.append(f"{ind}    'title' => '{escape_php_str(data['technical_specs']['title'])}',")
    lines.append(f"{ind}    'items' => [")
    for it in data['technical_specs']['items']:
        lines.append(f"{ind}        '{escape_php_str(it)}',")
    lines.append(f"{ind}    ],")
    lines.append(f"{ind}],")

    lines.append(f"{ind}'faqs' => [")
    for f in data['faqs']:
        lines.append(f"{ind}    [")
        lines.append(f"{ind}        'question' => '{escape_php_str(f['question'])}',")
        lines.append(f"{ind}        'answer' => '{escape_php_str(f['answer'])}',")
        lines.append(f"{ind}    ],")
    lines.append(f"{ind}],")

    return "\n".join(lines)

def update_lang_file(path, is_bn=False):
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    new_blocks = []
    for t in NETWORK_TOOLS:
        slug = t['slug']
        if f"'{slug}' => [" in content:
            continue
        
        data = get_bn_content(t) if is_bn else get_en_content(t)
        block = f"    '{slug}' => [\n" + format_php_array(data) + "\n    ],"
        new_blocks.append(block)

    if new_blocks:
        pos = content.rfind("];")
        if pos != -1:
            insertion = "\n\n" + "\n\n".join(new_blocks) + "\n"
            content = content[:pos] + insertion + content[pos:]
            with open(path, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f"Added {len(new_blocks)} entries to {path}")
    else:
        print(f"All entries already present in {path}")

if __name__ == '__main__':
    update_lang_file('lang/en/tools.php', is_bn=False)
    update_lang_file('lang/bn/tools.php', is_bn=True)
