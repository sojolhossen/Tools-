import subprocess
import json
from network_definitions import NETWORK_TOOLS

# Run an artisan tinker command to sync network tools into DB
php_script = """
$networkTools = config('tools.items');
$networkCount = 0;
foreach ($networkTools as $slug => $data) {
    if ($data['category'] !== 'network') {
        continue;
    }
    
    $existing = App\\Models\\Tool::where('slug', $slug)->first();
    $nameEn = __('tools.' . $slug . '.name', [], 'en');
    $nameBn = __('tools.' . $slug . '.name', [], 'bn');
    $descEn = __('tools.' . $slug . '.short_description', [], 'en');
    $descBn = __('tools.' . $slug . '.short_description', [], 'bn');
    $metaTitleEn = __('tools.' . $slug . '.meta_title', [], 'en');
    $metaTitleBn = __('tools.' . $slug . '.meta_title', [], 'bn');
    $metaDescEn = __('tools.' . $slug . '.meta_description', [], 'en');
    $metaDescBn = __('tools.' . $slug . '.meta_description', [], 'bn');

    $payload = [
        'slug' => $slug,
        'category_slug' => 'network',
        'name_en' => $nameEn,
        'name_bn' => $nameBn,
        'description_en' => $descEn,
        'description_bn' => $descBn,
        'meta_title_en' => $metaTitleEn,
        'meta_title_bn' => $metaTitleBn,
        'meta_description_en' => $metaDescEn,
        'meta_description_bn' => $metaDescBn,
        'icon' => $data['icon'] ?? 'globe',
        'view_handler' => $data['view'] ?? ('tools.' . $slug),
        'route_name' => $data['route_name'] ?? 'tools.show',
        'status' => 'active',
        'is_featured' => $data['is_featured'] ?? false,
        'is_popular' => $data['is_popular'] ?? false,
        'sort_order' => $data['sort_order'] ?? 10,
        'tags' => $data['tags'] ?? [],
        'supported_locales' => ['en', 'bn'],
    ];

    if ($existing) {
        $existing->update($payload);
    } else {
        App\\Models\\Tool::create($payload);
    }
    $networkCount++;
}
echo 'Synced ' . $networkCount . ' network tools into DB.' . PHP_EOL;
"""

with open('scripts/db_sync_temp.php', 'w', encoding='utf-8') as f:
    f.write(f"<?php\nrequire 'vendor/autoload.php';\n$app = require_once 'bootstrap/app.php';\n$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();\n{php_script}\n")

res = subprocess.run(['d:\\xampp\\php\\php.exe', 'scripts/db_sync_temp.php'], capture_output=True, text=True)
print(res.stdout)
if res.stderr:
    print("Error:", res.stderr)
