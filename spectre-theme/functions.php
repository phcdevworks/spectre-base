<?php
if (!defined("ABSPATH")) exit;

function spectre_base_setup() {
    load_theme_textdomain("spectre-base", get_template_directory() . "/languages");

    add_theme_support("title-tag");
    add_theme_support("post-thumbnails");
    add_theme_support("html5", array("search-form", "comment-form", "comment-list", "gallery", "caption"));
    add_theme_support("custom-logo");
    add_theme_support("editor-styles");
    add_theme_support("align-wide");
    add_theme_support("editor-color-palette");
    add_theme_support("editor-font-sizes");
    add_theme_support("responsive-embeds");

    $menus = array("primary" => __("Primary Menu", "spectre-base"));
    foreach (spectre_base_footer_menu_locations() as $location => $footer_menu) {
        $menus[$location] = $footer_menu["label"];
    }
    register_nav_menus($menus);
}
add_action("after_setup_theme", "spectre_base_setup");

const SPECTRE_BASE_FOOTER_MENU_MAX = 4;
const SPECTRE_BASE_FOOTER_MENU_DEFAULT = 3;

function spectre_base_sanitize_footer_menu_count($value) {
    return max(1, min(SPECTRE_BASE_FOOTER_MENU_MAX, absint($value)));
}

// The footer menu locations enabled by the Customizer's "Footer menus"
// setting (Appearance > Customize > Footer), in column order. Only these are
// registered, so Appearance > Menus lists exactly as many footer locations as
// the site uses. Slugs and args filters are stable per position, so lowering
// the count and raising it again restores the earlier menu assignments.
function spectre_base_footer_menu_locations() {
    $all = array(
        "footer"            => "spectre_base_footer_nav_args",
        "footer-secondary"  => "spectre_base_footer_secondary_nav_args",
        "footer-tertiary"   => "spectre_base_footer_tertiary_nav_args",
        "footer-quaternary" => "spectre_base_footer_quaternary_nav_args",
    );
    $count = spectre_base_sanitize_footer_menu_count(
        get_theme_mod("spectre_base_footer_menu_count", SPECTRE_BASE_FOOTER_MENU_DEFAULT)
    );

    $locations = array();
    $position  = 1;
    foreach (array_slice($all, 0, $count, true) as $location => $args_filter) {
        $locations[$location] = array(
            /* translators: %d: footer menu column number. */
            "label"       => sprintf(__("Footer Menu %d", "spectre-base"), $position++),
            "args_filter" => $args_filter,
        );
    }
    return $locations;
}

const SPECTRE_BASE_COLOR_MODES = array("light", "dark", "system");
const SPECTRE_BASE_COLOR_MODE_DEFAULT = "light";

function spectre_base_sanitize_color_mode($value) {
    return in_array($value, SPECTRE_BASE_COLOR_MODES, true) ? $value : SPECTRE_BASE_COLOR_MODE_DEFAULT;
}

// The site-wide color mode from Appearance > Customize > Color Mode. Spectre's
// dark tokens (and every spectre-ui component variable mapped from them) apply
// under `:root[data-spectre-theme="dark"]`, so the mode only has to reach the
// `<html>` element: server-side for light/dark, and via a tiny pre-paint
// script for system, which follows the visitor's OS setting live.
function spectre_base_color_mode() {
    return spectre_base_sanitize_color_mode(
        get_theme_mod("spectre_base_color_mode", SPECTRE_BASE_COLOR_MODE_DEFAULT)
    );
}

function spectre_base_color_mode_html_attribute($output) {
    $mode = spectre_base_color_mode();
    if (is_admin() || "system" === $mode) {
        return $output;
    }
    return $output . ' data-spectre-theme="' . esc_attr($mode) . '"';
}
add_filter("language_attributes", "spectre_base_color_mode_html_attribute");

