<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tool Categories
    |--------------------------------------------------------------------------
    |
    | Supported categories for organizing tools across the platform.
    |
    */
    'categories' => [
        'developer' => [
            'icon' => 'code-bracket',
            'color' => 'amber',
        ],
        'text' => [
            'icon' => 'document-text',
            'color' => 'indigo',
        ],
        'image' => [
            'icon' => 'photo',
            'color' => 'violet',
        ],
        'calculators' => [
            'icon' => 'calculator',
            'color' => 'emerald',
        ],
        'unit-converters' => [
            'icon' => 'scale',
            'color' => 'cyan',
        ],
        'finance' => [
            'icon' => 'banknotes',
            'color' => 'green',
        ],
        'security' => [
            'icon' => 'shield-check',
            'color' => 'rose',
        ],
        'network' => [
            'icon' => 'globe',
            'color' => 'blue',
        ],
        'seo' => [
            'icon' => 'chart-bar',
            'color' => 'purple',
        ],
        'color-design' => [
            'icon' => 'swatch',
            'color' => 'pink',
        ],
        'pdf' => [
            'icon' => 'document-duplicate',
            'color' => 'red',
        ],
        'mobile-dev' => [
            'icon' => 'device-phone-mobile',
            'color' => 'teal',
        ],
        'database' => [
            'icon' => 'circle-stack',
            'color' => 'orange',
        ],
        'audio-video' => [
            'icon' => 'video-camera',
            'color' => 'fuchsia',
        ],
        'file-archive' => [
            'icon' => 'archive-box',
            'color' => 'yellow',
        ],
        'productivity' => [
            'icon' => 'clock',
            'color' => 'sky',
        ],
        'education' => [
            'icon' => 'academic-cap',
            'color' => 'lime',
        ],
        'ecommerce' => [
            'icon' => 'shopping-cart',
            'color' => 'amber',
        ],
        'generators' => [
            'icon' => 'sparkles',
            'color' => 'indigo',
        ],
        'ai-tools' => [
            'icon' => 'cpu-chip',
            'color' => 'violet',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tool Definitions
    |--------------------------------------------------------------------------
    |
    | Central registry of all platform tools.
    |
    */
    'items' => [
        'word-counter' => [
            'slug' => 'word-counter',
            'name_key' => 'tools.word_counter.name',
            'description_key' => 'tools.word_counter.short_desc',
            'category' => 'text',
            'icon' => 'text-quote',
            'route_name' => 'tools.show',
            'status' => 'active',
            'supported_locales' => ['en', 'bn'],
            'view' => 'tools.word-counter',
            'is_featured' => true,
            'is_popular' => true,
            'meta_title_key' => 'tools.word_counter.meta_title',
            'meta_description_key' => 'tools.word_counter.meta_desc',
            'tags' => ['word count', 'character count', 'reading time', 'bangla words', 'text statistics'],
        ],
        'case-converter' => [
            'slug' => 'case-converter',
            'name_key' => 'tools.case_converter.name',
            'description_key' => 'tools.case_converter.short_desc',
            'category' => 'text',
            'icon' => 'arrows-up-down',
            'route_name' => 'tools.show',
            'status' => 'active',
            'supported_locales' => ['en', 'bn'],
            'view' => 'tools.case-converter',
            'is_featured' => false,
            'is_popular' => true,
            'meta_title_key' => 'tools.case_converter.meta_title',
            'meta_description_key' => 'tools.case_converter.meta_desc',
            'tags' => ['uppercase', 'lowercase', 'title case', 'sentence case', 'camelCase', 'snake_case'],
        ],
        'percentage-calculator' => [
            'slug' => 'percentage-calculator',
            'name_key' => 'tools.percentage_calculator.name',
            'description_key' => 'tools.percentage_calculator.short_desc',
            'category' => 'calculators',
            'icon' => 'calculator',
            'route_name' => 'tools.show',
            'status' => 'active',
            'supported_locales' => ['en', 'bn'],
            'view' => 'tools.percentage-calculator',
            'is_featured' => true,
            'is_popular' => true,
            'meta_title_key' => 'tools.percentage_calculator.meta_title',
            'meta_description_key' => 'tools.percentage_calculator.meta_desc',
            'tags' => ['percentage', 'discount', 'percentage increase', 'percent decrease', 'price calculator'],
        ],
        'json-formatter' => [
            'slug' => 'json-formatter',
            'name_key' => 'tools.json_formatter.name',
            'description_key' => 'tools.json_formatter.short_desc',
            'category' => 'developer',
            'icon' => 'code-bracket',
            'route_name' => 'tools.show',
            'status' => 'active',
            'supported_locales' => ['en', 'bn'],
            'view' => 'tools.json-formatter',
            'is_featured' => true,
            'is_popular' => true,
            'meta_title_key' => 'tools.json_formatter.meta_title',
            'meta_description_key' => 'tools.json_formatter.meta_desc',
            'tags' => ['json format', 'json validator', 'json prettify', 'json minify', 'beautify json'],
        ],
        'qr-generator' => [
            'slug' => 'qr-generator',
            'name_key' => 'tools.qr_generator.name',
            'description_key' => 'tools.qr_generator.short_desc',
            'category' => 'productivity',
            'icon' => 'qr-code',
            'route_name' => 'tools.show',
            'status' => 'active',
            'supported_locales' => ['en', 'bn'],
            'view' => 'tools.qr-generator',
            'is_featured' => true,
            'is_popular' => true,
            'meta_title_key' => 'tools.qr_generator.meta_title',
            'meta_description_key' => 'tools.qr_generator.meta_desc',
            'tags' => ['qr code', 'free qr generator', 'create qr code', 'download qr', 'url qr code'],
        ],
        'image-compressor' => [
            'slug' => 'image-compressor',
            'name_key' => 'tools.image_compressor.name',
            'description_key' => 'tools.image_compressor.short_desc',
            'category' => 'image',
            'icon' => 'arrow-down-tray',
            'route_name' => 'tools.show',
            'status' => 'active',
            'supported_locales' => ['en', 'bn'],
            'view' => 'tools.image-compressor',
            'is_featured' => true,
            'is_popular' => true,
            'meta_title_key' => 'tools.image_compressor.meta_title',
            'meta_description_key' => 'tools.image_compressor.meta_desc',
            'tags' => ['image compressor', 'compress jpg', 'reduce png size', 'webp compression', 'photo size reducer'],
        ],
        'image-resizer' => [
            'slug' => 'image-resizer',
            'name_key' => 'tools.image_resizer.name',
            'description_key' => 'tools.image_resizer.short_desc',
            'category' => 'image',
            'icon' => 'arrows-pointing-out',
            'route_name' => 'tools.show',
            'status' => 'active',
            'supported_locales' => ['en', 'bn'],
            'view' => 'tools.image-resizer',
            'is_featured' => false,
            'is_popular' => false,
            'meta_title_key' => 'tools.image_resizer.meta_title',
            'meta_description_key' => 'tools.image_resizer.meta_desc',
            'tags' => ['resize image', 'change dimensions', 'photo resizer', 'aspect ratio resize', 'convert image format'],
        ],
        'image-to-pdf' => [
            'slug' => 'image-to-pdf',
            'name_key' => 'tools.image_to_pdf.name',
            'description_key' => 'tools.image_to_pdf.short_desc',
            'category' => 'pdf',
            'icon' => 'document-duplicate',
            'route_name' => 'tools.show',
            'status' => 'active',
            'supported_locales' => ['en', 'bn'],
            'view' => 'tools.image-to-pdf',
            'is_featured' => true,
            'is_popular' => true,
            'meta_title_key' => 'tools.image_to_pdf.meta_title',
            'meta_description_key' => 'tools.image_to_pdf.meta_desc',
            'tags' => ['image to pdf', 'jpg to pdf', 'png to pdf', 'convert photo to pdf', 'free pdf maker'],
        ],
    ],
];
