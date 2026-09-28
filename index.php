<?php
// Developer: Ashwani Kushwaha

// Minifies the final HTML response. Only strips per-line indentation, blank
// lines, and comments — never collapses whitespace between tags, so inline
// spacing (e.g. text next to an icon) can never be affected. Script/style/
// pre/textarea contents (JSON-LD, importmap, etc.) are left byte-for-byte
// untouched.
function minify_html($html) {
    $placeholders = [];
    $html = preg_replace_callback('#<(script|style|pre|textarea)\b[^>]*>.*?</\1>#is', function ($m) use (&$placeholders) {
        $key = "\x01" . count($placeholders) . "\x02";
        $placeholders[$key] = $m[0];
        return $key;
    }, $html);

    $html = preg_replace('/<!--.*?-->/s', '', $html);
    $html = preg_replace('/^[ \t]+|[ \t]+$/m', '', $html);
    $html = preg_replace('/\n{2,}/', "\n", $html);

    foreach ($placeholders as $key => $value) {
        $html = str_replace($key, $value, $html);
    }
    return trim($html);
}
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Free Background Remover — Remove Image Background Online | iAuditOne</title>
<meta name="description" content="Remove image backgrounds instantly for free with iAuditOne. AI-powered, runs entirely in your browser — no uploads, no signup, no watermark.">
<meta name="keywords" content="remove background, remove image background, background remover, transparent PNG, AI background removal, free background remover, iAuditOne">
<meta name="robots" content="index, follow">
<meta name="author" content="iAuditOne">
<meta name="developer" content="iAuditOne">
<meta name="theme-color" content="#0a0e1a">
<link rel="canonical" href="https://patri.in/remove_bg/">

<!-- Open Graph / Facebook, LinkedIn, WhatsApp -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="iAuditOne">
<meta property="og:url" content="https://patri.in/remove_bg/">
<meta property="og:title" content="Free Background Remover — Remove Image Background Online | iAuditOne">
<meta property="og:description" content="Remove image backgrounds instantly for free with iAuditOne. AI-powered, runs entirely in your browser — no uploads, no signup, no watermark.">
<meta property="og:image" content="https://patri.in/remove_bg/assets/img/og-image.png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="en_US">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Free Background Remover — Remove Image Background Online | iAuditOne">
<meta name="twitter:description" content="Remove image backgrounds instantly for free with iAuditOne. AI-powered, runs entirely in your browser — no uploads, no signup, no watermark.">
<meta name="twitter:image" content="https://patri.in/remove_bg/assets/img/og-image.png">

<link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon_iso/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon_iso/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon_iso/favicon-16x16.png">
<link rel="stylesheet" href="assets/css/fonts.min.css">
<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.min.css">
<script type="importmap">
{
    "imports": {
        "onnxruntime-web": "./assets/vendor/onnxruntime-web/ort.bundle.min.mjs",
        "onnxruntime-web/webgpu": "./assets/vendor/onnxruntime-web/ort.bundle.min.mjs"
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@graph": [
        {
            "@type": "Organization",
            "@id": "https://patri.in/remove_bg/#organization",
            "name": "iAuditOne",
            "url": "https://patri.in/remove_bg/",
            "logo": "https://patri.in/remove_bg/assets/img/iAudit_logo.png"
        },
        {
            "@type": "WebSite",
            "@id": "https://patri.in/remove_bg/#website",
            "name": "iAuditOne Remove Background",
            "url": "https://patri.in/remove_bg/",
            "publisher": { "@id": "https://patri.in/remove_bg/#organization" }
        },
        {
            "@type": "SoftwareApplication",
            "@id": "https://patri.in/remove_bg/#software",
            "name": "iAuditOne Remove Background",
            "url": "https://patri.in/remove_bg/",
            "description": "Free AI-powered tool that removes the background from any photo instantly, right in your browser. No uploads to a server, no signup, no watermark.",
            "applicationCategory": "PhotoEditingApplication",
            "operatingSystem": "Any (runs in a web browser)",
            "browserRequirements": "Requires a modern browser with WebAssembly support",
            "image": "https://patri.in/remove_bg/assets/img/og-image.png",
            "offers": {
                "@type": "Offer",
                "price": "0",
                "priceCurrency": "USD"
            },
            "publisher": { "@id": "https://patri.in/remove_bg/#organization" }
        },
        {
            "@type": "FAQPage",
            "mainEntity": [
                {
                    "@type": "Question",
                    "name": "Is this tool really free to use?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "Yes. iAuditOne's background remover is completely free, with no limits on the number of images you can process."
                    }
                },
                {
                    "@type": "Question",
                    "name": "Do you store or upload my images?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "No. Background removal happens locally in your browser using AI models loaded on your device — your images are never uploaded to a server."
                    }
                },
                {
                    "@type": "Question",
                    "name": "What image formats are supported?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "You can upload PNG, JPG, or WEBP images. Results are downloaded as transparent PNG files."
                    }
                },
                {
                    "@type": "Question",
                    "name": "Is there a file size or usage limit?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "There's no hard limit, but very large images may take longer to process depending on your device's performance."
                    }
                },
                {
                    "@type": "Question",
                    "name": "Can I use the results commercially?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "Yes — the processed images are yours to use however you like, including for commercial projects."
                    }
                },
                {
                    "@type": "Question",
                    "name": "Which browsers are supported?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "Any modern browser with WebAssembly support, including Chrome, Edge, Firefox, and Safari."
                    }
                }
            ]
        }
    ]
}
</script>
</head>
<body>