function spectre_base_color_mode_head() {
    $mode = spectre_base_color_mode();
    // Lets the browser match native UI (form controls, scrollbars) to the mode.
    echo '<meta name="color-scheme" content="' . esc_attr("system" === $mode ? "light dark" : $mode) . '">' . "\n";

    if ("system" === $mode) {
        // Runs before any stylesheet prints (wp_head priority 1), so there is
        // no flash of the wrong mode.
        wp_print_inline_script_tag(
            "(function(){var r=document.documentElement,q=window.matchMedia('(prefers-color-scheme: dark)');"
            . "function a(){r.setAttribute('data-spectre-theme',q.matches?'dark':'light')}"
            . "a();q.addEventListener('change',a)})();"
        );
    }
}
add_action("wp_head", "spectre_base_color_mode_head", 1);

function spectre_base_customize_register($wp_customize) {
    $wp_customize->add_section("spectre_base_color_mode", array(
        "title"    => __("Color Mode", "spectre-base"),
        "priority" => 40,
    ));

    $wp_customize->add_setting("spectre_base_color_mode", array(
        "default"           => SPECTRE_BASE_COLOR_MODE_DEFAULT,
        "sanitize_callback" => "spectre_base_sanitize_color_mode",
    ));

    $wp_customize->add_control("spectre_base_color_mode", array(
        "label"       => __("Color mode", "spectre-base"),
        "description" => __("Applies to the whole site. System follows each visitor's device setting and switches automatically when it changes.", "spectre-base"),
        "section"     => "spectre_base_color_mode",
        "type"        => "radio",
        "choices"     => array(
            "light"  => __("Light", "spectre-base"),
            "dark"   => __("Dark", "spectre-base"),
            "system" => __("System (match the visitor's device)", "spectre-base"),
        ),
    ));

    $wp_customize->add_section("spectre_base_footer", array(
        "title"    => __("Footer", "spectre-base"),
        "priority" => 120,
    ));

    $wp_customize->add_setting("spectre_base_footer_menu_count", array(
        "default"           => SPECTRE_BASE_FOOTER_MENU_DEFAULT,
        "sanitize_callback" => "spectre_base_sanitize_footer_menu_count",
    ));

    $choices = array();
    for ($i = 1; $i <= SPECTRE_BASE_FOOTER_MENU_MAX; $i++) {
        $choices[$i] = (string) $i;
    }

    $wp_customize->add_control("spectre_base_footer_menu_count", array(
        "label"       => __("Footer menus", "spectre-base"),
        "description" => __("How many footer menu columns to show. Assign a menu to each one under Appearance > Menus; columns without a menu are hidden. Save and reload to update the menu locations list.", "spectre-base"),
        "section"     => "spectre_base_footer",
        "type"        => "select",
        "choices"     => $choices,
    ));
}
add_action("customize_register", "spectre_base_customize_register");

function spectre_base_widgets_init() {
    register_sidebar(array(
        "name"          => __("Main Sidebar", "spectre-base"),
        "id"            => "sidebar-main",
        "description"   => __("Widgets in this area appear in the sidebar.", "spectre-base"),
        "before_widget" => '<section id="%1$s" class="widget %2$s">',
        "after_widget"  => "</section>",
        "before_title"  => '<sp-text level="h3">',
        "after_title"   => "</sp-text>",
    ));
}
add_action("widgets_init", "spectre_base_widgets_init");

// Full-viewport page shell: with `<html class="sp-h-full">` (header.php), the
// body is at least the viewport tall and stacks header, main, and footer as a
// flex column; the footer's `sp-mt-auto` then pins it to the bottom on short
// or empty pages.
function spectre_base_body_class($classes) {
    return array_merge($classes, array("sp-min-h-full", "sp-flex", "sp-flex-col"));
}
add_filter("body_class", "spectre_base_body_class");

function spectre_base_primary_menu_fallback($args) {
    if (empty($args["theme_location"]) || "primary" !== $args["theme_location"]) {
        return;
    }

    // No menu assigned yet: list the site's pages with the same nav recipe
    // classes the assigned menu gets.
    $link_class = function ($atts, $page, $depth, $args, $current_page_id) {
        $classes = "sp-nav__link";
        if ((int) $page->ID === (int) $current_page_id) {
            $classes              .= " sp-nav__link--active";
            $atts["aria-current"]  = "page";
        }
        $atts["class"] = trim((isset($atts["class"]) ? $atts["class"] . " " : "") . $classes);
        return $atts;
    };
    add_filter("page_menu_link_attributes", $link_class, 10, 5);
    wp_page_menu(array(
        "container" => false,
        "depth"     => 1,
        // The site title already links home, and core's generated Home link
        // bypasses page_menu_link_attributes, so it can't take the recipe class.
        "show_home" => false,
        "before"    => '<ul class="sp-nav__links sp-mx-0 sp-flex-wrap">',
        "after"     => "</ul>",
    ));
    remove_filter("page_menu_link_attributes", $link_class, 10);
}

