import glob
import os
import re

files = [
    'case-converter.blade.php',
    'image-compressor.blade.php',
    'image-resizer.blade.php',
    'image-to-pdf.blade.php',
    'json-formatter.blade.php',
    'percentage-calculator.blade.php',
    'qr-generator.blade.php',
    'word-counter.blade.php'
]

for fn in files:
    path = os.path.join('resources', 'views', 'tools', fn)
    if not os.path.exists(path):
        continue
    with open(path, 'r', encoding='utf-8') as f:
        c = f.read()

    # Dropzones
    c = c.replace('class="bg-white rounded-3xl border-2 border-dashed border-slate-300', 'class="bg-white dark:bg-slate-900/60 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700')
    c = c.replace('p-6 rounded-2xl bg-white border border-slate-200/80', 'p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800')
    c = c.replace('p-5 rounded-2xl bg-white border border-slate-200/80', 'p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800')
    c = c.replace('bg-white/70 backdrop-blur-xs', 'bg-white/70 dark:bg-slate-950/70 backdrop-blur-xs')
    
    # Textarea / pre formatted boxes
    c = re.sub(r'bg-slate-50\b(?! dark:)', 'bg-slate-50 dark:bg-slate-950', c)
    c = re.sub(r'bg-slate-50/50\b(?! dark:)', 'bg-slate-50/50 dark:bg-slate-950', c)
    c = re.sub(r'border-slate-200\b(?! dark:)', 'border-slate-200 dark:border-slate-700', c)
    c = re.sub(r'border-slate-200/80\b(?! dark:)', 'border-slate-200/80 dark:border-slate-800', c)
    c = re.sub(r'text-slate-900\b(?! dark:)', 'text-slate-900 dark:text-slate-100', c)
    c = re.sub(r'text-slate-700\b(?! dark:)', 'text-slate-700 dark:text-slate-300', c)
    c = re.sub(r'text-slate-600\b(?! dark:)', 'text-slate-600 dark:text-slate-400', c)

    with open(path, 'w', encoding='utf-8') as f:
        f.write(c)
    print('Updated:', fn)

print('All starter tools updated successfully!')
