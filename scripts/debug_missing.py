from network_definitions import NETWORK_TOOLS
with open('config/tools.php', 'r', encoding='utf-8') as f:
    c = f.read()

missing = []
present = []
for t in NETWORK_TOOLS:
    slug = t['slug']
    if f"'{slug}' => [" in c:
        present.append(slug)
    else:
        missing.append(slug)

print(f"Total present: {len(present)}")
print(f"Total missing: {len(missing)} -> {missing}")
