


<?php
$api_url = "https://www.f3constructionny.com/blog/wp-json/wp/v2/posts?per_page=3&_embed";

$response = file_get_contents($api_url);
$posts = json_decode($response);
echo "<script>console.log('Received response from API:', " . json_encode($posts) . ");</script>";

foreach ($posts as $post) {

    $title = $post->title->rendered;
    $link = $post->link;
    $date = date("F d, Y", strtotime($post->date)); 

    $image = "";
    if (isset($post->_embedded->{'wp:featuredmedia'})) {
        $image = $post->_embedded->{'wp:featuredmedia'}[0]->source_url;
    }

    echo "
    <div class='blog-card'>
        <div class='blog-image'>
            <img src='$image' alt='$title' loading='lazy'>
        </div>
        <div class='blog-content'>
            <h3>$title</h3>
            <p class='blog-date'>$date</p>
            <a href='$link' class='read-btn'><span>Read more</span></a>
        </div>
    </div>
    ";
}
?>