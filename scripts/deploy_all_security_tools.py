# -*- coding: utf-8 -*-
"""
Full deploy script for all 34 Security & Cryptography tools.
Generates:
1. 34 Blade views with dark mode & Web Crypto API functionality
2. config/tools.php entries
3. lang/en/tools.php entries
4. lang/bn/tools.php entries
5. DB sync
"""
import os
import sys

BASE_DIR = r"d:\xampp\htdocs\tools"
VIEWS_DIR = os.path.join(BASE_DIR, "resources", "views", "tools")

from build_full_security_suite import ALL_TOOLS

print(f"Loaded {len(ALL_TOOLS)} tools.")
