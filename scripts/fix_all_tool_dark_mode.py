import glob
import os
import re

files = glob.glob('resources/views/tools/*.blade.php')

for f in files:
    with open(f, 'r', encoding='utf-8') as fp:
        c = fp.read()
    orig = c

    # 1. Format/ratio option buttons
    c = c.replace("'bg-slate-100 text-slate-700'", "'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200'")
    c = c.replace("'bg-slate-100 text-slate-700 hover:bg-slate-200'", "'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700'")
    c = c.replace("bg-slate-100 hover:bg-slate-200", "bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200")
    c = c.replace("hover:bg-slate-100 disabled:opacity", "hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity")
    
    # 2. Thumbnail / preview boxes
    c = c.replace("overflow-hidden bg-slate-100 flex items-center", "overflow-hidden bg-slate-100 dark:bg-slate-950 flex items-center")
    c = c.replace("overflow-hidden bg-slate-100 border border-slate-200", "overflow-hidden bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800")
    c = c.replace("rounded-xl bg-slate-100 text-xs font-semibold text-slate-600", "rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-semibold text-slate-600 dark:text-slate-300")
    c = c.replace("rounded-xl bg-slate-100 text-xs font-bold text-slate-600", "rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300")
    
    # 3. Action buttons (copy, format, clear)
    c = c.replace("bg-slate-100 text-slate-700 dark:text-slate-300 rounded-xl", "bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl")
    c = c.replace("bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300", "bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300")
    c = c.replace("font-semibold bg-slate-100 text-slate-700 dark:text-slate-300", "font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300")
    c = c.replace("text-slate-600 dark:text-slate-400 bg-slate-100", "text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800")
    
    # 4. Percentage calculator explanation boxes
    c = c.replace("bg-white/80 p-3 rounded-xl border border-indigo-100", "bg-white/80 dark:bg-indigo-950/30 p-3 rounded-xl border border-indigo-100 dark:border-indigo-900/40")
    c = c.replace("bg-white/80 p-3 rounded-xl border border-emerald-100", "bg-white/80 dark:bg-emerald-950/30 p-3 rounded-xl border border-emerald-100 dark:border-emerald-900/40")
    c = c.replace("text-indigo-900/70", "text-indigo-900/70 dark:text-indigo-300")
    c = c.replace("text-emerald-900/70", "text-emerald-900/70 dark:text-emerald-300")
    
    # 5. Tip calculator tip percent buttons
    c = c.replace("class=\"py-1.5 text-xs font-bold rounded-lg border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100", "class=\"py-1.5 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700")

    # 6. Duotone filter buttons
    c = c.replace("class=\"px-2.5 py-1 text-xs rounded-lg bg-slate-100 hover:bg-slate-200\"", "class=\"px-2.5 py-1 text-xs rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200\"")

    if c != orig:
        with open(f, 'w', encoding='utf-8') as fp:
            fp.write(c)
        print('Updated dark mode:', os.path.basename(f))

print('Finished updating tool dark mode styling.')
