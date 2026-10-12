<?php
/**
 * Plugin Name: Aisho Navratri 2026 Campaign
 * Description: Temporary Find Your Garba Partner landing page and festive homepage layer. Automatically expires after 24 October 2026, 11:59 PM IST.
 * Version: 1.0.0
 * Author: Aisho
 */

if (!defined('ABSPATH')) exit;

final class Aisho_Navratri_2026 {
    const VERSION = '1.0.0';
    const ROUTE = 'find-your-garba-partner';
    const END_AT = '2026-10-24 23:59:59';

    public static function boot() {
        add_action('init', [__CLASS__, 'rewrite']);
        add_filter('query_vars', [__CLASS__, 'query_vars']);
        add_action('template_redirect', [__CLASS__, 'landing']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
        add_filter('body_class', [__CLASS__, 'body_class']);
        add_action('wp_body_open', [__CLASS__, 'homepage_banner']);
        add_action('wp_footer', [__CLASS__, 'footer_fallback'], 2);
    }

    public static function active() {
        $tz = new DateTimeZone('Asia/Kolkata');
        $now = new DateTime('now', $tz);
        $end = new DateTime(self::END_AT, $tz);
        return $now <= $end;
    }

    public static function rewrite() {
        add_rewrite_rule('^' . self::ROUTE . '/?$', 'index.php?aisho_navratri=1', 'top');
    }

    public static function query_vars($vars) {
        $vars[] = 'aisho_navratri';
        return $vars;
    }

    public static function activate() {
        self::rewrite();
        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }

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

    public static function homepage_banner() {
        if (!self::active() || !is_front_page()) return;
        echo '<div id="aisho-navratri-banner" class="aisho-festive-banner" role="region" aria-label="Navratri campaign">
            <span class="aisho-dandiya" aria-hidden="true">✦</span>
            <div><small>NAVRATRI SPECIAL</small><strong>Find Your Garba Partner</strong><span>Match your rhythm. Meet your vibe.</span></div>
            <a href="' . esc_url(home_url('/' . self::ROUTE . '/')) . '">PLAY NOW <b>→</b></a>
        </div>';
    }

    public static function footer_fallback() {
        if (!self::active() || !is_front_page()) return;
        echo '<script>if(!document.getElementById("aisho-navratri-banner")){document.body.insertAdjacentHTML("afterbegin",'.wp_json_encode('<div id="aisho-navratri-banner" class="aisho-festive-banner"><span class="aisho-dandiya">✦</span><div><small>NAVRATRI SPECIAL</small><strong>Find Your Garba Partner</strong><span>Match your rhythm. Meet your vibe.</span></div><a href="'.esc_url(home_url('/'.self::ROUTE.'/')).'">PLAY NOW <b>→</b></a></div>').');}</script>';
    }

    public static function landing() {
        if (!get_query_var('aisho_navratri')) return;
        if (!self::active()) {
            wp_safe_redirect(home_url('/'), 302);
            exit;
        }
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
            <p class="eyebrow">AISHO NAVRATRI SPECIAL ✦</p>
            <h1>Find Your<br><em>Garba Partner.</em></h1>
            <p>Five playful questions. One festive vibe. Discover who matches your rhythm before the music starts.</p>
            <button class="garba-start" type="button">FIND MY MATCH <span>→</span></button>
            <small>No signup required · Just for fun · 18+</small>
        </div>
        <div class="garba-art" aria-hidden="true">
            <div class="orbit orbit-one"></div><div class="orbit orbit-two"></div>
            <div class="dandiya-card left-stick">◆</div><div class="dandiya-card right-stick">◆</div>
            <div class="match-badge"><b>94%</b><span>GARBA VIBE</span></div>
            <div class="spark s1">✦</div><div class="spark s2">✦</div><div class="spark s3">✦</div>
        </div>
    </section>

    <section class="garba-quiz" hidden>
        <div class="quiz-progress"><span></span></div>
        <p class="quiz-count">QUESTION <b>1</b> OF 5</p>
        <h2 class="quiz-question"></h2>
        <div class="quiz-options"></div>
    </section>

    <section class="garba-result" hidden>
        <p class="eyebrow">YOUR NAVRATRI MATCH ✦</p>
        <div class="result-score">94%</div>
        <h2>Your vibe is<br><em>The Effortless Dancer.</em></h2>
        <p>You need someone who laughs between steps, keeps the energy easy, and never takes a missed beat too seriously.</p>
        <a href="https://www.instagram.com/aisho_india/" target="_blank" rel="noopener">FOLLOW @AISHO_INDIA <span>→</span></a>
        <button class="garba-restart" type="button">PLAY AGAIN</button>
    </section>

    <footer class="garba-footer"><span>AISHO © 2026</span><span>WHERE CHEMISTRY MEETS COMPATIBILITY.</span></footer>
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