const SPECTRE_BASE_HEADER_LAYOUTS = array("inline", "edge-fluid-edge");

// `inline` (default) is a wrapping horizontal stack. `edge-fluid-edge` is a
// three-region grid -- branding, nav, and the header actions -- for headers
// with a CTA.
function spectre_base_header_layout() {
    $layout = apply_filters("spectre_base_header_layout", "inline");
    return in_array($layout, SPECTRE_BASE_HEADER_LAYOUTS, true) ? $layout : "inline";
}

// Utility classes for the header's `sp-container` inner element. `sp-relative`
// makes the container, not the full-width `sp-nav`, the positioned ancestor
// that `<sp-dropdown mega>` panels anchor to.
function spectre_base_header_container_class() {
    return trim((string) apply_filters("spectre_base_header_container_class", ""));
}

// Returning a markup string from either filter replaces the region's default
// contents; the wrapper (and the branding action hooks) stay. Callbacks must
// return escaped markup.
function spectre_base_site_branding() {
    ?>
    <div class="site-branding">
        <?php do_action("spectre_base_before_site_branding"); ?>
        <?php
        $branding = apply_filters("spectre_base_site_branding", null);
        if (null !== $branding) :
            echo $branding;
        elseif (has_custom_logo()) :
            the_custom_logo();
        else :
            ?>
            <a class="sp-nav__link sp-heading--h6" href="<?php echo esc_url(home_url("/")); ?>" rel="home">
                <?php echo esc_html(get_bloginfo("name")); ?>
            </a>
        <?php endif; ?>
        <?php do_action("spectre_base_after_site_branding"); ?>
    </div>
    <?php
}

function spectre_base_primary_nav() {
    ?>
    <div class="main-navigation">
        <?php
        $nav = apply_filters("spectre_base_primary_nav", null);
        if (null !== $nav) {
            echo $nav;
        } else {
            wp_nav_menu(apply_filters("spectre_base_primary_nav_args", array(
                "theme_location"     => "primary",
                "container"          => false,
                "depth"              => 1,
                "items_wrap"         => '<ul class="sp-nav__links sp-mx-0 sp-flex-wrap">%3$s</ul>',
                "spectre_link_class" => "sp-nav__link",
                "fallback_cb"        => "spectre_base_primary_menu_fallback",
            )));
        }
        ?>
    </div>
    <?php
}

const SPECTRE_BASE_CASCADE_LAYER_ORDER = "@layer theme, base, wp-global-styles, components, utilities;";

function spectre_base_register_cascade_layers() {
    // Establishes the shell's cascade-layer order via a src-less style
    // handle that is always enqueued, independent of the Vite manifest and
    // dev/prod asset mode (spectre-base-style is never enqueued in dev --
    // see spectre_base_enqueue_assets() below). `wp-global-styles` is the
    // shared layer name WordPress's own compiled global styles (see
    // spectre_base_layer_global_styles()) and any child theme's custom
    // shell-level CSS (see README.md "Child Themes") should opt into.
    // CSS cascade layer order is scoped to the whole document, not to
    // whichever stylesheet first mentions a name, so establishing it here
    // guarantees the order even on a request where core has no
    // global-styles content to wrap -- spectre_base_layer_global_styles()
    // would then have nothing to print and, on its own, would never
    // establish the order at all, leaving `wp-global-styles` usage
    // elsewhere (e.g. a child theme's style.css) unordered relative to
    // `components`/`utilities`.
    wp_register_style("spectre-base-cascade-layers", false);
    wp_enqueue_style("spectre-base-cascade-layers");
    wp_add_inline_style("spectre-base-cascade-layers", SPECTRE_BASE_CASCADE_LAYER_ORDER);
}
add_action("wp_enqueue_scripts", "spectre_base_register_cascade_layers", 5);

