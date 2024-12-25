<?php

use App\Fixer\ClassNotation\CustomControllerOrderFixer;
use App\Fixer\ClassNotation\CustomOrderedClassElementsFixer;
use App\Fixer\ClassNotation\CustomPhpUnitOrderFixer;
use App\Support\PhpCsFixer;
use PhpCsFixer\Config;

return (new Config())
    ->setFinder(PhpCsFixer::getFinder())
    ->setUsingCache(false)
    ->registerCustomFixers([
        new CustomControllerOrderFixer(),
        new CustomOrderedClassElementsFixer(),
        new CustomPhpUnitOrderFixer(),
    ])
    ->setRules([
        'Tighten/custom_controller_order' => true,
        'Tighten/custom_ordered_class_elements' => true,
        'Tighten/custom_phpunit_order' => true,
        '@PSR2' => true,
        'array_push' => true,
        'array_indentation' => true,
        'backtick_to_shell_exec' => true,
        'declare_strict_types' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'no_useless_else' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'case',
                'constant',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
                'method_abstract',
                'method_public_static',
                'method_public',
                'method_protected_static',
                'method_protected',
                'method_private_static',
                'method_private'
            ],
            'sort_algorithm' => 'none',
        ],
        'curly_braces_position' => [
            'control_structures_opening_brace' => 'same_line',
            'functions_opening_brace' => 'next_line_unless_newline_at_signature_end',
            'anonymous_functions_opening_brace' => 'same_line',
            'classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
            'anonymous_classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
            'allow_single_line_empty_anonymous_classes' => true,
            'allow_single_line_anonymous_functions' => false,
        ],
        'logical_operators' => true,
        'mb_str_functions' => true,
        'modernize_strpos' => true,
        'ordered_interfaces' => true,
        'ordered_traits' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
        ],
        'unary_operator_spaces' => true,
        'not_operator_with_successor_space' => true,
        'single_line_empty_body' => true,
        'no_unused_imports' => true,
        'strict_comparison' => true,
    ]);
