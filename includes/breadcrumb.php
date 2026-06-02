
<section class="breadcrumb-section">
    <?php
        $ruta = $_SERVER['REQUEST_URI'];
        $partes = explode("/", trim($ruta, "/"));
        
        $paginaActual = end($partes);
        $paginaLimpia = ucwords(str_replace(array("-", "_"), " ", preg_replace('/\.php$/', '', $paginaActual))); 

        echo '<nav aria-label="breadcrumb"><ul class="breadcrumb-style">';
        echo '<li class="active-style"><a href="/">Home</a></li>';

        
        if (!empty($paginaLimpia)) {
            echo '<li class="active-style">' . $paginaLimpia . '</li>';
        }

        echo '</ul></nav>';
    ?>
</section>