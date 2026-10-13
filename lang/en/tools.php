<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Tool 1: Word and Character Counter
    |--------------------------------------------------------------------------
    */
    'word_counter' => [
        'name' => 'Word & Character Counter',
        'short_desc' => 'Count words, characters, sentences, paragraphs, and reading time in real-time with full Unicode and Bangla support.',
        'meta_title' => 'Word Counter — Count Words and Characters | ToolZeno',
        'meta_desc' => 'Count words, characters, sentences, paragraphs, reading and speaking time in real time. Works directly in your browser with full English and Bangla support.',
        'input_label' => 'Enter or paste your text below',
        'input_placeholder' => 'Start typing or paste your content here to inspect real-time word and character counts (Bangla and Unicode fully supported)...',
        'metrics' => [
            'words' => 'Words',
            'characters' => 'Characters',
            'characters_no_spaces' => 'Characters (no spaces)',
            'sentences' => 'Sentences',
            'paragraphs' => 'Paragraphs',
            'reading_time' => 'Estimated Reading Time',
            'speaking_time' => 'Speaking Time',
        ],
        'wpm_note' => 'Calculated at an average speed of 200 words per minute for reading and 130 words per minute for speaking.',
        'how_to_title' => 'How to Use the Word Counter',
        'how_to_steps' => [
            'Paste or type any text into the main editor area above.',
            'View live counters for total words, characters, sentences, and paragraphs as you write.',
            'Review estimated reading and speaking times for speeches, essays, or social posts.',
            'Click "Copy Statistics" or "Clear" when you are finished.',
        ],
        'specs_title' => 'Technical Specifications & Limits',
        'specs' => [
            'Supported Scripts' => 'Universal Unicode (Latin, Bengali / বাংলা, Arabic, Devanagari, CJK, Emoji)',
            'Punctuation Recognition' => 'Standard (. ! ?) and Indic / Bengali Danda (।)',
            'Whitespace Tokenizer' => 'Unicode whitespace boundary regex (\s+)',
            'Reading Pace Benchmark' => '200 Words Per Minute (standard adult silent reading)',
            'Speaking Pace Benchmark' => '130 Words Per Minute (speech & podcast delivery)',
            'Server Data Transmission' => '0 bytes (100% processed in browser memory)',
        ],
        'examples_title' => 'Realistic Usage Examples',
        'examples' => [
            [
                'label' => 'English Sentence',
                'input' => 'ToolZeno provides free, privacy-first online tools for everyone.',
                'output' => '8 Words | 65 Characters (58 no spaces) | 1 Sentence | 2.4s Reading Time',
            ],
            [
                'label' => 'Bangla Sentence',
                'input' => 'টুলজেনো একটি সম্পূর্ণ ব্রাউজার-ভিত্তিক ফ্রি অনলাইন টুলস প্ল্যাটফর্ম।',
                'output' => '৭টি শব্দ | ৬০টি অক্ষর (৫৪ স্পেস ছাড়া) | ১টি বাক্য | ২.১ সে. রিডিং টাইম',
            ],
        ],
        'faq_title' => 'Frequently Asked Questions',
        'faq' => [
            [
                'q' => 'How does the counter handle hyphenated words and contractions?',
                'a' => 'Common contractions (such as "don\'t" and "it\'s") and hyphenated compound words (such as "state-of-the-art") are counted as single words in accordance with standard typographic rules.',
            ],
            [
                'q' => 'Does this word counter count Bengali words accurately?',
                'a' => 'Yes, our tokenizer respects Unicode character boundaries and correctly handles complex Bengali conjunct characters (যুক্তবর্ণ) without artificially inflating word or character counts.',
            ],
            [
                'q' => 'Is my text transmitted or saved on any server?',
                'a' => 'No. All counting logic executes strictly in your web browser using client-side JavaScript. Your confidential documents, essays, or drafts never leave your device.',
            ],
            [
                'q' => 'What is the difference between reading time and speaking time?',
                'a' => 'Reading time calculates how long a typical adult takes to read silently at 200 words per minute (WPM). Speaking time calculates presentation or podcast delivery time at a steady 130 WPM.',
            ],
        ],
        'related_context' => 'Need to adjust letter casing or capitalize headlines? Open our :link to switch between uppercase, lowercase, and title case.',
        'limitations_title' => 'Linguistic Notes & Limitations',
        'limitations_text' => 'Sentence segmentation relies on punctuation markers (. ! ? and Bengali dari ।). Text with informal punctuation or abbreviations (e.g., e.g., Mr., Dr.) may register approximate sentence counts.',
        'privacy_title' => 'Client-Side Privacy Guarantee',
        'privacy_text' => 'All text analysis executes entirely inside your web browser using JavaScript. No sentences, drafts, or sensitive documents are transmitted to our servers.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tool 2: Text Case Converter
    |--------------------------------------------------------------------------
    */
    'case_converter' => [
        'name' => 'Text Case Converter',
        'short_desc' => 'Easily switch between UPPERCASE, lowercase, Title Case, Sentence case, camelCase, snake_case, and kebab-case.',
        'meta_title' => 'Text Case Converter — Change Text Case Online | ToolZeno',
        'meta_desc' => 'Convert text to uppercase, lowercase, title case, sentence case, camelCase, snake_case, and kebab-case instantly in your browser with zero server uploads.',
        'input_label' => 'Input Text',
        'input_placeholder' => 'Type or paste the text you want to transform...',
        'output_label' => 'Transformed Result',
        'modes' => [
            'upper' => 'UPPERCASE',
            'lower' => 'lowercase',
            'title' => 'Title Case',
            'sentence' => 'Sentence case',
            'camel' => 'camelCase',
            'snake' => 'snake_case',
            'kebab' => 'kebab-case',
        ],
        'how_to_title' => 'How to Convert Text Case',
        'how_to_steps' => [
            'Enter your raw text into the input field.',
            'Click on any conversion button to apply the formatting instantly.',
            'Click "Copy Result" to copy the transformed output to your clipboard.',
        ],
        'specs_title' => 'Supported Formats & Behavior',
        'specs' => [
            'Supported Alphabets' => 'Bicameral writing systems (Latin, Cyrillic, Greek)',
            'Preserved Scripts' => 'Unicameral scripts (Bangla, Arabic, Devanagari) remain completely intact',
            'Maximum Input Size' => 'Up to 500,000 characters per conversion pass',
            'Supported Cases' => '7 standard formats (Upper, Lower, Title, Sentence, Camel, Snake, Kebab)',
            'Processing Engine' => 'Immediate client-side JavaScript regex transformations',
        ],
        'examples_title' => 'Transformation Examples',
        'examples' => [
            [
                'label' => 'Raw Input',
                'input' => 'free online tools platform',
                'output' => 'UPPERCASE: FREE ONLINE TOOLS PLATFORM | Title Case: Free Online Tools Platform | camelCase: freeOnlineToolsPlatform | snake_case: free_online_tools_platform',
            ],
            [
                'label' => 'Programming Identifier',
                'input' => 'user_profile_photo_url',
                'output' => 'camelCase: userProfilePhotoUrl | Title Case: User Profile Photo Url | kebab-case: user-profile-photo-url',
            ],
        ],
        'faq_title' => 'Frequently Asked Questions',
        'faq' => [
            [
                'q' => 'Why does case conversion leave Bengali or Arabic text unchanged?',
                'a' => 'Languages such as Bengali, Arabic, and Hindi use unicameral alphabets without capital or lowercase letterforms. The tool safely preserves these characters while correctly formatting any mixed Latin words.',
            ],
            [
                'q' => 'How does Title Case handle articles and prepositions?',
                'a' => 'Our title case engine capitalizes principal words while leaving standard short prepositions (in, on, at), conjunctions (and, but), and articles (the, a, an) in lowercase unless they begin the sentence.',
            ],
            [
                'q' => 'What is the practical difference between camelCase and snake_case?',
                'a' => 'camelCase concatenates words with inner capitals (common in JavaScript, Java, and TypeScript), whereas snake_case joins words with lowercase underscores (standard in Python, Ruby, and SQL databases).',
            ],
            [
                'q' => 'Is there any character limit for text conversion?',
                'a' => 'You can process long documents with hundreds of thousands of characters. Because conversion runs locally on your computer\'s processor, results appear in milliseconds.',
            ],
        ],
        'related_context' => 'After formatting your text casing, verify word counts and reading length with our :link, or format programming payloads with our :link2.',
        'limitations_title' => 'Script & Language Specifics',
        'limitations_text' => 'Case conversion applies to alphabets with bicameral letters (such as Latin, Cyrillic, and Greek). Unicameral writing systems such as Bangla, Arabic, or Devanagari do not possess uppercase or lowercase letters and will remain intact without corruption.',
        'privacy_title' => '100% Client-Side Processing',
        'privacy_text' => 'No text is uploaded or stored. Everything runs strictly in your local browser.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tool 3: Percentage and Discount Calculator
    |--------------------------------------------------------------------------
    */
    'percentage_calculator' => [
        'name' => 'Percentage & Discount Calculator',
        'short_desc' => 'Calculate percentages of numbers, percentage increase/decrease, and discount savings with clear formulas.',
        'meta_title' => 'Percentage Calculator — Calculate Percentages Online | ToolZeno',
        'meta_desc' => 'Calculate percentage of numbers, percentage increase or decrease, and shopping discount savings with transparent formulas and instant calculations.',
        'tabs' => [
            'simple' => 'Percentage of a Number',
            'change' => 'Percentage Increase / Decrease',
            'discount' => 'Discount & Final Price',
        ],
        'simple' => [
            'title' => 'What is X% of Y?',
            'pct_label' => 'Percentage (%)',
            'val_label' => 'Of Number',
            'result_label' => 'Result',
            'formula' => 'Formula: (Percentage ÷ 100) × Number',
        ],
        'change' => [
            'title' => 'Percentage Change (Increase / Decrease)',
            'initial_label' => 'Initial Value',
            'final_label' => 'Final Value',
            'result_label' => 'Percentage Change',
            'difference_label' => 'Absolute Difference',
            'direction_increase' => 'Increase (+)',
            'direction_decrease' => 'Decrease (-)',
            'direction_none' => 'No Change (0%)',
            'zero_error' => 'Cannot calculate percentage change when the initial value is zero (division by zero).',
            'formula' => 'Formula: ((Final Value - Initial Value) ÷ |Initial Value|) × 100',
        ],
        'discount' => [
            'title' => 'Shopping Discount Calculator',
            'price_label' => 'Original Price',
            'discount_label' => 'Discount Percentage (%)',
            'final_price' => 'Final Price to Pay',
            'savings' => 'Total Amount Saved',
            'formula' => 'Formula: Final Price = Original Price - ((Discount % ÷ 100) × Original Price)',
        ],
        'how_to_title' => 'How to Use the Calculator',
        'how_to_steps' => [
            'Select the desired calculation mode from the top tabs.',
            'Type your numerical values into the input fields.',
            'The calculated result and formula breakdown will update automatically.',
        ],
        'specs_title' => 'Calculation Specifications',
        'specs' => [
            'Arithmetic Precision' => 'IEEE 754 64-bit double precision floating point',
            'Rounding Standard' => 'Rounded to 2 decimal places for financial readability',
            'Negative Values' => 'Fully supported for percentage decrease and difference metrics',
            'Division by Zero Guard' => 'Safely intercepted with descriptive validation notice',
            'Network Requirement' => 'Zero network calls; computed in browser memory',
        ],
        'examples_title' => 'Step-by-Step Calculation Examples',
        'examples' => [
            [
                'label' => 'Percentage of Number',
                'input' => 'What is 15% of $80?',
                'output' => 'Formula: (15 ÷ 100) × 80 = $12.00',
            ],
            [
                'label' => 'Retail Discount',
                'input' => 'Original price $120 with a 25% discount',
                'output' => 'Savings: $30.00 | Final Price to Pay: $90.00',
            ],
            [
                'label' => 'Percentage Change',
                'input' => 'Initial value $50 increased to $75',
                'output' => 'Difference: +$25.00 | Percentage Change: +50.00% (Increase)',
            ],
        ],
        'faq_title' => 'Frequently Asked Questions',
        'faq' => [
            [
                'q' => 'How is a retail shopping discount calculated?',
                'a' => 'Multiply the original price by the discount percentage divided by 100 to get total savings. Subtract that savings amount from the original price to determine what you pay.',
            ],
            [
                'q' => 'Why does calculating percentage change from zero return an error?',
                'a' => 'Percentage change requires dividing by the initial value. In mathematics, division by zero is undefined, so the calculator warns you rather than producing an incorrect result.',
            ],
            [
                'q' => 'Can this calculator determine percentage decreases?',
                'a' => 'Yes. Whenever the final value is lower than the starting value (such as a drop from 100 to 75), the calculator automatically detects a negative change (-25%) and labels it as a decrease.',
            ],
            [
                'q' => 'Are my numbers or financial estimates stored anywhere?',
                'a' => 'No. All calculations are executed locally inside your device browser without sending any numbers or session history to external servers.',
            ],
        ],
        'related_context' => 'Need to generate scannable links for promotions or price tags? Create free QR codes with our :link, or inspect text metrics with our :link2.',
        'limitations_title' => 'Precision & Safety',
        'limitations_text' => 'Calculations use standard 64-bit IEEE floating-point arithmetic. Division by zero is trapped safely. Financial figures are rounded to 2 decimal places for clarity.',
        'privacy_title' => 'Instant & Private',
        'privacy_text' => 'Calculations are performed instantly in your browser without any network requests.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tool 4: JSON Formatter and Validator
    |--------------------------------------------------------------------------
    */
    'json_formatter' => [
        'name' => 'JSON Formatter & Validator',
        'short_desc' => 'Format, beautify, validate, and minify JSON data with instant syntax error detection and indentation controls.',
        'meta_title' => 'JSON Formatter & Validator — Format JSON Online | ToolZeno',
        'meta_desc' => 'Format, validate, beautify, and minify JSON data directly in your browser. Detect syntax errors and customize tab or space indentation with 100% privacy.',
        'input_label' => 'JSON Input',
        'input_placeholder' => 'Paste raw JSON code here to format and validate...',
        'indent_label' => 'Indentation:',
        'indent_2' => '2 Spaces',
        'indent_4' => '4 Spaces',
        'indent_tab' => 'Tab',
        'status_valid' => 'Valid JSON',
        'status_invalid' => 'Syntax Error',
        'load_sample' => 'Load Sample',
        'how_to_title' => 'How to Format and Validate JSON',
        'how_to_steps' => [
            'Paste your raw JSON text into the editor or click "Load Sample" to test.',
            'Click "Format" to beautify the code using your chosen indentation style.',
            'Click "Minify" to remove all unnecessary whitespace for compact payload transfer.',
            'Check the status badge to confirm validity or locate syntax errors.',
            'Click "Copy" to save the cleaned JSON to your clipboard.',
        ],
        'specs_title' => 'Technical Specifications',
        'specs' => [
            'Parser Standard' => 'RFC 8259 Standard ECMAScript JSON Parser',
            'Indentation Styles' => '2 Spaces, 4 Spaces, or Tab character',
            'Validation Diagnostics' => 'Line and character offset syntax error localization',
            'Execution Security' => 'Sandboxed JSON.parse() (zero JavaScript eval or script execution)',
            'Maximum Tested Payload' => 'Up to 10 MB payload directly in browser memory',
        ],
        'examples_title' => 'Formatting Demonstration',
        'examples' => [
            [
                'label' => 'Raw Minified Input',
                'input' => '{"brand":"ToolZeno","free":true,"tools":8,"locale":["en","bn"]}',
                'output' => "{\n  \"brand\": \"ToolZeno\",\n  \"free\": true,\n  \"tools\": 8,\n  \"locale\": [\n    \"en\",\n    \"bn\"\n  ]\n}",
            ],
        ],
        'faq_title' => 'Frequently Asked Questions',
        'faq' => [
            [
                'q' => 'How does the validator pinpoint syntax mistakes?',
                'a' => 'Our validator parses JSON through the browser\'s native parsing engine, detecting unescaped quotes, trailing commas, missing colons, and unclosed brackets with exact error messages.',
            ],
            [
                'q' => 'Is it safe to format proprietary API responses containing secrets?',
                'a' => 'Yes. Because processing executes 100% client-side in your browser, your payloads, customer tokens, and configuration keys are never transmitted to our servers.',
            ],
            [
                'q' => 'Why does JSON minification matter?',
                'a' => 'Minifying JSON removes all line breaks and indentation spaces, reducing HTTP payload transfer sizes and saving bandwidth in REST and GraphQL API communication.',
            ],
            [
                'q' => 'Does this tool accept comments in JSON code?',
                'a' => 'Strict JSON specification (RFC 8259) does not permit comments. If your payload includes JavaScript-style comments (// or /* */), the parser will flag them as syntax errors as required by JSON standards.',
            ],
        ],
        'related_context' => 'Need to standardize variable or key names across your payloads? Use our :link to switch between camelCase, snake_case, and kebab-case.',
        'security_title' => 'Security & Sandboxing',
        'security_text' => 'This tool parses JSON strictly using standard browser JSON.parse(). It never evaluates, executes, or renders arbitrary JavaScript, ensuring safe inspection of untrusted API payloads.',
        'privacy_title' => 'Local Processing',
        'privacy_text' => 'Your JSON structure and data keys never leave your browser window.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tool 5: QR Code Generator
    |--------------------------------------------------------------------------
    */
    'qr_generator' => [
        'name' => 'QR Code Generator',
        'short_desc' => 'Generate high-resolution QR codes from text, links, or contacts with customizable dimensions and instant download.',
        'meta_title' => 'QR Code Generator — Create QR Codes Online | ToolZeno',
        'meta_desc' => 'Generate high-resolution QR codes from URLs, text, or phone numbers. Select size and error correction, then download PNG or SVG files directly in your browser.',
        'input_label' => 'Text or Website URL',
        'input_placeholder' => 'Enter a website URL (e.g., https://example.com) or any plain text...',
        'size_label' => 'Output Dimensions',
        'size_small' => 'Small (150 × 150 px)',
        'size_medium' => 'Medium (300 × 300 px)',
        'size_large' => 'Large (600 × 600 px)',
        'error_label' => 'Error Correction Level',
        'error_l' => 'Low (7% recovery)',
        'error_m' => 'Medium (15% recovery)',
        'error_q' => 'Quartile (25% recovery)',
        'error_h' => 'High (30% recovery - recommended for print)',
        'download_png' => 'Download PNG',
        'download_svg' => 'Download SVG',
        'testing_warning' => 'Important: Always scan and test your generated QR code with a smartphone camera before commercial printing or physical distribution.',
        'how_to_title' => 'How to Create a QR Code',
        'how_to_steps' => [
            'Type or paste your link or text into the input field.',
            'Select your desired image resolution and error correction level.',
            'Preview the rendered QR code instantly on your screen.',
            'Click "Download PNG" or "Download SVG" to save your image file.',
        ],
        'specs_title' => 'Supported Encodings & Exports',
        'specs' => [
            'Payload Support' => 'Website URLs (https://), plain text, phone numbers (tel:), mailto: links, WiFi configs',
            'Export Formats' => 'Raster PNG (150px to 600px) and Vector SVG (infinitely scalable)',
            'Error Correction Levels' => 'Level L (7%), Level M (15%), Level Q (25%), Level H (30%)',
            'Maximum Alphanumeric Length' => 'Up to 2,953 alphanumeric characters',
            'Code Longevity' => 'Permanent static QR code (no expiration, no middleman redirects)',
        ],
        'examples_title' => 'Common QR Payload Formats',
        'examples' => [
            [
                'label' => 'Website URL',
                'input' => 'https://toolzeno.site/en/tools/qr-generator',
                'output' => 'Opens web browser directly to the specified destination URL.',
            ],
            [
                'label' => 'WiFi Network Access',
                'input' => 'WIFI:T:WPA;S:HomeNetwork;P:MyPassword;;',
                'output' => 'Prompts smartphones to automatically connect to the local WiFi.',
            ],
        ],
        'faq_title' => 'Frequently Asked Questions',
        'faq' => [
            [
                'q' => 'Which error correction level should I select?',
                'a' => 'For screens, websites, and emails, Medium (15%) is ideal. For printed posters, packaging, or business cards subject to wear or imperfect lighting, choose High (30%) for maximum scan reliability.',
            ],
            [
                'q' => 'What is the advantage of downloading SVG instead of PNG?',
                'a' => 'PNG is a fixed raster image ideal for websites and digital sharing. SVG is a vector format that can be scaled up to billboard size in print software without losing any sharpness.',
            ],
            [
                'q' => 'Do these QR codes ever expire or require a subscription?',
                'a' => 'No. All QR codes generated on ToolZeno are 100% static. Your URL or text is baked directly into the black and white pixel matrix, so it works forever without third-party redirection.',
            ],
            [
                'q' => 'Why is testing physical test prints recommended?',
                'a' => 'Different mobile camera lenses have varying focal ranges and contrast thresholds. Always scan a test print under typical room lighting before mass printing.',
            ],
        ],
        'related_context' => 'Need to optimize promotional images or resize graphics for printed QR flyers? Explore our :link and :link2.',
        'privacy_title' => 'Direct Browser Generation',
        'privacy_text' => 'The QR code matrix is calculated and drawn client-side using JavaScript. We do not transmit or store the URLs or messages you encode.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tool 6: Image Compressor
    |--------------------------------------------------------------------------
    */
    'image_compressor' => [
        'name' => 'Image Compressor',
        'short_desc' => 'Reduce JPEG, PNG, and WebP file sizes in your browser with adjustable quality controls and instant side-by-side preview.',
        'meta_title' => 'Image Compressor — Compress Images Online | ToolZeno',
        'meta_desc' => 'Compress JPEG, PNG, and WebP images online directly in your browser. Reduce file size without uploading photos to external servers.',
        'drop_label' => 'Drop your JPEG, PNG, or WebP image here, or click to browse',
        'supported_formats' => 'Supported formats: JPEG, PNG, WebP (Max size: 25 MB)',
        'quality_label' => 'Compression Quality Level',
        'original_label' => 'Original Image',
        'compressed_label' => 'Compressed Image',
        'size_saved' => 'Saved',
        'size_larger' => 'File size did not decrease',
        'download_compressed' => 'Download Compressed Image',
        'how_to_title' => 'How to Compress Images',
        'how_to_steps' => [
            'Drag your image into the upload area or click to select a file from your device.',
            'Adjust the compression quality slider (70% - 80% is ideal for web usage).',
            'Compare the original and processed file sizes and previews.',
            'Click "Download Compressed Image" to retrieve your optimized file.',
        ],
        'specs_title' => 'Image Compression Specifications',
        'specs' => [
            'Supported Formats' => 'JPEG (.jpg, .jpeg), PNG (.png), and WebP (.webp)',
            'Maximum Input Size' => '25 MB per single image file',
            'Compression Method' => 'Lossy Canvas resampling with adjustable quality factor (1% to 100%)',
            'Metadata Handling' => 'Strips unnecessary EXIF camera tags to minimize byte size',
            'Privacy Architecture' => '100% Client-side HTML5 Canvas API (zero server upload)',
        ],
        'examples_title' => 'Compression Benchmark Examples',
        'examples' => [
            [
                'label' => 'Smartphone Photo (JPEG)',
                'input' => '4.8 MB High-Resolution Camera Capture (4032 × 3024 px)',
                'output' => 'At 75% quality: 780 KB (~84% size reduction) with virtually identical visual appearance.',
            ],
            [
                'label' => 'Website Screenshot (PNG)',
                'input' => '2.1 MB PNG Graphic with flat colors',
                'output' => 'At 80% quality: 520 KB (~75% reduction) with sharp typography.',
            ],
        ],
        'faq_title' => 'Frequently Asked Questions',
        'faq' => [
            [
                'q' => 'How does in-browser compression work without uploading files?',
                'a' => 'Your device browser reads the photo directly into memory using FileReader and redraws it onto an off-screen HTML5 Canvas. The canvas re-encodes the image at your selected quality level entirely on your device CPU/GPU.',
            ],
            [
                'q' => 'Why do some PNG images compress less than JPEGs?',
                'a' => 'PNG is a lossless format designed for crisp illustrations and transparency. If a PNG contains photographic colors, re-encoding without format conversion yields modest savings compared to JPEG or WebP.',
            ],
            [
                'q' => 'Will compressing my photo alter its width and height in pixels?',
                'a' => 'No. Compression optimizes encoding tables and removes excess metadata while keeping the original pixel dimensions completely unchanged.',
            ],
            [
                'q' => 'What is the recommended quality setting for blogs and websites?',
                'a' => 'A quality slider setting between 70% and 80% delivers the sweet spot between crisp image fidelity and lightweight file size for rapid page load times.',
            ],
        ],
        'related_context' => 'Need to change the pixel dimensions or crop margins of your photo? Launch our :link, or package multiple photos into a clean document with our :link2.',
        'limitations_title' => 'Compression Behavior & Transparency',
        'limitations_text' => 'Some pre-optimized images or complex PNGs with alpha transparency may not achieve significant reduction without format changes. Canvas re-compression preserves essential visual quality while discarding unneeded metadata.',
        'privacy_title' => 'Zero-Server Uploads',
        'privacy_text' => 'Your personal photos and sensitive images are compressed entirely inside your web browser using HTML5 Canvas APIs. Nothing is uploaded to our servers.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tool 7: Image Resizer
    |--------------------------------------------------------------------------
    */
    'image_resizer' => [
        'name' => 'Image Resizer',
        'short_desc' => 'Resize photos to exact pixel dimensions or percentage scales while maintaining aspect ratio and choosing output format.',
        'meta_title' => 'Image Resizer — Resize Images Online | ToolZeno',
        'meta_desc' => 'Resize image dimensions by pixels or percentage scale while locking aspect ratio. Export to JPEG, PNG, or WebP locally in your browser.',
        'drop_label' => 'Drop your image here or click to choose from your device',
        'width_label' => 'Width (pixels)',
        'height_label' => 'Height (pixels)',
        'lock_aspect' => 'Lock Aspect Ratio',
        'scale_presets' => 'Quick Percentage Scales:',
        'format_label' => 'Output Format:',
        'format_original' => 'Original Format',
        'format_jpeg' => 'JPEG',
        'format_png' => 'PNG',
        'format_webp' => 'WebP',
        'download_resized' => 'Download Resized Image',
        'how_to_title' => 'How to Resize an Image',
        'how_to_steps' => [
            'Select an image from your computer or phone.',
            'Type the target width or height (or click a quick preset like 50%).',
            'Keep "Lock Aspect Ratio" checked to prevent image distortion.',
            'Pick your preferred output file format and click "Download Resized Image".',
        ],
        'specs_title' => 'Resizing Specifications',
        'specs' => [
            'Supported Formats' => 'JPEG (.jpg, .jpeg), PNG (.png), and WebP (.webp)',
            'Dimension Boundaries' => '1 × 1 px minimum up to 8,192 × 8,192 px maximum (device memory guard)',
            'Aspect Ratio Constraints' => 'Dynamic bidirectional proportional locking',
            'Preset Percentage Scales' => '25%, 50%, and 75% quick reduction buttons',
            'Output Conversion' => 'Convert format simultaneously during resizing (JPEG, PNG, WebP)',
        ],
        'examples_title' => 'Common Resizing Use Cases',
        'examples' => [
            [
                'label' => 'Social Media Open Graph Banner',
                'input' => 'Original 4000 × 3000 px photo resized to 1200 × 630 px',
                'output' => 'Perfect dimensions for Facebook, LinkedIn, and Twitter card previews.',
            ],
            [
                'label' => 'Passport / ID Application Photo',
                'input' => 'Cropped square portrait resized to 600 × 600 px',
                'output' => 'Standard specification for online visa and identity portals.',
            ],
        ],
        'faq_title' => 'Frequently Asked Questions',
        'faq' => [
            [
                'q' => 'What does "Lock Aspect Ratio" do?',
                'a' => 'Locking aspect ratio ensures that whenever you change the width, the height calculates automatically (and vice versa), preventing your picture from stretching or looking distorted.',
            ],
            [
                'q' => 'Can I enlarge a small photo without losing quality?',
                'a' => 'Upscaling an image beyond its native resolution cannot restore missing optical detail; pixels will look softer. For best results, downscale or keep dimensions close to original size.',
            ],
            [
                'q' => 'Can I convert an image format while resizing?',
                'a' => 'Yes. You can select WebP, JPEG, or PNG as your export format, allowing you to resize dimensions and convert formats in a single step.',
            ],
            [
                'q' => 'Is my private photo uploaded to any cloud server?',
                'a' => 'Never. All resampling calculations take place on your local device graphics pipeline, ensuring medical records, IDs, and family photos remain 100% confidential.',
            ],
        ],
        'related_context' => 'After setting your image dimensions, reduce its byte size with our :link, or assemble multiple photos into a PDF with our :link2.',
        'limitations_title' => 'Resolution Limits & Quality Advice',
        'limitations_text' => 'Upscaling an image beyond its original resolution will not restore missing detail or improve optical sharpness. For device memory safety, maximum dimensions are capped at 8,192 × 8,192 pixels.',
        'privacy_title' => 'Private & Device-Local',
        'privacy_text' => 'Image rendering and resampling are processed locally on your device hardware without external transmission.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tool 8: Image to PDF Converter
    |--------------------------------------------------------------------------
    */
    'image_to_pdf' => [
        'name' => 'Image to PDF Converter',
        'short_desc' => 'Combine multiple JPEG, PNG, and WebP images into a single clean PDF document with custom page sizes and orientations.',
        'meta_title' => 'Image to PDF Converter — Convert Images to PDF | ToolZeno',
        'meta_desc' => 'Combine multiple JPG, PNG, and WebP images into a single clean PDF document. Reorder pages and customize layout directly in your browser with zero server uploads.',
        'drop_label' => 'Drop multiple images here or click to browse',
        'reorder_hint' => 'Drag or use arrows to reorder pages. Remove unwanted images before generating.',
        'page_size' => 'Page Size',
        'size_a4' => 'A4 (210 × 297 mm)',
        'size_letter' => 'US Letter (8.5 × 11 in)',
        'size_fit' => 'Fit to Image Dimensions',
        'orientation' => 'Orientation',
        'orient_portrait' => 'Portrait',
        'orient_landscape' => 'Landscape',
        'margin_label' => 'Page Margins',
        'margin_none' => 'None (Full Bleed)',
        'margin_small' => 'Small (10 mm)',
        'margin_normal' => 'Standard (20 mm)',
        'btn_generate' => 'Generate PDF Document',
        'btn_download' => 'Download PDF',
        'generating_status' => 'Compiling PDF pages...',
        'no_images' => 'No images selected yet. Add one or more images above.',
        'move_up' => 'Move Up',
        'move_down' => 'Move Down',
        'remove' => 'Remove',
        'how_to_title' => 'How to Convert Images to PDF',
        'how_to_steps' => [
            'Click or drag multiple image files into the selection zone.',
            'Use the Up and Down buttons to adjust page order as needed.',
            'Select your desired page dimensions, orientation, and margin spacing.',
            'Click "Generate PDF Document" and download your newly compiled file.',
        ],
        'specs_title' => 'PDF Compilation Specifications',
        'specs' => [
            'Supported Image Formats' => 'JPEG (.jpg, .jpeg), PNG (.png), and WebP (.webp)',
            'Page Layout Standards' => 'ISO A4 (210 × 297 mm), US Letter (8.5 × 11 in), or Fit to Image',
            'Page Orientations' => 'Portrait and Landscape modes',
            'Margin Options' => 'Full Bleed (0 mm), Small (10 mm), and Standard (20 mm)',
            'Batch Processing Limit' => 'Up to 30 images per compilation pass (for mobile browser RAM stability)',
            'Confidentiality Guard' => 'Zero server uploads; compiled completely in browser sandbox',
        ],
        'examples_title' => 'Typical Document Workflows',
        'examples' => [
            [
                'label' => 'Document / Homework Submission',
                'input' => '4 scanned pages of assignments in JPG format',
                'output' => 'Compiled into a single structured A4 Portrait PDF file ready for email or school portal upload.',
            ],
            [
                'label' => 'Expense Receipts & Invoices',
                'input' => 'Photos of 3 store receipts with various dimensions',
                'output' => 'Unified into a single Letter document with neat 10mm margins for accounting records.',
            ],
        ],
        'faq_title' => 'Frequently Asked Questions',
        'faq' => [
            [
                'q' => 'How do I rearrange the order of pages before downloading?',
                'a' => 'Each uploaded image card features "Move Up" and "Move Down" buttons in the preview grid, allowing you to sequence pages in your exact preferred order.',
            ],
            [
                'q' => 'What does the "Fit to Image" page size setting do?',
                'a' => 'Rather than stretching photos onto fixed A4 or Letter page dimensions, "Fit to Image" matches each page\'s dimensions to the exact aspect ratio of the underlying photo, eliminating blank margins.',
            ],
            [
                'q' => 'Can I convert PNG images with transparent backgrounds?',
                'a' => 'Yes. Transparent PNG areas are automatically rendered onto a crisp white background during PDF synthesis to ensure clean printing and reading.',
            ],
            [
                'q' => 'Are my scanned IDs or financial receipts uploaded to your servers?',
                'a' => 'No. All PDF generation and byte synthesis happen entirely within your local browser memory using JavaScript. Your sensitive documents never travel across the web.',
            ],
        ],
        'related_context' => 'Need to optimize your photos before compiling them into a PDF? Compress them first with our :link or adjust dimensions with our :link2.',
        'limitations_title' => 'Memory & Page Count Guidelines',
        'limitations_text' => 'Browser-side generation processes each page in memory. For optimal stability on mobile devices, compiling batches of up to 30 high-resolution images at a time is recommended.',
        'privacy_title' => 'Strict Document Confidentiality',
        'privacy_text' => 'Your scanned IDs, receipts, and photos are never uploaded to any cloud server. PDF generation occurs entirely within your browser sandbox.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Developer Tools: Group 1 (Code Formatters & Beautifiers)
    |--------------------------------------------------------------------------
    */
    'html_css_js_beautifier' => [
        'name' => 'HTML/CSS/JS Beautifier',
        'short_desc' => 'Format, indent, and prettify messy HTML, CSS, and JavaScript code instantly in your browser.',
        'meta_title' => 'HTML/CSS/JS Beautifier — Format Frontend Code Online | ToolZeno',
        'meta_desc' => 'Clean up messy HTML, CSS, and JavaScript code with customizable indentation. Fast, secure, and 100% in-browser.',
    ],
    'sql_formatter' => [
        'name' => 'SQL Formatter',
        'short_desc' => 'Format, indent, and uppercase keywords in complex SQL queries for maximum readability.',
        'meta_title' => 'SQL Formatter — Format and Beautify SQL Queries | ToolZeno',
        'meta_desc' => 'Prettify SQL queries online with proper indentation and capitalized keywords. Supports MySQL, PostgreSQL, SQLite, and standard SQL.',
    ],
    'xml_formatter' => [
        'name' => 'XML Formatter & Beautifier',
        'short_desc' => 'Format and indent raw XML strings with syntax validation and proper tag hierarchy.',
        'meta_title' => 'XML Formatter — Indent & Beautify XML Code Online | ToolZeno',
        'meta_desc' => 'Format XML online with customizable indentation and instant syntax error detection. 100% private in-browser tool.',
    ],
    'yaml_json_converter' => [
        'name' => 'YAML to JSON / JSON to YAML Converter',
        'short_desc' => 'Seamlessly convert between YAML and JSON configuration formats with syntax validation.',
        'meta_title' => 'YAML to JSON Converter — Bidirectional Config Converter | ToolZeno',
        'meta_desc' => 'Convert YAML to JSON and JSON to YAML in real time. Validate syntax and format config files instantly.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Developer Tools: Group 2 (Minifiers & Optimizers)
    |--------------------------------------------------------------------------
    */
    'css_minifier' => [
        'name' => 'CSS Minifier',
        'short_desc' => 'Minify and compress CSS stylesheets by removing comments and whitespace to accelerate web performance.',
        'meta_title' => 'CSS Minifier — Compress CSS Online | ToolZeno',
        'meta_desc' => 'Compress and minify CSS code online. Remove comments and spaces to optimize page load speeds.',
    ],
    'js_minifier' => [
        'name' => 'JavaScript Minifier',
        'short_desc' => 'Compress JavaScript code by removing comments, blank lines, and unnecessary spaces.',
        'meta_title' => 'JavaScript Minifier — Minify JS Code Online | ToolZeno',
        'meta_desc' => 'Minify JavaScript code online to reduce bundle size and optimize web application performance.',
    ],
    'html_minifier' => [
        'name' => 'HTML Minifier',
        'short_desc' => 'Minify HTML code by stripping comments and redundant whitespace for faster webpage rendering.',
        'meta_title' => 'HTML Minifier — Compress HTML Code Online | ToolZeno',
        'meta_desc' => 'Minify HTML markup online to reduce page size and improve site loading speeds.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Developer Tools: Group 3 (Converters & Encoders/Decoders)
    |--------------------------------------------------------------------------
    */
    'base64_encoder_decoder' => [
        'name' => 'Base64 Encoder/Decoder',
        'short_desc' => 'Encode and decode UTF-8 text or convert image files to Base64 data URIs securely in your browser.',
        'meta_title' => 'Base64 Encoder and Decoder — Text & Image to Base64 | ToolZeno',
        'meta_desc' => 'Encode and decode Base64 strings with full UTF-8 support. Convert images to Base64 data URIs in your browser.',
    ],
    'url_encoder_decoder' => [
        'name' => 'URL Encoder/Decoder',
        'short_desc' => 'Encode and decode URLs, query strings, and URI components with full Unicode support.',
        'meta_title' => 'URL Encoder/Decoder — Encode & Decode URIs Online | ToolZeno',
        'meta_desc' => 'Encode and decode URLs, query parameters, and special characters online with full UTF-8 Unicode support.',
    ],
    'html_entities_encoder' => [
        'name' => 'HTML Entities Encoder/Decoder',
        'short_desc' => 'Convert special characters to HTML entities and decode escaped HTML back to plain text.',
        'meta_title' => 'HTML Entities Encoder & Decoder Online | ToolZeno',
        'meta_desc' => 'Encode text to safe HTML entities or decode HTML entities back to raw text. Protect against XSS and display symbols.',
    ],
    'jwt_decoder' => [
        'name' => 'JWT Decoder & Inspector',
        'short_desc' => 'Decode, inspect, and verify JSON Web Token headers, payloads, and expiration times safely.',
        'meta_title' => 'JWT Decoder — Inspect JSON Web Tokens Online | ToolZeno',
        'meta_desc' => 'Decode and inspect JWT headers, claims, and timestamps in your browser without sending secrets to any server.',
    ],
    'hex_binary_text_converter' => [
        'name' => 'Hex to ASCII / Binary to Text Converter',
        'short_desc' => 'Convert between plain text, hexadecimal, and binary number systems seamlessly.',
        'meta_title' => 'Hex & Binary to Text Converter Online | ToolZeno',
        'meta_desc' => 'Convert text to Hex, Hex to ASCII, text to binary, and binary to text. Real-time bidirectional converter.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Developer Tools: Group 4 (Testing & Regex)
    |--------------------------------------------------------------------------
    */
    'regex_tester' => [
        'name' => 'Regex Tester & Debugger',
        'short_desc' => 'Test and debug regular expressions in real-time with match highlighting and capture group extraction.',
        'meta_title' => 'Regex Tester & Debugger — Test Regular Expressions Online | ToolZeno',
        'meta_desc' => 'Test JavaScript regular expressions in real time. Visual match highlights, capture group inspection, and cheat sheet.',
    ],
    'cron_generator' => [
        'name' => 'Crontab (Cron Expression) Generator',
        'short_desc' => 'Visually create, customize, and understand cron job schedule expressions with plain-English explanations.',
        'meta_title' => 'Cron Expression Generator — Build Crontab Schedules Online | ToolZeno',
        'meta_desc' => 'Generate and validate cron schedule expressions with an interactive builder. Includes common presets and syntax explanations.',
    ],
    'chmod_calculator' => [
        'name' => 'Chmod Permissions Calculator',
        'short_desc' => 'Calculate Linux/Unix file permissions with visual checkboxes, octal values (755, 644), and symbolic modes.',
        'meta_title' => 'Chmod Permissions Calculator — Linux File Permissions | ToolZeno',
        'meta_desc' => 'Calculate Linux chmod permissions interactively. Get numeric octal (e.g. 755), symbolic (-rwxr-xr-x), and terminal commands.',
    ],
    'curl_converter' => [
        'name' => 'cURL to Fetch / PHP / Axios / Python Converter',
        'short_desc' => 'Convert cURL terminal commands into modern JavaScript fetch, Axios, PHP cURL, and Python requests code.',
        'meta_title' => 'cURL Converter — Convert cURL to Fetch, Axios, PHP, Python | ToolZeno',
        'meta_desc' => 'Convert cURL requests into JavaScript fetch, Axios, PHP, and Python code instantly in your browser.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Developer Tools: Group 5 (ID & Data Generators)
    |--------------------------------------------------------------------------
    */
    'uuid_generator' => [
        'name' => 'UUID / GUID Generator',
        'short_desc' => 'Generate cryptographically random UUID v4 identifiers individually or in bulk with custom formatting.',
        'meta_title' => 'UUID Generator — Generate Bulk v4 UUIDs / GUIDs Online | ToolZeno',
        'meta_desc' => 'Generate random v4 UUIDs (GUIDs) using Web Crypto API. Support for bulk generation, uppercase, and hyphen options.',
    ],
    'nanoid_generator' => [
        'name' => 'NanoID Generator',
        'short_desc' => 'Generate cryptographically secure, URL-friendly unique IDs with custom lengths and alphabets.',
        'meta_title' => 'NanoID Generator — Secure URL-Friendly Unique IDs | ToolZeno',
        'meta_desc' => 'Generate secure NanoID strings with customizable length and character sets using client-side Web Crypto.',
    ],
    'dummy_json_generator' => [
        'name' => 'Dummy / Mock JSON Generator',
        'short_desc' => 'Generate mock JSON test data with realistic user profiles, emails, addresses, and timestamps for API testing.',
        'meta_title' => 'Dummy JSON Generator — Mock Data for API Testing | ToolZeno',
        'meta_desc' => 'Generate realistic dummy JSON data for frontend prototyping and backend testing. Fast and customizable.',
    ],
    'bcrypt_hash_generator' => [
        'name' => 'Bcrypt & Cryptographic Hash Generator',
        'short_desc' => 'Generate SHA-256, SHA-512, SHA-1, MD5, and Bcrypt cryptographic hashes directly in your browser.',
        'meta_title' => 'Hash & Bcrypt Generator — SHA256, SHA512, MD5 Online | ToolZeno',
        'meta_desc' => 'Generate cryptographic hashes (SHA-256, SHA-512, SHA-1, MD5) and bcrypt strings client-side with Web Crypto.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Developer Tools: Group 6 (Diff & Inspection)
    |--------------------------------------------------------------------------
    */
    'code_diff_checker' => [
        'name' => 'Code Diff Checker',
        'short_desc' => 'Compare two snippets of code or text to highlight added, removed, and modified lines side-by-side.',
        'meta_title' => 'Code Diff Checker — Compare Text and Code Online | ToolZeno',
        'meta_desc' => 'Compare two text or code files online. Visual line-by-line diff with added, removed, and changed highlights.',
    ],
    'string_escape_unescape' => [
        'name' => 'String Escape / Unescape Tool',
        'short_desc' => 'Escape and unescape special characters, quotes, and backslashes for JSON, JavaScript, SQL, and HTML.',
        'meta_title' => 'String Escape & Unescape Tool Online | ToolZeno',
        'meta_desc' => 'Escape or unescape string literals for JSON, JavaScript, PHP, Python, and SQL strings in your browser.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Developer Tools: Additional Tools (Data Models, APIs, DevOps, Converters)
    |--------------------------------------------------------------------------
    */
    'json_to_kotlin' => [
        'name' => 'JSON to Kotlin Data Class Generator',
        'short_desc' => 'Convert raw JSON payloads into clean, idiomatic Kotlin data classes with null-safety and serialization annotations.',
        'meta_title' => 'JSON to Kotlin Data Class Generator Online | ToolZeno',
        'meta_desc' => 'Convert JSON into type-safe Kotlin data classes for Android and multiplatform applications instantly.',
    ],
    'json_to_java_pojo' => [
        'name' => 'JSON to Java POJO Class Generator',
        'short_desc' => 'Generate Java POJO classes with getters, setters, constructors, and Jackson/Gson annotations from JSON.',
        'meta_title' => 'JSON to Java POJO Generator Online | ToolZeno',
        'meta_desc' => 'Convert JSON into clean Java POJO classes with Lombok or standard getters/setters in your browser.',
    ],
    'json_to_dart' => [
        'name' => 'JSON to Dart/Flutter Model Generator',
        'short_desc' => 'Convert JSON objects into Flutter Dart model classes with fromJson and toJson serialization methods.',
        'meta_title' => 'JSON to Dart Model Generator for Flutter | ToolZeno',
        'meta_desc' => 'Generate Dart model classes with fromJson and toJson methods from JSON for Flutter applications.',
    ],
    'json_to_typescript' => [
        'name' => 'JSON to TypeScript Interface Generator',
        'short_desc' => 'Generate type-safe TypeScript interfaces and types from JSON objects and arrays in real time.',
        'meta_title' => 'JSON to TypeScript Interface & Type Generator | ToolZeno',
        'meta_desc' => 'Convert JSON into clean TypeScript interfaces with nested types and optional fields support.',
    ],
    'http_status_lookup' => [
        'name' => 'HTTP Status Code Lookup',
        'short_desc' => 'Quickly search and reference all HTTP status codes (1xx, 2xx, 3xx, 4xx, 5xx) with RFC specifications and causes.',
        'meta_title' => 'HTTP Status Code Lookup & Reference Guide | ToolZeno',
        'meta_desc' => 'Comprehensive HTTP status code directory with explanations, RFC standards, and developer troubleshooting tips.',
    ],
    'query_string_json_converter' => [
        'name' => 'Query String to JSON Converter',
        'short_desc' => 'Parse URL query parameter strings into structured JSON objects and convert JSON back into encoded query strings.',
        'meta_title' => 'Query String to JSON & URL Params Converter | ToolZeno',
        'meta_desc' => 'Bi-directional converter between URL query strings and JSON objects with nested key handling.',
    ],
    'api_payload_tester' => [
        'name' => 'Webhook & API Payload Tester UI',
        'short_desc' => 'Construct, test, and send mock HTTP REST requests (GET, POST, PUT, DELETE) with headers and JSON payloads directly in-browser.',
        'meta_title' => 'API Payload Tester & In-Browser REST Client | ToolZeno',
        'meta_desc' => 'Test API endpoints, webhooks, and HTTP requests with customizable headers, body, and status inspection.',
    ],
    'url_path_normalizer' => [
        'name' => 'URL & Path Normalizer',
        'short_desc' => 'Normalize and sanitize messy URLs by resolving relative dots, trailing slashes, duplicate separators, and sort query parameters.',
        'meta_title' => 'URL & Path Normalizer — Clean & Standardize URLs | ToolZeno',
        'meta_desc' => 'Normalize URLs, remove tracking parameters, decode safe characters, and generate canonical clean URLs.',
    ],
    'htaccess_generator' => [
        'name' => '.htaccess Generator & Tester',
        'short_desc' => 'Generate production-ready Apache .htaccess rules for 301 redirects, HTTPS enforcement, Gzip compression, and security headers.',
        'meta_title' => '.htaccess Generator — Apache Redirects & Security | ToolZeno',
        'meta_desc' => 'Visual .htaccess file generator for SSL redirects, 301 redirects, caching, custom error pages, and hotlink protection.',
    ],
    'nginx_config_generator' => [
        'name' => 'Nginx Config Generator',
        'short_desc' => 'Generate optimized Nginx server blocks for PHP-FPM, reverse proxies, static SPAs, SSL certificates, and gzip compression.',
        'meta_title' => 'Nginx Server Block Config Generator | ToolZeno',
        'meta_desc' => 'Generate clean Nginx configuration files for Laravel, Node.js, reverse proxy, and SSL websites.',
    ],
    'gitignore_generator' => [
        'name' => '.gitignore Generator',
        'short_desc' => 'Generate customized, curated .gitignore templates for Laravel, Node.js, Python, Flutter, Go, and IDE environments.',
        'meta_title' => '.gitignore Generator for Popular Frameworks & OS | ToolZeno',
        'meta_desc' => 'Create comprehensive .gitignore files combining operating systems, IDEs, and languages with one click.',
    ],
    'hmac_generator' => [
        'name' => 'HMAC Generator (SHA-256 / SHA-512)',
        'short_desc' => 'Generate Hash-based Message Authentication Codes (HMAC) using cryptographic secret keys and SHA algorithms for API signature verification.',
        'meta_title' => 'HMAC Generator — SHA256 & SHA512 API Signatures | ToolZeno',
        'meta_desc' => 'Calculate HMAC signatures online with SHA-256, SHA-384, and SHA-512 using Web Crypto API.',
    ],
    'xml_json_converter' => [
        'name' => 'XML to JSON & JSON to XML Converter',
        'short_desc' => 'Bi-directionally convert XML documents to structured JSON and JSON data into valid, properly indented XML tags.',
        'meta_title' => 'XML to JSON / JSON to XML Converter Online | ToolZeno',
        'meta_desc' => 'Convert XML to JSON and JSON to XML instantly with attribute parsing and clean indentation.',
    ],
    'csv_to_sql' => [
        'name' => 'CSV to SQL Insert Query Generator',
        'short_desc' => 'Convert raw CSV or TSV tabular data into batch SQL INSERT statements with custom table names and escaped values.',
        'meta_title' => 'CSV to SQL Generator — Convert CSV to INSERT Statements | ToolZeno',
        'meta_desc' => 'Transform CSV spreadsheets into SQL INSERT queries for MySQL, PostgreSQL, SQLite, and SQL Server.',
    ],
    'markdown_table_generator' => [
        'name' => 'Markdown Table Generator',
        'short_desc' => 'Interactive visual spreadsheet editor to design, format, and generate GitHub Flavored Markdown (GFM) tables.',
        'meta_title' => 'Markdown Table Generator — Visual Editor for GitHub MD | ToolZeno',
        'meta_desc' => 'Create, edit, and export GitHub-flavored Markdown tables with column alignments and easy cell editing.',
    ],
    'html_table_converter' => [
        'name' => 'HTML Table to JSON/CSV Converter',
        'short_desc' => 'Extract tabular data from raw HTML <table> markup and convert it cleanly into structured JSON arrays or CSV files.',
        'meta_title' => 'HTML Table to JSON & CSV Converter | ToolZeno',
        'meta_desc' => 'Paste any HTML table to parse rows and headers into clean JSON or exportable CSV spreadsheets.',
    ],
    'svg_path_visualizer' => [
        'name' => 'SVG Path Visualizer & Optimizer',
        'short_desc' => 'Preview, inspect, scale, and visualize raw SVG path (d attribute) commands with interactive canvas rendering.',
        'meta_title' => 'SVG Path Visualizer & Inspector Online | ToolZeno',
        'meta_desc' => 'Visualize and debug SVG path "d" strings with real-time vector rendering, scale controls, and coordinate inspection.',
    ],
    'jpg_compressor' => [
        'name' => 'JPG / JPEG Compressor',
        'short_desc' => 'Compress JPG and JPEG photos directly in your browser. Reduce file size up to 85% while preserving visual clarity.',
        'meta_title' => 'JPG Compressor — Reduce JPG Image Size Online Free | ToolZeno',
        'meta_desc' => 'Compress JPG and JPEG images online for free. Adjust quality, compare before/after size, and download instantly without server uploads.',
    ],
    'png_compressor' => [
        'name' => 'PNG Compressor (Lossless & Lossy)',
        'short_desc' => 'Optimize and compress PNG images while keeping transparency intact. Reduce bandwidth and boost website speed.',
        'meta_title' => 'PNG Compressor — Compress Transparent PNG Online | ToolZeno',
        'meta_desc' => 'Compress PNG files with transparency preservation. Clean color quantization and lossless size reduction in browser.',
    ],
    'webp_compressor' => [
        'name' => 'WebP Compressor',
        'short_desc' => 'Compress modern Google WebP images with adjustable quality controls. Maximum compression for blazing-fast web pages.',
        'meta_title' => 'WebP Compressor — Reduce WebP Image Size Online | ToolZeno',
        'meta_desc' => 'Super-fast WebP compression tool. Fine-tune image quality, preview results in real time, and save bandwidth.',
    ],
    'bulk_image_compressor' => [
        'name' => 'Bulk Image Compressor',
        'short_desc' => 'Compress multiple photos at once. Process entire batches of JPG, PNG, and WebP files with single-click bulk download.',
        'meta_title' => 'Bulk Image Compressor — Batch Compress Multiple Photos | ToolZeno',
        'meta_desc' => 'Compress multiple images simultaneously in your browser. Batch optimize photos, compare total savings, and download all.',
    ],
    'svg_minifier' => [
        'name' => 'SVG Minifier & Optimizer',
        'short_desc' => 'Clean and minify SVG vector graphics by removing redundant metadata, comments, editor namespaces, and whitespace.',
        'meta_title' => 'SVG Minifier — Optimize & Shrink Vector SVG Files | ToolZeno',
        'meta_desc' => 'Minify SVG code online. Clean unnecessary tags, reduce coordinate precision, and preview vector graphics in real-time.',
    ],
    'png_to_jpg' => [
        'name' => 'PNG to JPG Converter',
        'short_desc' => 'Convert transparent or opaque PNG images to standard high-quality JPG format with custom background color fill.',
        'meta_title' => 'PNG to JPG Converter — Convert PNG to JPEG Online | ToolZeno',
        'meta_desc' => 'Convert PNG images to JPG format for free. Handle transparency with custom background colors and quality sliders.',
    ],
    'jpg_to_png' => [
        'name' => 'JPG to PNG Converter',
        'short_desc' => 'Convert JPG or JPEG photographs to lossless PNG format with crisp pixel preservation.',
        'meta_title' => 'JPG to PNG Converter — Convert JPEG to Lossless PNG | ToolZeno',
        'meta_desc' => 'Convert JPG and JPEG images to PNG format instantly in browser. Fast, private, and 100% free.',
    ],
    'image_to_webp' => [
        'name' => 'Image to WebP Converter',
        'short_desc' => 'Convert any image (JPG, PNG, GIF, BMP) to Google WebP format for superior web compression and faster page loads.',
        'meta_title' => 'Image to WebP Converter — Convert Any Photo to WebP | ToolZeno',
        'meta_desc' => 'Convert PNG, JPG, and BMP images into lightweight WebP format with custom compression quality settings.',
    ],
    'webp_to_jpg_png' => [
        'name' => 'WebP to PNG / JPG Converter',
        'short_desc' => 'Convert WebP images back into universally compatible PNG or JPG formats for editing and legacy application support.',
        'meta_title' => 'WebP to JPG / PNG Converter — Decode WebP Images | ToolZeno',
        'meta_desc' => 'Convert WebP files to PNG or JPG online. Solve compatibility issues with one click in your web browser.',
    ],
    'heic_to_jpg' => [
        'name' => 'HEIC / HEIF to JPG Converter',
        'short_desc' => 'Convert Apple iPhone HEIC and HEIF photos to universally viewable JPG and PNG images right in your browser.',
        'meta_title' => 'HEIC to JPG Converter — Convert iPhone Photos Free | ToolZeno',
        'meta_desc' => 'Convert Apple HEIC photos to JPG or PNG directly in browser. No file size limits and completely private.',
    ],
    'svg_to_png' => [
        'name' => 'SVG to PNG Converter',
        'short_desc' => 'Render SVG vector files into high-resolution transparent PNG images with customizable scale (1x, 2x, 4x, 8x).',
        'meta_title' => 'SVG to PNG Converter — Export Crisp Vector to Raster | ToolZeno',
        'meta_desc' => 'Convert SVG to high-res PNG online. Scale vectors up to 8x with crisp edge rendering and transparent background.',
    ],
    'png_to_svg' => [
        'name' => 'PNG to SVG Vectorizer',
        'short_desc' => 'Trace bitmap PNG and JPG images into scalable vector SVG graphics with edge detection and color thresholding.',
        'meta_title' => 'PNG to SVG Converter — Trace Images to Vector SVG | ToolZeno',
        'meta_desc' => 'Convert PNG and JPG images to scalable SVG vectors. Trace contours, adjust threshold, and download clean SVG.',
    ],
    'image_to_ico' => [
        'name' => 'Image to ICO Converter',
        'short_desc' => 'Create Windows icon (.ico) files from any photo or logo with multi-size resolutions (16x16, 32x32, 48x48, 64x64, 128x128).',
        'meta_title' => 'Image to ICO Converter — Create Windows .ico Icons | ToolZeno',
        'meta_desc' => 'Convert PNG and JPG images to genuine .ico icon format for Windows apps and website favicons.',
    ],
    'bmp_to_jpg_png' => [
        'name' => 'BMP to JPG / PNG Converter',
        'short_desc' => 'Convert heavy Windows Bitmap (BMP) images into lightweight, modern JPG or PNG formats with zero quality loss.',
        'meta_title' => 'BMP to JPG / PNG Converter — Convert Bitmap Online | ToolZeno',
        'meta_desc' => 'Convert legacy BMP bitmap files into compressed JPG or transparent PNG images right in your browser.',
    ],
    'tiff_to_jpg_png' => [
        'name' => 'TIFF to JPG / PNG Converter',
        'short_desc' => 'Convert high-resolution TIFF and TIF print files to web-friendly JPG and PNG formats with instant preview.',
        'meta_title' => 'TIFF to JPG / PNG Converter — Convert TIF Images Online | ToolZeno',
        'meta_desc' => 'Convert scanner and DSLR camera TIFF/TIF files to JPG or PNG format quickly and privately in your browser.',
    ],
    'gif_to_mp4' => [
        'name' => 'GIF to MP4 / Video Converter',
        'short_desc' => 'Convert heavy animated GIFs into smooth, lightweight MP4 and WebM videos with up to 90% size reduction.',
        'meta_title' => 'GIF to MP4 Converter — Convert Animated GIF to Video | ToolZeno',
        'meta_desc' => 'Convert animated GIFs to MP4 and WebM video format for smoother playback, smaller sizes, and video player support.',
    ],
    'video_to_gif' => [
        'name' => 'Video to GIF Converter',
        'short_desc' => 'Convert short MP4 and WebM video clips into shareable animated GIFs with custom FPS, speed, and resolution.',
        'meta_title' => 'Video to GIF Converter — Create Animated GIFs from Video | ToolZeno',
        'meta_desc' => 'Turn any video clip into an animated GIF. Set start/end timestamps, adjust frame rate, and download your GIF.',
    ],
    'gif_to_webp' => [
        'name' => 'GIF to WebP Converter',
        'short_desc' => 'Convert bulky animated GIFs into ultra-compact animated WebP files with crystal clear colors and tiny file sizes.',
        'meta_title' => 'GIF to WebP Converter — Animated GIF to Modern WebP | ToolZeno',
        'meta_desc' => 'Convert animated GIFs to modern animated WebP format. Reduce bandwidth by up to 70% with identical animation quality.',
    ],
    'pixel_image_resizer' => [
        'name' => 'Pixel Image Resizer',
        'short_desc' => 'Resize images to exact pixel dimensions (width x height) with aspect ratio lock and high-quality resampling.',
        'meta_title' => 'Pixel Image Resizer — Resize Photos to Exact Pixels | ToolZeno',
        'meta_desc' => 'Set exact image width and height in pixels. Lock aspect ratio, select resampling mode, and download resized image.',
    ],
    'percentage_image_resizer' => [
        'name' => 'Percentage Image Resizer',
        'short_desc' => 'Scale images proportionally by percentage (25%, 50%, 75%, 150%, 200%) with live dimension and size preview.',
        'meta_title' => 'Percentage Image Resizer — Scale Photos by Percent | ToolZeno',
        'meta_desc' => 'Scale any photo up or down by percentage. Quick presets for 50%, 75%, 200% with real-time file size estimation.',
    ],
    'image_cropper' => [
        'name' => 'Aspect Ratio Image Cropper',
        'short_desc' => 'Crop photos with precision presets (1:1 Square, 4:3, 16:9 Cinema, 9:16 Story, Freeform) and live drag handles.',
        'meta_title' => 'Image Cropper — Crop Photos to Exact Aspect Ratios | ToolZeno',
        'meta_desc' => 'Crop images online with preset aspect ratios. Interactive visual crop box with smooth canvas export.',
    ],
    'circle_image_cropper' => [
        'name' => 'Circle & Round Corner Image Cropper',
        'short_desc' => 'Crop photos into circular profile pictures or rounded-corner avatars with transparent backgrounds.',
        'meta_title' => 'Circle Image Cropper — Round Profile Picture Maker | ToolZeno',
        'meta_desc' => 'Crop photos into perfect circles or smooth rounded rectangles. Ideal for avatars, DP, and UI icons.',
    ],
    'image_rotate_flip' => [
        'name' => 'Image Rotate & Flip Tool',
        'short_desc' => 'Rotate photos clockwise or counter-clockwise, flip horizontally or vertically, or set arbitrary rotation angles.',
        'meta_title' => 'Image Rotate & Flip — Rotate 90° & Mirror Photos Online | ToolZeno',
        'meta_desc' => 'Rotate images by 90°, 180°, 270° or arbitrary angles. Flip horizontal and vertical with real-time canvas rendering.',
    ],
    'image_canvas_padder' => [
        'name' => 'Image Canvas Extender & Padder',
        'short_desc' => 'Add custom borders, uniform margins, or colored padding around photos to match exact target dimensions.',
        'meta_title' => 'Image Canvas Padder — Add Margins & Borders to Photos | ToolZeno',
        'meta_desc' => 'Extend canvas area and add solid color borders or padding around any image without distorting original content.',
    ],
    'app_icon_generator' => [
        'name' => 'App Icon Generator (iOS & Android)',
        'short_desc' => 'Generate complete icon asset packs for iOS (iPhone, iPad, App Store) and Android (mdpi to xxxhdpi) in one click.',
        'meta_title' => 'App Icon Generator — iOS & Android App Icon Pack | ToolZeno',
        'meta_desc' => 'Generate all standard app icon resolutions for iOS and Android. Instant preview and individual or bulk export.',
    ],
    'favicon_generator' => [
        'name' => 'Favicon Generator & HTML Meta Tag Creator',
        'short_desc' => 'Create complete website favicon suites (16x16, 32x32, 48x48, Apple Touch Icon, Android Chrome) with ready-to-paste HTML tags.',
        'meta_title' => 'Favicon Generator — Multi-Size Favicon & HTML Tags | ToolZeno',
        'meta_desc' => 'Generate 16x16, 32x32, and 180x180 favicons from your logo. Includes ready-to-use HTML link tags.',
    ],
    'youtube_thumbnail_resizer' => [
        'name' => 'YouTube Thumbnail Resizer (1280x720)',
        'short_desc' => 'Fit and scale images into standard 1280x720 (16:9) YouTube thumbnails with duration badge safe-zone overlays.',
        'meta_title' => 'YouTube Thumbnail Resizer — 1280x720 HD Thumbnails | ToolZeno',
        'meta_desc' => 'Resize and optimize images for YouTube thumbnails at recommended 1280x720 resolution with timestamp safe guide.',
    ],
    'social_media_resizer' => [
        'name' => 'Social Media Preset Resizer',
        'short_desc' => 'One-click resize for Facebook covers/posts, Instagram squares/stories, Twitter/X headers, and LinkedIn banners.',
        'meta_title' => 'Social Media Image Resizer — Facebook, Instagram, X Presets | ToolZeno',
        'meta_desc' => 'Crop and resize photos for all social networks. Pre-configured dimensions for Facebook, Instagram, Twitter, and LinkedIn.',
    ],
    'android_nine_patch_helper' => [
        'name' => 'Android 9-Patch (.9.png) Helper & Previewer',
        'short_desc' => 'Inspect, define stretchable areas, and test real-time 9-patch image scaling across multiple screen dimensions.',
        'meta_title' => 'Android 9-Patch Helper — Preview & Test .9.png Drawables | ToolZeno',
        'meta_desc' => 'Visual 9-patch Android drawable previewer. Test horizontal and vertical stretch markers in real time.',
    ],
    'color_palette_extractor' => [
        'name' => 'Color Palette Extractor',
        'short_desc' => 'Extract dominant color palettes and prominent HEX/RGB codes from any photo with instant one-click copying and CSS export.',
        'meta_title' => 'Color Palette Extractor — Extract Colors from Photo Online | ToolZeno',
        'meta_desc' => 'Extract dominant colors and HEX values from images. Copy color codes and export palette as CSS or JSON.',
    ],
    'grayscale_image_converter' => [
        'name' => 'Grayscale & Black and White Converter',
        'short_desc' => 'Convert color photos into classic monochrome or high-contrast black and white images with sepia and silver tone options.',
        'meta_title' => 'Grayscale Image Converter — Black & White Photo Filter | ToolZeno',
        'meta_desc' => 'Turn photos into black and white online. Apply grayscale, sepia, and artistic monochrome filters instantly.',
    ],
    'image_inverter' => [
        'name' => 'Image Color Inverter (Negative Effect)',
        'short_desc' => 'Invert color channels to produce artistic photographic negative effects with per-channel RGB toggles.',
        'meta_title' => 'Image Color Inverter — Photo Negative Effect Online | ToolZeno',
        'meta_desc' => 'Invert image colors to create photographic negatives. Fast client-side pixel manipulation with instant download.',
    ],
    'brightness_contrast_adjuster' => [
        'name' => 'Brightness & Contrast Adjuster',
        'short_desc' => 'Fine-tune photo brightness, contrast, saturation, and hue with interactive sliders and live real-time canvas preview.',
        'meta_title' => 'Brightness & Contrast Adjuster — Enhance Photos Online | ToolZeno',
        'meta_desc' => 'Adjust photo brightness, contrast, and saturation online. Enhance underexposed photos directly in your browser.',
    ],
    'image_blur_pixelate' => [
        'name' => 'Image Blur & Pixelate (Censor Tool)',
        'short_desc' => 'Blur or pixelate sensitive regions, faces, documents, or license plates on photos to protect personal privacy.',
        'meta_title' => 'Image Blur & Pixelate — Censor Sensitive Photo Areas | ToolZeno',
        'meta_desc' => 'Blur or pixelate confidential data on images. Protect privacy before sharing screenshots and ID photos.',
    ],
    'image_duotone_filter' => [
        'name' => 'Color Tint & Duotone Filter',
        'short_desc' => 'Apply stylish two-tone duotone gradient filters and custom color tints to photos inspired by Spotify and poster art.',
        'meta_title' => 'Duotone Image Filter — Stylish Two-Tone Color Effects | ToolZeno',
        'meta_desc' => 'Create Spotify-style duotone and custom color tint images online. Choose highlight and shadow colors with live preview.',
    ],
    'watermark_adder' => [
        'name' => 'Watermark Adder (Text & Logo)',
        'short_desc' => 'Stamp custom text or transparent PNG logos onto photos with adjustable position, opacity, font size, and rotation.',
        'meta_title' => 'Watermark Adder — Add Text & Logo Watermarks to Photos | ToolZeno',
        'meta_desc' => 'Protect photos by adding custom text or logo watermarks. Full control over opacity, positioning, and rotation.',
    ],
    'meme_generator' => [
        'name' => 'Meme Generator',
        'short_desc' => 'Create viral memes in seconds by adding bold top and bottom caption text with classic outlined typography.',
        'meta_title' => 'Meme Generator — Create Top & Bottom Text Memes Free | ToolZeno',
        'meta_desc' => 'Make memes online with custom photos. Add bold Impact font top and bottom captions and download instantly.',
    ],
    'dummy_image_generator' => [
        'name' => 'Dummy Image Placeholder Generator',
        'short_desc' => 'Generate custom dummy placeholder images (e.g. 300x250, 800x600, 1920x1080) for web designers and mockup developers.',
        'meta_title' => 'Dummy Image Generator — Create Placeholder Images Online | ToolZeno',
        'meta_desc' => 'Generate clean placeholder images with custom dimensions, background colors, and labels for UI design.',
    ],
    'image_to_ascii' => [
        'name' => 'Image to ASCII Art Converter',
        'short_desc' => 'Transform photos into creative ASCII character text art with character density sets, font size, and color modes.',
        'meta_title' => 'Image to ASCII Art Converter — Photo to Text Art Online | ToolZeno',
        'meta_desc' => 'Convert images into ASCII art text strings. Copy text output or export as styled canvas image.',
    ],
    'photo_frame_maker' => [
        'name' => 'Polaroid & Frame Photo Maker',
        'short_desc' => 'Add vintage Polaroid white borders, wooden gallery frames, or minimal shadow borders with custom bottom captions.',
        'meta_title' => 'Polaroid & Frame Photo Maker — Add Vintage Frames to Photos | ToolZeno',
        'meta_desc' => 'Frame your pictures with retro Polaroid borders, artistic frames, and handwriting style captions.',
    ],
    'exif_data_viewer' => [
        'name' => 'EXIF Data Viewer & Inspector',
        'short_desc' => 'Inspect hidden photo metadata including camera model, shutter speed, ISO, aperture, focal length, date, and GPS coordinates.',
        'meta_title' => 'EXIF Data Viewer — Read Photo Metadata & Camera Settings | ToolZeno',
        'meta_desc' => 'View EXIF metadata of any photo. See camera settings, shutter speed, ISO, focal length, and GPS location in browser.',
    ],
    'exif_stripper' => [
        'name' => 'EXIF & Metadata Stripper',
        'short_desc' => 'Strip all hidden EXIF metadata, camera serial numbers, and GPS location data to protect personal privacy before sharing.',
        'meta_title' => 'EXIF Metadata Stripper — Remove GPS & Camera Tags Free | ToolZeno',
        'meta_desc' => 'Remove private EXIF metadata and GPS location from photos before uploading to social media.',
    ],
    'image_to_base64' => [
        'name' => 'Image to Base64 Data URI Converter',
        'short_desc' => 'Convert any image into a Base64 encoded string or Data URI suitable for direct embedding into HTML, CSS, or JSON payloads.',
        'meta_title' => 'Image to Base64 Converter — Convert Photo to Data URI | ToolZeno',
        'meta_desc' => 'Convert JPG, PNG, and SVG images to Base64 strings. Get ready-to-copy HTML <img> and CSS background snippets.',
    ],
    'base64_to_image' => [
        'name' => 'Base64 to Image Decoder',
        'short_desc' => 'Paste raw Base64 strings or Data URIs to preview, inspect dimensions, and download the decoded image as PNG or JPG.',
        'meta_title' => 'Base64 to Image Decoder — Decode & Download Photos Online | ToolZeno',
        'meta_desc' => 'Paste Base64 data strings to preview and download images in PNG or JPG format with instant mime detection.',
    ],
    'image_dpi_converter' => [
        'name' => 'Image DPI Converter (72 to 300 DPI)',
        'short_desc' => 'Adjust image DPI metadata for professional printing (72 DPI web to 300 DPI print standard) and view print dimensions.',
        'meta_title' => 'Image DPI Converter — Change Photo DPI to 300 Online | ToolZeno',
        'meta_desc' => 'Convert photo DPI to 300 DPI for high-resolution printing. Calculate physical print sizes in inches and centimeters.',
    ],
    'audio_trimmer' => [
        'name' => 'Audio Trimmer & Cutter',
        'short_desc' => 'Cut and trim audio files (MP3, WAV, OGG) with an interactive waveform visualizer, millisecond precision, and instant export.',
        'meta_title' => 'Audio Trimmer — Cut MP3 & Audio Tracks Online Free | ToolZeno',
        'meta_desc' => 'Trim MP3 and audio tracks in your browser. Interactive waveform visualizer with precise start/end markers.',
    ],
    'audio_converter' => [
        'name' => 'Audio Format Converter (MP3, WAV, OGG)',
        'short_desc' => 'Convert audio tracks between MP3, WAV, and OGG formats with customizable bitrates and sample rates.',
        'meta_title' => 'Audio Format Converter — Convert Audio Files Online | ToolZeno',
        'meta_desc' => 'Convert audio files between MP3, WAV, and OGG directly in browser using the Web Audio API.',
    ],
    'aspect_ratio_calculator' => [
        'name' => 'Aspect Ratio Calculator',
        'short_desc' => 'Calculate image and video aspect ratios in real time with standard display presets (16:9, 4:3, 21:9, 1:1, 9:16).',
        'meta_title' => 'Aspect Ratio Calculator — Video & Screen Dimensions | ToolZeno',
        'meta_desc' => 'Calculate aspect ratios for screens, videos, and photography. Find missing width or height with responsive preview.',
    ],
    'video_frame_grabber' => [
        'name' => 'Video Frame Grabber',
        'short_desc' => 'Extract and capture high-resolution still image frames from MP4 and WebM videos at exact millisecond timestamps.',
        'meta_title' => 'Video Frame Grabber — Capture Still Photos from Video | ToolZeno',
        'meta_desc' => 'Capture HD photo frames from any video. Scrub frame-by-frame and save high-resolution snapshots in PNG or JPG.',
    ],
    'voice_recorder' => [
        'name' => 'Voice & Audio Recorder',
        'short_desc' => 'Record voice memos and microphone audio directly in your browser with live waveform visualization and instant download.',
        'meta_title' => 'Voice & Audio Recorder — Record Microphone Audio Online | ToolZeno',
        'meta_desc' => 'Record clear voice audio using your browser microphone. Live audio visualizer, playback, and free file export.',
    ],
];
