import subprocess

script = """
try {
    $tool = app(App\\Support\\Tools\\ToolRegistry::class)->find('schema-generator');
    $html = view($tool->view, [
        'tool' => $tool,
        'locale' => 'en',
        'schema' => [],
        'relatedTools' => collect(),
    ])->render();
    echo 'SUCCESS: Rendered ' . strlen($html) . ' bytes' . PHP_EOL;
} catch (\\Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    echo 'FILE: ' . $e->getFile() . ':' . $e->getLine() . PHP_EOL;
}
"""

with open('scripts/test_render.php', 'w', encoding='utf-8') as f:
    f.write(f"<?php\nrequire 'vendor/autoload.php';\n$app = require_once 'bootstrap/app.php';\n$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();\n{script}\n")

res = subprocess.run(['d:\\xampp\\php\\php.exe', 'scripts/test_render.php'], capture_output=True, text=True)
print(res.stdout)
