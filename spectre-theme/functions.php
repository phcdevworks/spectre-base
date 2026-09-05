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

    register_nav_menus(array(
        "primary" => __("Primary Menu", "spectre-base"),
        "footer"  => __("Footer Menu", "spectre-base"),
    ));
}
add_action("after_setup_theme", "spectre_base_setup");

function spectre_base_widgets_init() {
    register_sidebar(array(
        "name"          => __("Main Sidebar", "spectre-base"),
        "id"            => "sidebar-main",
        "description"   => __("Widgets in this area appear in the sidebar.", "spectre-base"),
        "before_widget" => '<sp-card id="%1$s" class="widget %2$s" padded>',
        "after_widget"  => "</sp-card>",
        "before_title"  => '<sp-text level="h3">',
        "after_title"   => "</sp-text>",
    ));
}
add_action("widgets_init", "spectre_base_widgets_init");

function spectre_base_primary_menu_fallback($args) {
    if (empty($args["theme_location"]) || "primary" !== $args["theme_location"]) {
        return;
    }

    wp_page_menu(array(
        "container" => false,
        "show_home" => true,
    ));
}

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

    add_editor_style(get_template_directory_uri() . "/dist/" . $main_entry["css"][0]);
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
    // layer and keep the theme.json default scale.
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
    if (str_starts_with(ltrim($css), "@layer theme, base, wp-global-styles, components, utilities;")) {
        return;
    }

    // Both this output and main.css establish the same order before any
    // layer rules. Later declarations cannot reorder existing layers.
    $wrapped = "@layer theme, base, wp-global-styles, components, utilities;\n"
        . "@layer wp-global-styles {\n" . $css . "\n}";

    $styles->add_data($handle, "after", array($wrapped));
}
add_action("wp_enqueue_scripts", "spectre_base_layer_global_styles", 20, 0);
add_action("wp_footer", "spectre_base_layer_global_styles", 2, 0);

function spectre_base_has_icons() {
    return shortcode_exists("spectre-icon");
}
