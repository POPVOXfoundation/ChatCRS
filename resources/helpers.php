<?php

function array_unique_multidimensional($input)
{
    $serialized = array_map('serialize', $input);
    $unique = array_unique($serialized);
    return array_intersect_key($input, $unique);
}
function nl2p($text) {
    $paragraphs = '';

    foreach (explode("\n", $text) as $line) {
        if (trim($line)) {
            $paragraphs .= '<p class="m-3">' . htmlspecialchars($line) . '</p>';
        }
    }

    return $paragraphs;
}
