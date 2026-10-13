# -*- coding: utf-8 -*-
"""
Full Generator for ToolZeno's Security & Cryptography Suite (34 tools)
Generates:
1. 34 Blade views in resources/views/tools/
2. Config definitions in config/tools.php
3. Language files in lang/en/tools.php and lang/bn/tools.php
4. Database synchronizer
"""
import os
import sys

BASE_DIR = r"d:\xampp\htdocs\tools"
VIEWS_DIR = os.path.join(BASE_DIR, "resources", "views", "tools")

from build_full_security_suite import ALL_TOOLS

print(f"Loaded {len(ALL_TOOLS)} tool definitions.")
