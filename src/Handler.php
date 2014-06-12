<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file.
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright (c) 2014, Achmad F. Ibrahim
 * @link https://github.com/Achsoft
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 */

namespace Achsoft\Component\ErrorHandler;

/**
 * Handle all Php errors as exceptions.
 * 
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 * @package Achsoft\Component\ErrorHandler
 * @version 0.1
 * @since 0.1
 */
class Handler
{
    /**
     * Variable to store default exception class name.
     * 
     * @var string
     * @since 0.1
     */
    private $exception = '\Achsoft\Component\ErrorHandler\ErrorException';
    
    /**
     * Constructor.
     * 
     * @param string $class Exception class name
     * @since 0.1
     */
    public function __construct($class = null)
    {
        is_null($class)
            ? $this->setException($this->exception)
            : $this->setException($class);
    }
    
    /**
     * Exception error handler.
     * 
     * @since 0.1
     */
    public function handler($errno, $errstr, $errfile, $errline)
    {
        $exception = $this->exception;
        throw new $exception($errstr, 0, $errno, $errfile, $errline);
    }
    
    /**
     * Register exception handler.
     *
     * @since 0.1
     */
    public function register()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', true);
        ini_set('html_errors', false);
        set_error_handler([$this, 'handler']);
    }
    
    /**
     * Set exception class.
     * 
     * @param string $class Fully qualified exception class name
     * @since 0.1
     */
    public function setException($class)
    {
        $this->exception = $class;
    }
}
