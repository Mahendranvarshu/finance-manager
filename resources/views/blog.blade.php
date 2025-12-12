<?php
$blog = [
    'id' => 34,
    'title' => "Breakthrough: NVIDIA Unveils Next-Gen Phoenix X2 Chipset",
    'short_description' => "NVIDIA has announced the Phoenix X2 chipset with 4nm technology and AI-native acceleration. Explore what this means for computers, gamers, and the future.",
    'image_url' => "https://www.technewsworld.com/wp-content/uploads/sites/3/2025/12/Nvidia-house-of-cards.jpg"
];
$blogUrl = "https://finance-manager-main-wonvyi.laravel.cloud/blog";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= htmlspecialchars($blog['title']) ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?= htmlspecialchars($blog['title']) ?>" />
    <meta property="og:description" content="<?= htmlspecialchars($blog['short_description']) ?>" />
    <meta property="og:image" content="<?= $blog['image_url'] ?>" />
    <meta property="og:url" content="<?= $blogUrl ?>" />
    <meta property="og:type" content="article" />

    <!-- UI Enhancements -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            margin: 0;
            background: #f5f7fa;
            color: #23395d;
        }
        .container {
            max-width: 650px;
            background: #fff;
            margin: 40px auto;
            border-radius: 12px;
            box-shadow: 0 4px 32px 0 rgba(0,0,0,0.07);
            overflow: hidden;
        }
        .blog-image {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-bottom: 2px solid #e5ecfa;
        }
        .blog-content {
            padding: 2rem;
        }
        .blog-title {
            font-size: 2.1rem;
            font-weight: 700;
            margin-bottom: 0.25em;
            color: #03396c;
        }
        .blog-summary {
            font-size: 1.15rem;
            margin-bottom: 1.2em;
            color: #195571;
            font-weight: 500;
        }
        .divider {
            height: 1px;
            background: #e7eeff;
            border: none;
            margin: 1.4em 0;
        }
        .full-article {
            font-size: 1.07rem;
            color: #374c6e;
            margin-bottom: 1.6em;
            line-height: 1.65;
        }
        .share-btn {
            display: inline-flex;
            align-items: center;
            background: #0a66c2;
            color: #fff;
            border: none;
            padding: 0.75em 1.6em;
            border-radius: 7px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.18s;
            cursor: pointer;
            margin-top: 1.2em;
            box-shadow: 0 2px 8px rgba(0,102,194,0.07);
        }
        .share-btn:hover {
            background: #084c99;
            text-decoration: none;
        }
        .linkedin-icon {
            width: 20px;
            height: 20px;
            fill: #fff;
            margin-right: 0.7em;
        }
        @media (max-width: 720px) {
            .container {
                margin: 15px 5px;
            }
            .blog-image {
                height: 180px;
            }
            .blog-content {
                padding: 1rem;
            }
            .blog-title {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img class="blog-image" src="<?= $blog['image_url'] ?>" alt="NVIDIA Phoenix X2 Chipset">
        <div class="blog-content">
            <div class="blog-title"><?= htmlspecialchars($blog['title']) ?></div>
            <div class="blog-summary"><?= htmlspecialchars($blog['short_description']) ?></div>
            <hr class="divider" />

            <div class="full-article">
                <strong>Revolutionary Tech:</strong> NVIDIA's new Phoenix X2 chipset, manufactured on groundbreaking 4nm EUV process, integrates 40 billion transistors and an AI-native core structure.<br><br>
                <strong>Performance:</strong> Benchmarks show up to 2.5x performance over previous generations, especially in real-time ray tracing and ML tasks. The new unified memory architecture also significantly reduces bottlenecks for AI, creative workflows, and gaming.<br><br>
                <strong>Key Features:</strong>
                <ul>
                    <li>Integrated AI acceleration and hardware ray tracing.</li>
                    <li>Support for PCIe 5.0, DDR6 RAM.</li>
                    <li>Dynamic thermals for laptops and desktops.</li>
                    <li>Optimized for green computing (30% less power consumption).</li>
                </ul>
                <strong>Technology Ecosystem:</strong> Initial partners include ASUS, MSI, and Dell, with developer SDKs for AI-driven graphics and scientific computing launching alongside.<br><br>
                <em><b>Stay tuned</b> for in-depth benchmarks and industry expert opinions in our upcoming reviews!</em>
            </div>

            <!-- LINKEDIN SHARE BUTTON -->
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($blogUrl); ?>" 
               target="_blank" class="share-btn" title="Share on LinkedIn">
                <svg class="linkedin-icon" viewBox="0 0 32 32">
                    <path d="M28 0H4C1.78 0 0 1.78 0 4v24c0 2.22 1.78 4 4 4h24c2.22 0 4-1.78 4-4V4C32 1.78 30.22 0 28 0zM9.41 27H5.34V12.63h4.07V27zM7.38 11.1c-1.3 0-2.36-1.07-2.36-2.37 0-1.3 1.06-2.37 2.36-2.37s2.36 1.07 2.36 2.37c0 1.31-1.06 2.37-2.36 2.37zm19.62 15.88h-4.07v-6.94c0-1.65-.03-3.77-2.3-3.77-2.3 0-2.65 1.8-2.65 3.66v7.05h-4.07V12.63h3.91v1.95h.06c.54-1.03 1.86-2.13 3.84-2.13 4.11 0 4.87 2.7 4.87 6.2v8.35z" />
                </svg>
                Share on LinkedIn
            </a>
        </div>
    </div>
</body>
</html>
