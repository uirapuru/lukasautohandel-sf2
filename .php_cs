<?php

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers([
        'short_array_syntax',
        'ordered_use',
        'no_blank_lines_before_namespace',
        'newline_after_open_tag',
        'multiline_spaces_before_semicolon',
        'align_equals',
        'align_double_arrow',
    ])
    ->setUsingCache(true)
    ;