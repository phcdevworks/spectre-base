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
        "before_title"  => '<h3>',
        "after_title"   => "</h3>",
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

function spectre_base_has_icons() {
    return shortcode_exists("spectre-icon");
}
