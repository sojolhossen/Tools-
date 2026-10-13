# -*- coding: utf-8 -*-
import os
import sys

from calculators_data import calculators_data

BASE_DIR = r"d:\xampp\htdocs\tools"

def update_config_tools():
    config_path = os.path.join(BASE_DIR, "config", "tools.php")
    with open(config_path, "r", encoding="utf-8") as f:
        content = f.read()

    last_bracket_idx = content.rfind("    ],")
    if last_bracket_idx == -1:
        print("Could not find end of items in config/tools.php")
        return

    new_items_code = []
    for t in calculators_data:
        slug = t["slug"]
        snake_slug = slug.replace("-", "_")
        icon = t["icon"]
        tags_str = ", ".join([f"'{tag}'" for tag in t["tags"]])

        if f"'{slug}' => [" in content:
            continue

        item_str = f"""        '{slug}' => [
            'slug' => '{slug}',
            'name_key' => 'tools.{snake_slug}.name',
            'description_key' => 'tools.{snake_slug}.short_desc',
            'category' => 'calculators',
            'icon' => '{icon}',
            'route_name' => 'tools.show',
            'status' => 'active',
            'supported_locales' => ['en', 'bn'],
            'view' => 'tools.{slug}',
            'is_featured' => False,
            'is_popular' => False,
            'meta_title_key' => 'tools.{snake_slug}.meta_title',
            'meta_description_key' => 'tools.{snake_slug}.meta_desc',
            'tags' => [{tags_str}],
        ],"""
        new_items_code.append(item_str)

    if new_items_code:
        updated_content = content[:last_bracket_idx] + "\n".join(new_items_code) + "\n" + content[last_bracket_idx:]
        with open(config_path, "w", encoding="utf-8") as f:
            f.write(updated_content)
        print(f"Added {len(new_items_code)} items to config/tools.php")
    else:
        print("All items already exist in config/tools.php")

def update_lang_en():
    en_path = os.path.join(BASE_DIR, "lang", "en", "tools.php")
    with open(en_path, "r", encoding="utf-8") as f:
        content = f.read()

    last_bracket_idx = content.rfind("];")
    if last_bracket_idx == -1:
        print("Could not find closing bracket in lang/en/tools.php")
        return

    new_entries = []
    for t in calculators_data:
        snake_slug = t["slug"].replace("-", "_")
        if f"'{snake_slug}' => [" in content:
            continue
        
        name = t["en"]["name"].replace("'", "\\'")
        short_desc = t["en"]["short_desc"].replace("'", "\\'")
        meta_title = t["en"]["meta_title"].replace("'", "\\'")
        meta_desc = t["en"]["meta_desc"].replace("'", "\\'")

        entry = f"""    '{snake_slug}' => [
        'name' => '{name}',
        'short_desc' => '{short_desc}',
        'meta_title' => '{meta_title}',
        'meta_desc' => '{meta_desc}',
    ],"""
        new_entries.append(entry)

    if new_entries:
        updated_content = content[:last_bracket_idx] + "\n".join(new_entries) + "\n" + content[last_bracket_idx:]
        with open(en_path, "w", encoding="utf-8") as f:
            f.write(updated_content)
        print(f"Added {len(new_entries)} entries to lang/en/tools.php")
    else:
        print("All entries already exist in lang/en/tools.php")

def update_lang_bn():
    bn_path = os.path.join(BASE_DIR, "lang", "bn", "tools.php")
    with open(bn_path, "r", encoding="utf-8") as f:
        content = f.read()

    last_bracket_idx = content.rfind("];")
    if last_bracket_idx == -1:
        print("Could not find closing bracket in lang/bn/tools.php")
        return

    new_entries = []
    for t in calculators_data:
        snake_slug = t["slug"].replace("-", "_")
        if f"'{snake_slug}' => [" in content:
            continue
        
        name = t["bn"]["name"].replace("'", "\\'")
        short_desc = t["bn"]["short_desc"].replace("'", "\\'")
        meta_title = t["bn"]["meta_title"].replace("'", "\\'")
        meta_desc = t["bn"]["meta_desc"].replace("'", "\\'")

        entry = f"""    '{snake_slug}' => [
        'name' => '{name}',
        'short_desc' => '{short_desc}',
        'meta_title' => '{meta_title}',
        'meta_desc' => '{meta_desc}',
    ],"""
        new_entries.append(entry)

    if new_entries:
        updated_content = content[:last_bracket_idx] + "\n".join(new_entries) + "\n" + content[last_bracket_idx:]
        with open(bn_path, "w", encoding="utf-8") as f:
            f.write(updated_content)
        print(f"Added {len(new_entries)} entries to lang/bn/tools.php")
    else:
        print("All entries already exist in lang/bn/tools.php")

def update_test_slugs():
    test_path = os.path.join(BASE_DIR, "tests", "Feature", "ToolsPageTest.php")
    with open(test_path, "r", encoding="utf-8") as f:
        content = f.read()

    provider_end_idx = content.find("        ];\n    }")
    if provider_end_idx == -1:
        print("Could not find provider end in ToolsPageTest.php")
        return

    new_slug_lines = []
    for t in calculators_data:
        slug = t["slug"]
        if f"['{slug}']" in content:
            continue
        new_slug_lines.append(f"            ['{slug}'],")

    if new_slug_lines:
        updated_content = content[:provider_end_idx] + "\n".join(new_slug_lines) + "\n" + content[provider_end_idx:]
        with open(test_path, "w", encoding="utf-8") as f:
            f.write(updated_content)
        print(f"Added {len(new_slug_lines)} slugs to ToolsPageTest.php")
    else:
        print("All slugs already exist in ToolsPageTest.php")

if __name__ == "__main__":
    update_config_tools()
    update_lang_en()
    update_lang_bn()
    update_test_slugs()
