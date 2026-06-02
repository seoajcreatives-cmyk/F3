<?php
// CONFIGURACIÓN PARA F3 Construction
$site_url  = "https://f3constructionny.com"; // sin / al final
$blog_path = __DIR__ . "/blog";              // carpeta donde están tus posts
$base_slug = "/blog";                        // parte de la URL después del dominio

header("Content-Type: application/xml; charset=utf-8");

// Extensiones permitidas
$extensiones = ['php', 'html', 'htm'];

// Recorrer la carpeta del blog
$posts = [];

if (is_dir($blog_path)) {
    $dir = opendir($blog_path);
    while (($file = readdir($dir)) !== false) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $path_info = pathinfo($file);
        if (!isset($path_info['extension'])) {
            continue;
        }

        $ext = strtolower($path_info['extension']);
        if (in_array($ext, $extensiones)) {

            $full_path = $blog_path . "/" . $file;
            $url       = $site_url . $base_slug . "/" . $file;
            $lastmod   = date("Y-m-d", filemtime($full_path));

            $posts[] = [
                'loc'        => $url,
                'lastmod'    => $lastmod,
                'priority'   => '0.80',
                'changefreq' => 'weekly'
            ];
        }
    }
    closedir($dir);
}

// Ordenar por fecha (recientes primero)
usort($posts, function($a, $b) {
    return strcmp($b['lastmod'], $a['lastmod']);
});

// Salida XML
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($posts as $post): ?>
  <url>
    <loc><?= htmlspecialchars($post['loc'], ENT_XML1, 'UTF-8'); ?></loc>
    <lastmod><?= $post['lastmod']; ?></lastmod>
    <changefreq><?= $post['changefreq']; ?></changefreq>
    <priority><?= $post['priority']; ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
