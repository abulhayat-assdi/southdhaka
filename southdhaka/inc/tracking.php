<?php
/**
 * Analytics / ad-pixel snippets and the optional Turnstile spam check.
 *
 * Everything here is driven by IDs the client pastes into
 * WordPress Admin > South City Settings. Nothing is output while a field is empty.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Cloudflare Turnstile keys always look like "0x4AAAAAAA..." (test keys "1x000...").
 * Anything else (e.g. text a browser auto-filled by mistake) is treated as "not set".
 */
function south_city_is_turnstile_key(string $key): bool
{
    return preg_match('/^[0-9]x[A-Za-z0-9_-]{16,}$/', trim($key)) === 1;
}

/**
 * Cloudflare Turnstile site key — returned only when BOTH keys are saved and
 * valid, because the server refuses submissions it cannot verify.
 */
function south_city_turnstile_site_key(): string
{
    $site_key   = trim((string) south_city_get_option('turnstile_site_key', ''));
    $secret_key = trim((string) get_option('options_turnstile_secret_key', ''));

    return (south_city_is_turnstile_key($site_key) && south_city_is_turnstile_key($secret_key)) ? $site_key : '';
}

/**
 * Print the GA4 and Meta Pixel snippets.
 *
 * Logged-in users (the team testing the site) are not tracked, so they do not
 * inflate visits or conversions. Test in a private window while logged out.
 * main.js fires the events (whatsapp_click, generate_lead, Lead, ...).
 */
function south_city_output_tracking(): void
{
    if (is_user_logged_in()) {
        return;
    }

    $ga4_id   = trim((string) south_city_get_option('ga4_id', ''));
    $pixel_id = trim((string) south_city_get_option('meta_pixel_id', ''));

    if (preg_match('/^G-[A-Z0-9]{6,16}$/i', $ga4_id) === 1) {
        ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga4_id); ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?php echo esc_js($ga4_id); ?>');
</script>
        <?php
    }

    if (preg_match('/^\d{8,20}$/', $pixel_id) === 1) {
        ?>
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?php echo esc_js($pixel_id); ?>');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none" alt="" src="<?php echo esc_url('https://www.facebook.com/tr?id=' . $pixel_id . '&ev=PageView&noscript=1'); ?>"></noscript>
        <?php
    }
}
add_action('wp_head', 'south_city_output_tracking', 5);
