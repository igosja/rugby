<?php

/**
 * @var string $host
 * @var array $urls
 */

print '<?xml version="1.0" encoding="UTF-8"?>';

?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= $host . '/' ?></loc>
        <lastmod><?= date(DATE_W3C) ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>1.00</priority>
    </url>
    <?php foreach ($urls as $url) : ?>
        <url>
            <loc><?= $host . $url['loc'] ?></loc>
            <lastmod><?= $url['lastmod'] ?></lastmod>
            <changefreq>monthly</changefreq>
            <priority><?= $url['priority'] ?></priority>
        </url>
    <?php endforeach ?>
</urlset>
