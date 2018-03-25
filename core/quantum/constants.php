<?php

/**
 * Quantum PHP Framework
 * 
 * An open source software development framework for PHP
 * 
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 1.0.0
 */

const DS = DIRECTORY_SEPARATOR;

/**
 * Base directory of framework.
 */
const BASE_DIR = __DIR__ . DS . '..' . DS . '..' . DS;

/**
 * Vendor directory.
 */
const VENDOR_DIR = BASE_DIR . 'vendor';

/**
 * Framework Core directory.
 */
const CORE_DIR = BASE_DIR . 'core';

/**
 * Modules directory.
 */
const MODULES_DIR = BASE_DIR . 'modules';

/**
 * Helpers directory.
 */
const HELPERS_DIR = CORE_DIR . DS . 'quantum' . DS . 'Helpers';

/**
 * Libraries directory.
 */
const LIBRARIES_DIR = CORE_DIR . DS . 'quantum' . DS . 'Libraries';

/**
 * Public directory.
 */
const PUBLIC_DIR = BASE_DIR . 'public';

/**
 * Upload directory.
 */
const UPLOADS_DIR = PUBLIC_DIR . DS . 'uploads';