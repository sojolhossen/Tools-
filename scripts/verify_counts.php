<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$items = config('tools.items');
foreach ($items as $slug => $data) {
    if (in_array($slug, ['what-is-my-ip', 'ip-lookup', 'user-agent-parser', 'browser-info', 'webrtc-ip-leak-checker',
        'dns-lookup', 'reverse-dns-lookup', 'whois-lookup', 'dns-propagation-checker', 'spf-dkim-dmarc-checker',
        'ping-tester', 'traceroute-online', 'port-checker', 'internet-speed-test', 'subnet-calculator',
        'http-headers-lookup', 'http-status-checker', 'security-headers-checker', 'redirect-checker', 'ssl-checker',
        'robots-txt-generator', 'robots-txt-validator', 'xml-sitemap-generator', 'xml-sitemap-validator', 'htaccess-generator', 'nginx-config-generator',
        'meta-tag-generator', 'open-graph-generator', 'twitter-card-generator', 'schema-generator',
        'source-code-viewer', 'url-parser', 'url-encoder-decoder', 'compression-checker', 'website-uptime-checker'])) {
        if ($data['category'] !== 'network') {
            echo $slug . ' has category ' . $data['category'] . PHP_EOL;
        }
    }
}