function spectre_base_enqueue_assets() {
    $is_dev = function_exists("wp_get_environment_type")
        ? wp_get_environment_type() === "development"
        : (defined("WP_ENV") && WP_ENV === "development");
    $vite_server = defined("VITE_DEV_SERVER") ? rtrim(VITE_DEV_SERVER, "/") : "http://localhost:5173";

    if ($is_dev) {
        // Development mode loads the single theme entry from Vite. CSS arrives through the JS import.
        wp_enqueue_script(
            "vite-client",
            $vite_server . "/@vite/client",
            array(),
            null,
            false
        );
        wp_script_add_data("vite-client", "type", "module");

        wp_enqueue_script(
            "spectre-base-main",
            $vite_server . "/src/js/main.ts",
            array("vite-client"),
            null,
            true
        );
        wp_script_add_data("spectre-base-main", "type", "module");

        return;
    }

    $manifest_path = get_template_directory() . "/dist/.vite/manifest.json";
    if (!file_exists($manifest_path)) {
        if (defined("WP_DEBUG") && WP_DEBUG) {
            error_log("Vite manifest not found: " . $manifest_path);
        }
        return;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);
    if (!is_array($manifest)) {
        if (defined("WP_DEBUG") && WP_DEBUG) {
            error_log("Invalid Vite manifest JSON: " . $manifest_path);
        }
        return;
    }

    $main_entry = $manifest["src/js/main.ts"] ?? null;

    if (!$main_entry || empty($main_entry["file"])) {
        if (defined("WP_DEBUG") && WP_DEBUG) {
            error_log("Main Vite entry not found in manifest: src/js/main.ts");
        }
        return;
    }

    if (!empty($main_entry["css"]) && is_array($main_entry["css"])) {
        wp_enqueue_style(
            "spectre-base-style",
            get_template_directory_uri() . "/dist/" . $main_entry["css"][0],
            array(),
            null
        );
    }

    wp_enqueue_script(
        "spectre-base-main",
        get_template_directory_uri() . "/dist/" . $main_entry["file"],
        array(),
        null,
        true
    );
    wp_script_add_data("spectre-base-main", "type", "module");
}
add_action("wp_enqueue_scripts", "spectre_base_enqueue_assets");

function spectre_base_add_editor_styles() {
    $manifest_path = get_template_directory() . "/dist/.vite/manifest.json";
    if (!file_exists($manifest_path)) {
        return;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);
    if (!is_array($manifest)) {
        return;
    }

    $main_entry = $manifest["src/js/main.ts"] ?? null;
    if (!$main_entry || empty($main_entry["css"]) || !is_array($main_entry["css"])) {
        return;
    }

    // Theme-relative path, not a URL: the block editor fetches URL editor
    // styles server-side via wp_remote_get(), which fails whenever the site
    // can't reach its own public URL (e.g. Docker port mapping), silently
    // dropping every --sp-* token from the editor canvas.
    add_editor_style("dist/" . $main_entry["css"][0]);
}
add_action("admin_init", "spectre_base_add_editor_styles");

