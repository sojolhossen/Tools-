<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tool 1: Word and Character Counter
    |--------------------------------------------------------------------------
    */
    'word_counter' => [
        'name' => 'Word & Character Counter',
        'short_desc' => 'Count words, characters, sentences, paragraphs, and reading time in real-time with full Unicode and Bangla support.',
        'meta_title' => 'Free Word & Character Counter Online | Reading Time & Bangla Support',
        'meta_desc' => 'Accurate, real-time word counter and character counter. Counts characters with/without spaces, sentences, paragraphs, and reading time. Fully supports Bangla and international Unicode text.',
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
        'meta_title' => 'Free Text Case Converter Online | Uppercase, Lowercase, Title Case',
        'meta_desc' => 'Instantly convert text to UPPERCASE, lowercase, Title Case, Sentence case, camelCase, snake_case, and kebab-case. Fast, safe, and works in your browser.',
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
        'meta_title' => 'Free Percentage & Discount Calculator | Step-by-Step Formulas',
        'meta_desc' => 'Accurate percentage calculator for everyday math, shopping discounts, profit margins, and percentage changes. Includes clear formula explanations.',
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
        'meta_title' => 'Free Online JSON Formatter & Validator | Beautify & Minify JSON',
        'meta_desc' => 'Format, validate, beautify, and minify your JSON data directly in your browser. Spot syntax errors, format with custom tabs or spaces, and copy cleanly.',
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
        'meta_title' => 'Free QR Code Generator Online | Download PNG & SVG QR Codes',
        'meta_desc' => 'Create custom QR codes from any URL or text. Select size, error correction, and download clean PNG or SVG files. 100% free and client-side.',
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
        'meta_title' => 'Free Image Compressor Online | Reduce JPEG, PNG & WebP File Size',
        'meta_desc' => 'Compress images directly in your browser without uploading files to a server. Adjust compression quality, inspect file savings, and download optimized photos.',
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
        'meta_title' => 'Free Online Image Resizer | Change Dimensions & Maintain Aspect Ratio',
        'meta_desc' => 'Resize pictures to exact width and height or scale by percentage. Lock aspect ratio, select output format (JPEG, PNG, WebP), and download instantly.',
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
        'meta_title' => 'Free Image to PDF Converter Online | Convert JPG & PNG to PDF',
        'meta_desc' => 'Convert multiple images into a single downloadable PDF document right in your browser. Reorder images, choose page size (A4, Letter), and download securely.',
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
        'limitations_title' => 'Memory & Page Count Guidelines',
        'limitations_text' => 'Browser-side generation processes each page in memory. For optimal stability on mobile devices, compiling batches of up to 30 high-resolution images at a time is recommended.',
        'privacy_title' => 'Strict Document Confidentiality',
        'privacy_text' => 'Your scanned IDs, receipts, and photos are never uploaded to any cloud server. PDF generation occurs entirely within your browser sandbox.',
    ],
];
