import glob
import os
import re

files = glob.glob('resources/views/tools/*.blade.php')
issues = {}

for f in files:
    name = os.path.basename(f)
    with open(f, 'r', encoding='utf-8') as fp:
        lines = fp.readlines()
    
    file_issues = []
    for idx, l in enumerate(lines):
        line_clean = l.strip()
        # ignore svg tags, comments, canvas wrapper
        if '<svg' in line_clean or '<path' in line_clean or 'qrCanvas' in line_clean or line_clean.startswith('//'):
            continue
        # Check bg-white without dark:bg
        if 'bg-white' in line_clean and 'dark:bg-' not in line_clean:
            file_issues.append((idx + 1, 'bg-white', line_clean[:90]))
        elif 'bg-slate-50' in line_clean and 'dark:bg-' not in line_clean:
            file_issues.append((idx + 1, 'bg-slate-50', line_clean[:90]))
        elif 'bg-slate-100' in line_clean and 'dark:bg-' not in line_clean:
            file_issues.append((idx + 1, 'bg-slate-100', line_clean[:90]))
            
    if file_issues:
        issues[name] = file_issues

print(f'Total tools: {len(files)}')
print(f'Tools with light-only backgrounds: {len(issues)}')
for name, list_iss in sorted(issues.items()):
    print(f'{name}: {len(list_iss)} issues')