function spectre_base_layer_global_styles($handle = "global-styles") {
    // WordPress core's `global-styles-inline-css` output (theme.json's
    // `styles.elements.h1`-`h6` etc.) compiles to plain, unlayered
    // selectors. Per the CSS cascade-layers spec, unlayered rules always
    // beat layered rules regardless of specificity or source order, so raw
    // theme.json heading defaults permanently override spectre-ui's own
    // `@layer components`/`@layer utilities` size recipes (e.g. the classes
    // an `<sp-text level="h1" size="*">` requests). Wrapping this output in
    // a named layer, and explicitly ordering that layer below
    // `components`/`utilities`, restores the intended precedence: an
    // explicit `sp-text` size recipe wins, while raw editor-content
    // headings with no competing layered rule still fall through to this
    // layer and keep the theme.json default scale. The order is also
    // established unconditionally by spectre_base_register_cascade_layers()
    // above, so a request with no global-styles content to wrap here still
    // leaves `wp-global-styles` correctly ordered for any other consumer of
    // that layer name (e.g. a child theme's own shell-level CSS).
    //
    // WordPress core has no filter on `wp_get_global_stylesheet()`'s return
    // value or on the printed inline `<style id='global-styles-inline-css'>`
    // tag -- verified against wordpress-develop trunk and the bundled 6.7
    // core: `wp_get_global_stylesheet()` returns unfiltered, and
    // `WP_Styles::do_item()` echoes the inline style tag for a `src=false`
    // handle -- like `global-styles` -- without ever calling the
    // `style_loader_tag` filter, which only fires for the `<link href>`
    // branch. So this post-processes the already-enqueued inline CSS via
    // the public `WP_Styles` data API instead of a nonexistent filter.
    // `wp_enqueue_global_styles()` (core) registers the `global-styles`
    // handle and calls `wp_add_inline_style()` either on `wp_enqueue_scripts`
    // directly (classic core, and block themes), or -- as of WP 6.9's
    // "load block assets on demand" default for classic themes like this one
    // -- only a placeholder handle on `wp_enqueue_scripts`, with the real
    // `global-styles` handle registered on `wp_footer` priority 1 instead
    // (later hoisted into `<head>` by core's own `wp_hoist_late_printed_styles()`,
    // which reads the same live "after" data this rewrites). Hooking both
    // points, one priority step after either, and guarding with the
    // "already wrapped" check above covers every core version/config; the
    // guard also makes it safe if both hooks happen to find real content.
    //
    // Both `add_action()` calls below pass `$accepted_args = 0` on purpose:
    // WP core's `do_action( 'tag' )` with no extra arguments still calls
    // every hooked callback with one argument, an empty string (see
    // `do_action()`'s `if ( empty( $arg ) ) { $arg[] = ''; }`), which would
    // silently override the `$handle` default above with `''` and break
    // every `$styles->query( $handle, ... )` lookup. Confirmed by testing
    // directly against a live WordPress 7.0 install: omitting
    // `$accepted_args` reproduced exactly that silent failure.
    $styles = wp_styles();
    if (!($styles instanceof WP_Styles) || !$styles->query($handle, "registered")) {
        return;
    }

    $after = $styles->get_data($handle, "after");
    if (empty($after)) {
        return;
    }

    $css = implode("\n", (array) $after);
    if (str_starts_with(ltrim($css), SPECTRE_BASE_CASCADE_LAYER_ORDER)) {
        return;
    }

    // Restating the same order here is redundant with
    // spectre_base_register_cascade_layers() but harmless -- repeating an
    // already-established layer order is a no-op per the cascade-layers
    // spec -- and keeps this function correct standing alone.
    $wrapped = SPECTRE_BASE_CASCADE_LAYER_ORDER . "\n"
        . "@layer wp-global-styles {\n" . $css . "\n}";

    $styles->add_data($handle, "after", array($wrapped));
}
add_action("wp_enqueue_scripts", "spectre_base_layer_global_styles", 20, 0);
add_action("wp_footer", "spectre_base_layer_global_styles", 2, 0);

function spectre_base_has_icons() {
    return shortcode_exists("spectre-icon") || has_filter("spectre_base_icon");
}

// Icon markup for `$name`, or "" when no provider has the icon. The
// spectre-icons plugin's shortcode is the default provider; the
// `spectre_base_icon` filter lets a child theme supply its own markup (for
// example a bundled inline SVG) with or without the plugin. Filter callbacks
// must return escaped markup.
function spectre_base_icon($name, $size) {
    $markup = shortcode_exists("spectre-icon")
        ? do_shortcode('[spectre-icon name="' . esc_attr($name) . '" size="' . esc_attr($size) . '"]')
        : "";
    return (string) apply_filters("spectre_base_icon", $markup, $name, $size);
}

const SPECTRE_BASE_FOOTER_SURFACES = array("page", "card", "subtle", "inverse", "hero");
const SPECTRE_BASE_FOOTER_APPEARANCES = array("dark", "light", "system");

