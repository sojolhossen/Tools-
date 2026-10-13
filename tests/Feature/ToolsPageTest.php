<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class ToolsPageTest extends TestCase
{
    /**
     * @return array<int, array<int, string>>
     */
    public static function toolSlugsProvider(): array
    {
        return [
            ['word-counter'],
            ['case-converter'],
            ['percentage-calculator'],
            ['json-formatter'],
            ['qr-generator'],
            ['image-compressor'],
            ['image-resizer'],
            ['image-to-pdf'],
            ['html-css-js-beautifier'],
            ['sql-formatter'],
            ['xml-formatter'],
            ['yaml-json-converter'],
            ['css-minifier'],
            ['js-minifier'],
            ['html-minifier'],
            ['base64-encoder-decoder'],
            ['url-encoder-decoder'],
            ['html-entities-encoder'],
            ['jwt-decoder'],
            ['hex-binary-text-converter'],
            ['regex-tester'],
            ['cron-generator'],
            ['chmod-calculator'],
            ['curl-converter'],
            ['uuid-generator'],
            ['nanoid-generator'],
            ['dummy-json-generator'],
            ['bcrypt-hash-generator'],
            ['code-diff-checker'],
            ['string-escape-unescape'],
            ['json-to-kotlin'],
            ['json-to-java-pojo'],
            ['json-to-dart'],
            ['json-to-typescript'],
            ['http-status-lookup'],
            ['query-string-json-converter'],
            ['api-payload-tester'],
            ['url-path-normalizer'],
            ['htaccess-generator'],
            ['nginx-config-generator'],
            ['gitignore-generator'],
            ['hmac-generator'],
            ['xml-json-converter'],
            ['csv-to-sql'],
            ['markdown-table-generator'],
            ['html-table-converter'],
            ['svg-path-visualizer'],
        ];
    }

    #[DataProvider('toolSlugsProvider')]
    public function test_all_tools_render_successfully_in_english(string $slug): void
    {
        $response = $this->get("/en/tools/{$slug}");
        $response->assertStatus(200);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="bn"', false);
        $response->assertSee('100% Client-Side');
    }

    #[DataProvider('toolSlugsProvider')]
    public function test_all_tools_render_successfully_in_bangla(string $slug): void
    {
        $response = $this->get("/bn/tools/{$slug}");
        $response->assertStatus(200);
        $response->assertSee('<html lang="bn"', false);
        $response->assertSee('সম্পূর্ণ ব্রাউজারে চালিত');
    }

    public function test_non_existent_tool_returns_404(): void
    {
        $response = $this->get('/en/tools/fake-tool-does-not-exist');
        $response->assertStatus(404);
    }
}
