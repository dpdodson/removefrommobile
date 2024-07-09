/*.  REMOVES ITEMS FROM DOM.  */

function filter_content_for_mobile($content) {
    if (wp_is_mobile()) {
        // Use DOMDocument to parse and modify the HTML content
        $dom = new DOMDocument();
        // Suppress errors due to malformed HTML
        libxml_use_internal_errors(true);
        $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Find elements with the class 'removefrommobile'
        $xpath = new DOMXPath($dom);
        $elements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' removefrommobile ')]");

        foreach ($elements as $element) {
            $element->parentNode->removeChild($element);
        }

        // Return the modified HTML
        $content = $dom->saveHTML();
    }
    return $content;
}

function start_buffering_for_mobile() {
    if (wp_is_mobile()) {
        ob_start('filter_content_for_mobile');
    }
}

function end_buffering_for_mobile() {
    if (wp_is_mobile()) {
        ob_end_flush();
    }
}

add_action('template_redirect', 'start_buffering_for_mobile');
add_action('shutdown', 'end_buffering_for_mobile');