// The `sp-footer` surface and appearance attributes, each printed only when
// its filter returns an allowed value, so the component default (the dark
// footer palette) applies otherwise.
function spectre_base_footer_surface_attributes() {
    $attributes = "";
    $options    = array(
        "surface"    => array("spectre_base_footer_surface", SPECTRE_BASE_FOOTER_SURFACES),
        "appearance" => array("spectre_base_footer_appearance", SPECTRE_BASE_FOOTER_APPEARANCES),
    );
    foreach ($options as $attribute => list($filter, $allowed)) {
        $value = apply_filters($filter, "");
        if (in_array($value, $allowed, true)) {
            $attributes .= " " . $attribute . '="' . esc_attr($value) . '"';
        }
    }
    return $attributes;
}

// Adds a Spectre link recipe class (the `spectre_link_class` wp_nav_menu()
// arg, e.g. `sp-nav__link` or `sp-footer__link`) to every menu link, plus its
// `--active` modifier on the current page's link. WordPress already sets
// aria-current="page" on that link.
function spectre_base_nav_link_attributes($atts, $item, $args) {
    if (empty($args->spectre_link_class)) {
        return $atts;
    }
    $classes = $args->spectre_link_class;
    if (!empty($item->current)) {
        $classes .= " " . $args->spectre_link_class . "--active";
    }
    $existing      = isset($atts["class"]) ? $atts["class"] . " " : "";
    $atts["class"] = trim($existing . $classes);
    return $atts;
}
add_filter("nav_menu_link_attributes", "spectre_base_nav_link_attributes", 10, 3);

// Renders one `theme_location` as a footer column: a non-linked
// `.sp-footer__heading` label followed by its top-level menu items as a
// `.sp-footer__links` list. Returns silently (renders nothing) if no menu
// is assigned to `$location` -- same graceful-degradation pattern as the
// social icons and contact info columns below.
// A footer column's heading is the name of the menu assigned to its location
// in Appearance > Menus, so site content never ships with theme-authored
// labels. Empty when no menu is assigned.
function spectre_base_footer_menu_name($location) {
    $locations = get_nav_menu_locations();
    if (empty($locations[$location])) {
        return "";
    }
    $menu = wp_get_nav_menu_object($locations[$location]);
    return $menu ? $menu->name : "";
}

function spectre_base_footer_nav_column($location, $heading, $args_filter) {
    if (!has_nav_menu($location)) {
        return;
    }
    ?>
    <sp-stack class="spectre-footer-column" gap="sm" align="stretch">
        <?php if ($heading !== "") : ?>
            <span class="sp-footer__heading" id="<?php echo esc_attr("spectre-footer-heading-{$location}"); ?>"><?php echo esc_html($heading); ?></span>
            <nav aria-labelledby="<?php echo esc_attr("spectre-footer-heading-{$location}"); ?>">
        <?php else : ?>
            <nav aria-label="<?php echo esc_attr(get_registered_nav_menus()[$location] ?? $location); ?>">
        <?php endif; ?>
            <?php
            wp_nav_menu(apply_filters($args_filter, array(
                "theme_location"            => $location,
                "container"                 => false,
                "depth"                     => 1,
                "items_wrap"                => '<ul class="sp-footer__links">%3$s</ul>',
                "spectre_link_class"        => "sp-footer__link",
            )));
            ?>
        </nav>
    </sp-stack>
    <?php
}

