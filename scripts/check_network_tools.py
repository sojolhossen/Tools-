import re

with open('config/tools.php', 'r', encoding='utf-8') as f:
    content = f.read()

matches = re.findall(r"'([a-z0-9-]+)'\s*=>\s*\[[^\]]*?'category'\s*=>\s*'([^']+)'", content)
cat_counts = {}
for slug, cat in matches:
    cat_counts[cat] = cat_counts.get(cat, 0) + 1

print("Categories in config:")
for cat, count in sorted(cat_counts.items()):
    print(f"  {cat}: {count}")

seo_or_net = [slug for slug, cat in matches if cat in ['seo', 'network']]
print("Existing SEO/Network items:", seo_or_net)
