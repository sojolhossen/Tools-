import re
from network_definitions import NETWORK_TOOLS
from build_full_security_suite import ALL_TOOLS as SECURITY_TOOLS

test_file = 'tests/Feature/ToolsPageTest.php'
with open(test_file, 'r', encoding='utf-8') as f:
    content = f.read()

all_slugs = []
for t in SECURITY_TOOLS:
    all_slugs.append(t['slug'])
for t in NETWORK_TOOLS:
    all_slugs.append(t['slug'])

slugs_to_add = []
for s in all_slugs:
    entry = f"            ['{s}'],"
    if entry not in content:
        slugs_to_add.append(entry)

if slugs_to_add:
    target = "            ['risk-reward-calculator'],\n"
    replacement = target + "\n".join(slugs_to_add) + "\n"
    content = content.replace(target, replacement, 1)
    with open(test_file, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Added {len(slugs_to_add)} tools to {test_file}")
else:
    print(f"All tools already in {test_file}")
