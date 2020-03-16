<?php


namespace Rdlv\WordPress\HtmlManipulation;


use WP_Styles;

class CssTargetSubset
{
    /** @var Theme */
    private $theme;

    public function __construct($theme)
    {
        $this->theme = $theme;
        add_filter('style_loader_tag', [$this, 'style_target_support'], 10, 3);
        add_filter('language_attributes', [$this, 'browser_fs_attr'], 0);
        add_action('wp_head', [$this, 'browser_fs_script'], 0);
    }

    public function browser_fs_attr($output)
    {
        return $output . ' data-browser-fs="16"';
    }

    public function browser_fs_script()
    {
        $path = $this->theme->assets_path('/js/inline-browser-fs.js');
        if (!file_exists($path)) {
            return;
        }
        echo '<script>' . file_get_contents($path) . '</script>';
    }

    public function style_target_support($tag)
    {
        global $wp_styles;
        if (!($wp_styles instanceof WP_Styles)) {
            return $tag;
        }

        $scriptPath = $this->theme->assets_path('/js/inline-targeted-css.js');
        if (!file_exists($scriptPath)) {
            return $tag;
        }

        // <link rel='stylesheet' id='main-css'  href='//localhost:3000/app/themes/linghun/assets/css/main.css?ver=5.3.2&#038;v=1582269649' type='text/css' media='screen' />
        return preg_replace_callback(
            '/<link\b(.*\bhref=([\'"])(.*?)\2.*)>/',
            function ($m) use ($scriptPath) {

                $url = $m[3];
                $glob_url = preg_replace('/(\.v[0-9]+)?(\.[^.]+)(\?.*)?$/', 'SUBSET\1\2\3', $url);
                $glob_path = preg_replace(
                    [
                        '/(\?.*)?$/', // drop query string
                        '/(\.v[0-9]+)(\.[^.]+)$/', // drop cachebust path fragment
                        '/SUBSET/',
                    ],
                    [
                        '',
                        '\2',
                        '.ts*',
                    ],
                    str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $glob_url)
                );

                // get existing targeted stylesheets
                $targets = array_filter(array_map(function ($file) {
                    if (preg_match('/\.ts([0-9]+)-([0-9]+|inf)\.css$/', $file, $m)) {
                        return [$m[1], $m[2]];
                    }
                    return false;
                }, glob($glob_path)));

                if (!$targets) {
                    return $m[0];
                }

                // extract attributes
                preg_match_all('/\b([a-z\-]+)=([\'"])(.*?)\2/', $m[1], $atts, PREG_SET_ORDER);
                $attributes = [];
                foreach ($atts as $att) {
                    $attributes[$att[1]] = $att[1] === 'href' ? $glob_url : $att[3];
                }

                $script = str_replace(
                    ['RANGES', 'ATTRIBUTES'],
                    [
                        json_encode($targets),
                        json_encode($attributes),
                    ],
                    file_get_contents($scriptPath)
                );

                return sprintf(
                    "<noscript>%s</noscript>\n" .
                    "<script>%s</script>",
                    $m[0],
                    $script,
                );
            },
            $tag
        );
    }
}