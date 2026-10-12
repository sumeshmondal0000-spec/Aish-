<?php
/**
 * Plugin Name: Aisho Navratri 2026 Campaign
 * Description: Temporary Find Your Garba Partner landing page and festive homepage hero. Automatically expires after 24 October 2026, 11:59 PM IST.
 * Version: 1.1.0
 * Author: Aisho
 */

if (!defined('ABSPATH')) exit;

final class Aisho_Navratri_2026 {
    const VERSION = '1.1.0';
    const ROUTE = 'find-your-garba-partner';
    const END_AT = '2026-10-24 23:59:59';

    public static function boot() {
        add_action('init', [__CLASS__, 'rewrite']);
        add_filter('query_vars', [__CLASS__, 'query_vars']);
        add_action('template_redirect', [__CLASS__, 'landing']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
        add_filter('body_class', [__CLASS__, 'body_class']);
        add_action('wp_body_open', [__CLASS__, 'homepage_hero']);
        add_action('wp_footer', [__CLASS__, 'footer_fallback'], 2);
    }

    public static function active() {
        $tz = new DateTimeZone('Asia/Kolkata');
        return new DateTime('now', $tz) <= new DateTime(self::END_AT, $tz);
    }

    public static function rewrite() {
        add_rewrite_rule('^' . self::ROUTE . '/?$', 'index.php?aisho_navratri=1', 'top');
    }

    public static function query_vars($vars) {
        $vars[] = 'aisho_navratri';
        return $vars;
    }

    public static function activate() { self::rewrite(); flush_rewrite_rules(); }
    public static function deactivate() { flush_rewrite_rules(); }

    public static function body_class($classes) {
        if (self::active() && is_front_page()) $classes[] = 'aisho-navratri-active';
        return $classes;
    }

    public static function assets() {
        if (!self::active()) return;
        if (is_front_page() || get_query_var('aisho_navratri')) {
            wp_enqueue_style('aisho-navratri', plugin_dir_url(__FILE__) . 'assets/navratri.css', [], self::VERSION);
            wp_enqueue_script('aisho-navratri', plugin_dir_url(__FILE__) . 'assets/navratri.js', [], self::VERSION, true);
        }
    }

    private static function homepage_markup() {
        return '<section id="aisho-navratri-hero" class="aisho-festive-hero" aria-label="Aisho Navratri special">
            <div class="aisho-home-copy">
                <p>AISHO NAVRATRI SPECIAL <span>✦</span></p>
                <h2>Find Your<br><em>Garba Partner.</em></h2>
                <div class="aisho-rule"></div>
                <p class="aisho-home-sub">Under the moonlight, every rhythm can lead to a meaningful connection.</p>
                <a href="' . esc_url(home_url('/' . self::ROUTE . '/')) . '">FIND MY MATCH <b>→</b></a>
                <small>Five playful questions · No signup required · 18+</small>
            </div>
            <span class="aisho-season-note">LIVE UNTIL 24 OCTOBER 2026</span>
        </section>';
    }

    public static function homepage_hero() {
        if (!self::active() || !is_front_page()) return;
        echo self::homepage_markup();
    }

    public static function footer_fallback() {
        if (!self::active() || !is_front_page()) return;
        echo '<script>if(!document.getElementById("aisho-navratri-hero")){document.body.insertAdjacentHTML("afterbegin",'.wp_json_encode(self::homepage_markup()).');}</script>';
    }

    public static function landing() {
        if (!get_query_var('aisho_navratri')) return;
        if (!self::active()) { wp_safe_redirect(home_url('/'), 302); exit; }
        status_header(200);
        nocache_headers();
        ?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Find Your Garba Partner | Aisho Navratri Special</title>
<meta name="description" content="Find your Garba vibe with Aisho. Answer five playful questions and discover your ideal Navratri partner match.">
<meta name="robots" content="noindex,follow">
<?php wp_head(); ?>
</head>
<body class="aisho-garba-page">
<main class="garba-shell">
    <nav class="garba-nav">
        <a class="garba-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Aisho home">aō</a>
        <span>NAVRATRI 2026 · INDIA · 18+</span>
    </nav>

    <section class="garba-hero">
        <div class="garba-copy">
            <p class="eyebrow">AISHO NAVRATRI SPECIAL <span>✦</span></p>
            <h1>Find Your<br><em>Garba Partner.</em></h1>
            <div class="garba-line"></div>
            <p>Five playful questions. One festive vibe. Discover who matches your rhythm beneath the moonlight.</p>
            <button class="garba-start" type="button">FIND MY MATCH <span>→</span></button>
            <small>No signup required · Just for fun · 18+</small>
        </div>
        <p class="garba-scene-label">WHERE CHEMISTRY MEETS COMPATIBILITY.</p>
    </section>

    <section class="garba-quiz" hidden>
        <div class="quiz-panel">
            <div class="quiz-progress"><span></span></div>
            <p class="quiz-count">QUESTION <b>1</b> OF 5</p>
            <h2 class="quiz-question"></h2>
            <div class="quiz-options"></div>
        </div>
    </section>

    <section class="garba-result" hidden>
        <div class="result-panel">
            <p class="eyebrow">YOUR NAVRATRI MATCH ✦</p>
            <div class="result-score">94%</div>
            <h2>Your vibe is<br><em>The Effortless Dancer.</em></h2>
            <p>You need someone who laughs between steps, keeps the energy easy, and never takes a missed beat too seriously.</p>
            <a href="https://www.instagram.com/aisho_india/" target="_blank" rel="noopener">FOLLOW @AISHO_INDIA <span>→</span></a>
            <button class="garba-restart" type="button">PLAY AGAIN</button>
        </div>
    </section>

    <footer class="garba-footer"><span>AISHO © 2026</span><span>LIVE UNTIL 24 OCTOBER · 11:59 PM IST</span></footer>
</main>
<?php wp_footer(); ?>
</body>
</html><?php
        exit;
    }
}

register_activation_hook(__FILE__, ['Aisho_Navratri_2026', 'activate']);
register_deactivation_hook(__FILE__, ['Aisho_Navratri_2026', 'deactivate']);
Aisho_Navratri_2026::boot();