<header class="site-header">
    <nav class="navbar navbar-expand-lg header-inner" aria-label="Primary">
        <a href="#top" class="navbar-brand brand">
            <img class="brand-logo" src="assets/img/iAudit_logo.png" alt="iAuditOne" width="252" height="85">
            <!-- <span class="brand-text">Remove Background</span> -->
        </a>
        <div class="header-actions">
            <!-- <a href="#tool" class="btn btn-primary btn-nav"><span class="btn-nav-full">Remove Background</span><span class="btn-nav-short">Try it</span></a> -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNavCollapse" aria-controls="siteNavCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="siteNavCollapse">
            <ul class="navbar-nav site-nav">
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#how-it-works">How it Works</a></li>
                <li class="nav-item"><a class="nav-link" href="#family">Our Products</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
            </ul>
        </div>
    </nav>
</header>

<main>
    <section class="hero" id="top">
        <div class="hero-grid">
            <div class="hero-copy">
                <span class="eyebrow">Free &bull; Private &bull; No Sign-up Required</span>
                <h1>Remove image backgrounds in seconds</h1>
                <p class="hero-sub">iAuditOne's AI background remover erases the background from any photo right inside your browser — no uploads to a server, no watermarks, no limits.</p>
                <div class="hero-badges">
                    <span class="trust-badge"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4" stroke-linecap="round"/></svg>100% on-device processing</span>
                    <span class="trust-badge"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 4 14h6l-1 8 9-12h-6l1-8z" stroke-linejoin="round"/></svg>Instant AI results</span>
                    <span class="trust-badge"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="12" r="5"/><circle cx="16" cy="12" r="5"/></svg>Unlimited &amp; free</span>
                </div>
                <!-- <a href="#tool" class="btn btn-primary btn-lg">Upload an Image</a> -->
            </div>
            <div class="hero-visual" id="tool">
                <div class="upload-card">
                    <header class="upload-card-header">
                        <h2>Upload your image</h2>
                        <p class="subtitle">Drop a photo below and click Start — the background is removed instantly, right in your browser.</p>
                    </header>

                    <section class="steps">
                        <div class="step" data-step="1">
                            <span class="step-num">1</span>
                            <span>Upload image</span>
                        </div>
                        <div class="step" data-step="2">
                            <span class="step-num">2</span>
                            <span>Start</span>
                        </div>
                        <div class="step" data-step="3">
                            <span class="step-num">3</span>
                            <span>Remove background</span>
                        </div>
                        <div class="step" data-step="4">
                            <span class="step-num">4</span>
                            <span>Download</span>
                        </div>
                    </section>

                    <div id="errorBox" class="error-box" hidden></div>

                    <div id="dropzone" class="dropzone" tabindex="0" role="button" aria-label="Upload image">
                        <input type="file" id="fileInput" accept="image/*" hidden>
                        <div id="dropzoneEmpty" class="dropzone-empty">
                            <svg class="upload-icon" viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 16V4M12 4l-4 4M12 4l4 4" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p><strong>Drag &amp; drop</strong> an image here, or click to browse</p>
                            <span class="hint">PNG, JPG, or WEBP</span>
                        </div>
                        <div id="dropzonePreview" class="dropzone-preview" hidden>
                            <img id="previewImg" alt="Selected image preview">
                        </div>
                    </div>

                    <div id="uploadActions" class="actions">
                        <button type="button" id="startBtn" class="btn btn-primary" disabled>Start</button>
                    </div>

                    <div id="progressBox" class="progress-box" hidden>
                        <div class="spinner"></div>
                        <p id="progressText">Preparing…</p>
                        <div class="progress-track">
                            <div id="progressFill" class="progress-fill"></div>
                        </div>
                    </div>

                    <section id="resultBox" class="result-box" hidden>
                        <h2>Background removed</h2>
                        <div class="checkerboard">
                            <img id="resultImg" alt="Image with background removed">
                        </div>
                        <div class="result-actions">
                            <a id="downloadBtn" class="btn btn-primary" download="removed-background.png">Download PNG</a>
                            <button type="button" id="resetBtn" class="btn btn-secondary">Try another image</button>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>

    <section class="showcase reveal">
        <div class="section-head reveal">
            <h2>See it in action</h2>
            <p>Drag the slider to reveal the transparent result.</p>
        </div>
        <div class="compare-slider" id="compareSlider">
            <img class="compare-img compare-before" src="assets/img/demo-before.png" alt="Original side-profile photo of a car parked in front of a tiled wall" width="1031" height="580">
            <img class="compare-img compare-after" src="assets/img/demo-after.png" alt="Same photo with the background removed, leaving a transparent cutout of the car" width="1031" height="580">
            <span class="compare-label compare-label-before">Before</span>
            <span class="compare-label compare-label-after">After</span>
            <div class="compare-handle" role="slider" aria-label="Before and after comparison" aria-valuemin="0" aria-valuemax="100" aria-valuenow="50" tabindex="0">
                <span class="compare-handle-grip">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M8 7l-5 5 5 5M16 7l5 5-5 5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="section-head reveal">
            <h2>Why choose iAuditOne's background remover</h2>
            <p>Built for speed, privacy, and pixel-perfect results.</p>
        </div>
        <div class="feature-grid reveal">
            <div class="feature-card">
                <span class="feature-icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4" stroke-linecap="round"/></svg></span>
                <h3>100% private</h3>
                <p>Your images never leave your device. All processing happens locally in your browser using on-device AI.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 4 14h6l-1 8 9-12h-6l1-8z" stroke-linejoin="round"/></svg></span>
                <h3>Instant results</h3>
                <p>Get a clean, transparent cutout in seconds — no waiting on server queues or slow uploads.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="12" r="5"/><circle cx="16" cy="12" r="5"/></svg></span>
                <h3>Free &amp; unlimited</h3>
                <p>Remove backgrounds from as many images as you like — no watermarks, credits, or hidden fees.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M18.4 5.6l-2.1 2.1M7.7 16.3l-2.1 2.1" stroke-linecap="round"/></svg></span>
                <h3>AI-powered precision</h3>
                <p>Advanced machine learning models detect edges, hair, and fine detail for a clean, natural cutout.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M3 15l4.5-4.5a2 2 0 012.8 0L15 15M14 13l1.5-1.5a2 2 0 012.8 0L21 14" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="8" r="1.5"/></svg></span>
                <h3>Works with any photo</h3>
                <p>Supports PNG, JPG, and WEBP images — perfect for products, portraits, and graphics.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 4v12M12 16l-4-4M12 16l4-4" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 18v1a2 2 0 002 2h12a2 2 0 002-2v-1" stroke-linecap="round"/></svg></span>
                <h3>High-res PNG export</h3>
                <p>Download your result as a crisp, transparent PNG ready to use anywhere.</p>
            </div>
        </div>
    </section>

    <section class="how-it-works" id="how-it-works">
        <div class="section-head reveal">
            <h2>How it works</h2>
            <p>Four simple steps to a background-free image.</p>
        </div>
        <div class="hiw-grid reveal">
            <div class="hiw-step">
                <span class="hiw-num">1</span>
                <h3>Upload</h3>
                <p>Drag and drop or select a photo from your device.</p>
            </div>
            <div class="hiw-step">
                <span class="hiw-num">2</span>
                <h3>Start</h3>
                <p>Click Start to kick off the AI background removal.</p>
            </div>
            <div class="hiw-step">
                <span class="hiw-num">3</span>
                <h3>Processing</h3>
                <p>Our on-device model analyzes the image and removes the background automatically.</p>
            </div>
            <div class="hiw-step">
                <span class="hiw-num">4</span>
                <h3>Download</h3>
                <p>Save your transparent PNG and use it anywhere.</p>
            </div>
        </div>
    </section>

    <section class="use-cases">
        <div class="section-head reveal">
            <h2>Perfect for</h2>
            <p>One tool, endless uses.</p>
        </div>
        <div class="usecase-grid reveal">
            <div class="usecase-card">
                <span class="usecase-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8h12l-1 13H7L6 8Z" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 8V6a3 3 0 016 0v2" stroke-linecap="round"/></svg></span>
                <h3>E-commerce photos</h3>
                <p>Clean, consistent product cutouts for your online store or marketplace listings.</p>
            </div>
            <div class="usecase-card">
                <span class="usecase-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-3.87 3.58-7 8-7s8 3.13 8 7" stroke-linecap="round"/></svg></span>
                <h3>Profile &amp; ID pictures</h3>
                <p>Swap out cluttered backgrounds for a plain or transparent one in seconds.</p>
            </div>
            <div class="usecase-card">
                <span class="usecase-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11v2a2 2 0 002 2h1l3 5V6L6 11H5a2 2 0 00-2 2Z" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 8a4 4 0 010 8M19 5a8 8 0 010 14" stroke-linecap="round"/></svg></span>
                <h3>Marketing &amp; social media</h3>
                <p>Create eye-catching graphics and ads without a design tool.</p>
            </div>
            <div class="usecase-card">
                <span class="usecase-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 16v-4M12 16V8M16 16v-6" stroke-linecap="round"/></svg></span>
                <h3>Reports &amp; presentations</h3>
                <p>Drop clean subject cutouts into slides and documents in one step.</p>
            </div>
        </div>
    </section>

    <section class="product-family" id="family">
        <div class="section-head reveal">
            <h2>More from iAuditOne</h2>
            <p>One family of tools built to make business easy.</p>
        </div>
        <div class="family-grid reveal">
            <a class="family-card" href="https://iaudithrm.com/" target="_blank" rel="noopener noreferrer">
                <span class="family-icon"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="7" r="4"/><path d="M1 21v-2a4 4 0 014-4h4a4 4 0 014 4v2" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 3.13a4 4 0 010 7.75" stroke-linecap="round"/><path d="M23 21v-2a4 4 0 00-3-3.87" stroke-linecap="round"/></svg></span>
                <div class="family-body">
                    <h3>iAuditHRM</h3>
                    <p class="family-tag">Smart HR &amp; payroll software</p>
                    <p>Attendance, leave, performance, payroll, and compliance for your whole team — from one dashboard.</p>
                </div>
                <span class="family-cta">Visit iAuditHRM<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </a>
            <a class="family-card" href="https://opencompas.com/" target="_blank" rel="noopener noreferrer">
                <span class="family-icon"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10 12 5 2 10l10 5 10-5Z" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 12.5V17c0 1.66 2.69 3 6 3s6-1.34 6-3v-4.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 10v6" stroke-linecap="round"/></svg></span>
                <div class="family-body">
                    <h3>iAuditNext <span class="family-alt">(OpenCompas)</span></h3>
                    <p class="family-tag">Education ERP for schools &amp; colleges</p>
                    <p>A cashless, paperless management system for admissions, fees, attendance, and academics.</p>
                </div>
                <span class="family-cta">Visit iAuditNext<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </a>
        </div>
    </section>

    <section class="faq" id="faq">
        <div class="section-head reveal">
            <h2>Frequently asked questions</h2>
            <p>Everything you need to know before you get started.</p>
        </div>
        <div class="accordion faq-list" id="faqAccordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">Is this tool really free to use?</button>
                </h3>
                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">Yes. iAuditOne's background remover is completely free, with no limits on the number of images you can process.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">Do you store or upload my images?</button>
                </h3>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">No. Background removal happens locally in your browser using AI models loaded on your device — your images are never uploaded to a server.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">What image formats are supported?</button>
                </h3>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">You can upload PNG, JPG, or WEBP images. Results are downloaded as transparent PNG files.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">Is there a file size or usage limit?</button>
                </h3>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">There's no hard limit, but very large images may take longer to process depending on your device's performance.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">Can I use the results commercially?</button>
                </h3>
                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">Yes — the processed images are yours to use however you like, including for commercial projects.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">Which browsers are supported?</button>
                </h3>
                <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">Any modern browser with WebAssembly support, including Chrome, Edge, Firefox, and Safari.</div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-banner">
        <h2>Ready to remove your image background?</h2>
        <p>Free, private, and instant — try it now, no account needed.</p>
        <a href="#tool" class="btn btn-primary btn-lg">Try it now — it's free</a>
    </section>
</main>

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="#top" class="brand">
                <span class="brand-logo-chip"><img class="brand-logo" src="assets/img/iAudit_logo.png" alt="iAuditOne" width="252" height="85"></span>
                <span class="brand-text">Remove Background</span>
            </a>
            <p>Smart tools for smarter workflows.</p>
        </div>
        <nav class="footer-links" aria-label="Footer">
            <a href="#features">Features</a>
            <a href="#how-it-works">How it Works</a>
            <a href="#family">Our Products</a>
            <a href="#faq">FAQ</a>
        </nav>
        <p class="footer-copy">&copy; <?= date('Y') ?> iAuditOne. All rights reserved. Remove Background is a free tool by iAuditOne.</p>
    </div>
</footer>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/site.min.js"></script>
<script type="module" src="assets/js/app.min.js"></script>
</body>
</html>
<?php
echo minify_html(ob_get_clean());
