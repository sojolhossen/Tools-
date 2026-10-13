import re
import os
from network_definitions import NETWORK_TOOLS
from security_definitions import TOOLS as SECURITY_TOOLS

def escape_php_str(val):
    if not isinstance(val, str):
        return str(val)
    return val.replace('\\', '\\\\').replace("'", "\\'")

def update_tool_model():
    path = 'app/Models/Tool.php'
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Check if security and network views are in ALLOWLISTED_VIEWS
    all_needed_views = []
    for t in SECURITY_TOOLS:
        all_needed_views.append(f"tools.{t['slug']}")
    for t in NETWORK_TOOLS:
        all_needed_views.append(f"tools.{t['slug']}")
        
    views_to_add = []
    for v in all_needed_views:
        if f"'{v}'," not in content:
            views_to_add.append(f"        '{v}',")
            
    if views_to_add:
        target = "    public const ALLOWLISTED_VIEWS = [\n"
        replacement = target + "\n".join(views_to_add) + "\n"
        content = content.replace(target, replacement, 1)
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Added {len(views_to_add)} views to Tool::ALLOWLISTED_VIEWS in {path}")
    else:
        print("Tool::ALLOWLISTED_VIEWS already up to date.")

def update_config_tools():
    path = 'config/tools.php'
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Move existing ones to network if needed
    for s in ['htaccess-generator', 'nginx-config-generator', 'url-encoder-decoder']:
        pattern = rf"('{s}'\s*=>\s*\[\s*'category'\s*=>\s*)'[^']+'"
        content = re.sub(pattern, r"\1'network'", content)

    # Now add any missing tools before the end of 'items' => [
    # Locate end of items array
    new_entries = []
    for t in NETWORK_TOOLS:
        slug = t['slug']
        if f"'{slug}'" in content:
            # ensure category is network
            pattern = rf"('{slug}'\s*=>\s*\[\s*'category'\s*=>\s*)'[^']+'"
            content = re.sub(pattern, r"\1'network'", content)
            continue
        
        entry = f"""        '{slug}' => [
            'category' => 'network',
            'icon' => '{t['icon']}',
            'view' => 'tools.{slug}',
            'view_handler' => 'tools.{slug}',
            'name_key' => 'tools.{slug}.name',
            'description_key' => 'tools.{slug}.short_description',
            'meta_title_key' => 'tools.{slug}.meta_title',
            'meta_description_key' => 'tools.{slug}.meta_description',
            'route_name' => 'tools.show',
            'status' => 'active',
            'is_featured' => {'true' if t['is_featured'] else 'false'},
            'is_popular' => {'true' if t['is_popular'] else 'false'},
            'sort_order' => {t['sort_order']},
            'tags' => [{', '.join(repr(x) for x in t['tags'])}],
            'supported_locales' => ['en', 'bn'],
        ],"""
        new_entries.append(entry)

    if new_entries:
        # Find where items array ends
        # In config/tools.php, find the last `];` before return end or before another section
        # Look for the last `],` inside items
        marker = "    'items' => [\n"
        pos = content.find(marker)
        if pos != -1:
            insertion_pos = pos + len(marker)
            content = content[:insertion_pos] + "\n".join(new_entries) + "\n" + content[insertion_pos:]
            with open(path, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f"Added {len(new_entries)} tools to config/tools.php")
    else:
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)
        print("All network tools already present in config/tools.php (updated categories).")

if __name__ == '__main__':
    update_tool_model()
    update_config_tools()
