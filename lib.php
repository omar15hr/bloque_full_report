<?php

function block_full_report_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    return false; // Evita el manejo de archivos.
}

function block_full_report_extend_navigation(navigation_node $parentnode, stdClass $user, stdClass $course, context_course $context) {
    global $PAGE;

    // Añadir el archivo CSS.
    $PAGE->requires->css('/blocks/full_report/styles.css');
}
