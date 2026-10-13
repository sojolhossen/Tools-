from network_definitions import NETWORK_TOOLS
with open('app/Models/Tool.php', 'r', encoding='utf-8') as f:
    c = f.read()
missing = []
for t in NETWORK_TOOLS:
    v = f"'tools.{t['slug']}'"
    if v not in c:
        missing.append(t['slug'])
print('Missing from Tool::ALLOWLISTED_VIEWS:', missing)
