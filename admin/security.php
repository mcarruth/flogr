<?php
/**
 * Security helper functions for Flogr
 *
 * @author Mike Carruth <mikecarruth@gmail.com>
 * @version 2.5.7
 * @package Flogr
 */

/**
 * Sanitize HTML output to prevent XSS attacks
 */
function sanitize_output($data) {
    if (is_array($data)) {
        return array_map('sanitize_output', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate and sanitize integer input
 */
function validate_int($input, $default = null) {
    if ($input === null || $input === '') {
        return $default;
    }
    return filter_var($input, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) ?: $default;
}

/**
 * Validate and sanitize string input
 */
function validate_string($input, $default = '', $max_length = 255) {
    if ($input === null || $input === '') {
        return $default;
    }
    // Use htmlspecialchars instead of deprecated FILTER_SANITIZE_STRING
    $sanitized = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return substr($sanitized, 0, $max_length);
}

/**
 * Validate Flickr ID format (numbers and @N suffix)
 */
function validate_flickr_id($input) {
    if ($input === null || $input === '') {
        return null;
    }
    // Flickr IDs are typically numbers with optional @N suffix
    if (preg_match('/^[0-9]+(@N[0-9]+)?$/', $input)) {
        return $input;
    }
    return null;
}

/**
 * Validate tag string (alphanumeric, spaces, commas, hyphens, underscores)
 */
function validate_tags($input) {
    if ($input === null || $input === '') {
        return null;
    }
    // Allow alphanumeric, spaces, commas, hyphens, underscores
    if (preg_match('/^[a-zA-Z0-9\s,_-]+$/', $input)) {
        return $input;
    }
    return null;
}

/**
 * Validate photo size parameter
 */
function validate_size($input, $default = 'thumbnail') {
    if ($input === null || $input === '') {
        return $default;
    }
    $valid_sizes = ['square', 'thumbnail', 'small', 'medium', 'medium640', 'large', 'original'];
    $input_lower = strtolower($input);
    return in_array($input_lower, $valid_sizes) ? $input_lower : $default;
}

/**
 * Validate sort parameter
 */
function validate_sort($input, $default = '') {
    $valid_sorts = ['date-posted-asc', 'date-posted-desc', 'date-taken-asc', 'date-taken-desc',
                    'interestingness-desc', 'interestingness-asc', 'relevance'];
    return in_array($input, $valid_sorts) ? $input : $default;
}

/**
 * Set security headers
 */
function set_security_headers() {
    // Only set headers if they haven't been sent yet
    if (!headers_sent()) {
        // Prevent clickjacking
        header('X-Frame-Options: SAMEORIGIN');

        // Enable XSS protection
        header('X-XSS-Protection: 1; mode=block');

        // Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff');

        // Referrer policy
        header('Referrer-Policy: strict-origin-when-cross-origin');

        // Content Security Policy (relaxed for external resources like Flickr)
        header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' *.flickr.com *.staticflickr.com maps.googleapis.com; img-src * data:; font-src 'self' data:;");
    }
}

/**
 * Safe wrapper for Flickr API calls with error handling
 */
function safe_flickr_call($callback, $default = null) {
    try {
        $result = $callback();
        if ($result === false || (is_array($result) && isset($result['stat']) && $result['stat'] === 'fail')) {
            error_log('Flickr API call failed: ' . print_r($result, true));
            return $default;
        }
        return $result;
    } catch (Exception $e) {
        error_log('Flickr API exception: ' . $e->getMessage());
        return $default;
    }
}