// Renders the `spectre_base_footer_social_icons` filter entries -- each entry:
// ['name' => 'github', 'size' => '20' (optional), 'url' => optional, 'label' => optional].
// Linked icons stay server-rendered `<a class="sp-footer__chip">` so they work
// before scripts load; unlinked icons use the `<sp-footer-chip>` component.
// `label` is the accessible name for an icon-only link and falls back to the
// icon name. An entry whose icon no provider renders (see spectre_base_icon())
// is skipped, since the icon is the chip's only content.
function spectre_base_footer_social_icons() {
    $chips = array();
    foreach (apply_filters("spectre_base_footer_social_icons", array()) as $icon) {
        $name = isset($icon["name"]) ? $icon["name"] : "";
        if ($name === "") {
            continue;
        }
        $glyph = spectre_base_icon($name, isset($icon["size"]) ? $icon["size"] : "20");
        if ($glyph === "") {
            continue;
        }
        $chips[] = array(
            "glyph" => $glyph,
            "url"   => isset($icon["url"])   ? $icon["url"]   : "",
            "label" => isset($icon["label"]) ? $icon["label"] : ucfirst($name),
        );
    }
    if (empty($chips)) {
        return;
    }
    ?>
    <sp-stack direction="horizontal" align="stretch" gap="sm" inner-class="sp-flex-wrap" aria-label="<?php esc_attr_e("Social links", "spectre-base"); ?>">
        <?php foreach ($chips as $chip) :
            $url   = $chip["url"];
            $label = $chip["label"];
            $glyph = $chip["glyph"];
        ?>
            <?php if ($url) : ?>
                <a class="sp-footer__chip" href="<?php echo esc_url($url); ?>" aria-label="<?php echo esc_attr($label); ?>" target="_blank" rel="noopener noreferrer"><?php echo $glyph; ?></a>
            <?php else : ?>
                <sp-footer-chip aria-label="<?php echo esc_attr($label); ?>"><?php echo $glyph; ?></sp-footer-chip>
            <?php endif; ?>
        <?php endforeach; ?>
    </sp-stack>
    <?php
}

// Renders the copyright bar's trailing links from the
// `spectre_base_footer_legal_links` filter -- each entry: ['text' => '...', 'url' => '...'].
// Defaults to the WordPress privacy policy page (Settings > Privacy) when one
// is configured. The current page's link gets the active recipe state.
function spectre_base_footer_legal_links() {
    $links       = array();
    $privacy_url = get_privacy_policy_url();
    if ($privacy_url) {
        $links[] = array("text" => __("Privacy Policy", "spectre-base"), "url" => $privacy_url);
    }
    $links = apply_filters("spectre_base_footer_legal_links", $links);
    if (empty($links)) {
        return;
    }

    global $wp;
    $current_url = untrailingslashit(home_url($wp->request ?? ""));
    ?>
    <nav aria-label="<?php esc_attr_e("Legal", "spectre-base"); ?>">
        <sp-stack direction="horizontal" align="stretch" gap="md" inner-class="sp-flex-wrap">
            <?php foreach ($links as $link) :
                $text = isset($link["text"]) ? $link["text"] : "";
                $url  = isset($link["url"])  ? $link["url"]  : "";
                if ($text === "" || $url === "") {
                    continue;
                }
                $is_current = untrailingslashit($url) === $current_url;
            ?>
                <a class="sp-footer__link<?php echo $is_current ? " sp-footer__link--active" : ""; ?>" href="<?php echo esc_url($url); ?>"<?php echo $is_current ? ' aria-current="page"' : ""; ?>><?php echo esc_html($text); ?></a>
            <?php endforeach; ?>
        </sp-stack>
    </nav>
    <?php
}

// Renders the footer contact info from the `spectre_base_footer_contact_items`
// filter -- each entry: ['icon' => 'map-pin' (optional), 'text' => '...', 'url' => optional].
// Icons render only when a provider has them (see spectre_base_icon()); the
// text itself always renders, unlike a social icon (which has no content
// besides the icon and so is skipped without one).
// Renders no heading/wrapper of its own -- intended to sit inside the brand
// column alongside the tagline and social icons, not as its own grid column.
function spectre_base_footer_contact_info() {
    $items = apply_filters("spectre_base_footer_contact_items", array());
    if (empty($items)) {
        return;
    }
    ?>
    <ul class="sp-footer__links">
        <?php foreach ($items as $item) :
            $text = isset($item["text"]) ? $item["text"] : "";
            if ($text === "") {
                continue;
            }
            $icon_name = isset($item["icon"]) ? $item["icon"] : "";
            $url       = isset($item["url"])  ? $item["url"]  : "";
        ?>
            <li>
                <?php if ($url) : ?>
                    <a class="sp-footer__link" href="<?php echo esc_url($url); ?>">
                <?php else : ?>
                    <span class="sp-footer__link">
                <?php endif; ?>
                    <?php if ($icon_name) :
                        echo spectre_base_icon($icon_name, "16");
                    endif; ?>
                    <?php echo esc_html($text); ?>
                <?php echo $url ? "</a>" : "</span>"; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
}
