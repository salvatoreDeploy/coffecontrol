<?php
  if(strpos(url(), "localhost")){
    /**
     * CSS
     */

     $minifyCSS = new MatthiasMullie\Minify\CSS();
     $minifyCSS->add(__DIR__ . "/../../shared/styles/styles.css");
     $minifyCSS->add(__DIR__ . "/../../shared/styles/boot.css");

     // Theme CSS

     $cssDir = scandir(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/css");
     
     foreach($cssDir as $css){
        $cssFile = __DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/css/{$css}";
        
        if(is_file($cssFile) && pathinfo($cssFile)['extension'] == 'css'){
          $minifyCSS->add($cssFile);
        }
     }

     // Minify CSS

     $minifyCSS->minify(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/style.css");

     /**
      * JS
      */

     $minifyJS = new MatthiasMullie\Minify\JS();

     $minifyJS->add(__DIR__ . "/../../shared/scripts/jquery.min.js");
     $minifyJS->add(__DIR__ . "/../../shared/scripts/jquery-ui.js");

     $jsDir = scandir(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/js");
     
     foreach($jsDir as $js){
        $jsDir = __DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/js/{$js}";

        $jsFile = "";
        
        if(is_file($jsFile) && pathinfo($jsFile)['extension'] == 'js'){
          $minifyJS->add($jsFile);
        }
     }

     // Minify JS

     $minifyJS->minify(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/assets/scripts.js");
  }
?>