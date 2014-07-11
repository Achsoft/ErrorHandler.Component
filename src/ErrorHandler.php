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

use Psr\Log\LoggerAwareInterface;

/**
 * Handle all Php errors as exceptions.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 * @package Achsoft\Component\ErrorHandler
 * @version 0.3.0
 */
class ErrorHandler implements LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;
    
    /**
     * Fatal errors map.
     *
     * @var array
     */
    protected $fatalError = [
        E_ERROR,
        E_COMPILE_ERROR,
        E_COMPILE_WARNING,
        E_CORE_ERROR,
        E_CORE_WARNING,
        E_PARSE
    ];

    /**
     * Exception error handler.
     *
     * @param type $errno
     * @param type $errstr
     * @param type $errfile
     * @param type $errline
     * @throws @var:$this@mtd:createException
     */
    public function handle($errno, $errstr, $errfile, $errline)
    {
        if (error_reporting() & $errno) {
            $exception = $this->createException($errno, $errstr, $errfile, $errline);
            throw $exception;
        }
    }
    
    /**
     * Handle fatal error if occurs.
     * 
     * This method is registered as shutdown function.
     */
    public function handleFatal()
    {
        $error = error_get_last();
        if (isset($error) && in_array($error['type'], $this->fatalError)) {
            $exception = $this->createException($error['type'], $error['message'], $error['file'], $error['line']);
            // @todo log using logger
            exit($error['type']);
        }
    }

    /**
     * Register exception handler.
     */
    public function register()
    {
        ini_set('display_errors', false);
        ini_set('html_errors', false);
        set_error_handler([$this, 'handle']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleFatal']);
    }

    /**
     * Restore default error handler.
     */
    public function restore()
    {
        restore_error_handler();
    }
    
    public function handleException($exception)
    {
        if (isset($this->logger)) {
            // @todo log using logger
            return;
        }
        //@todo error_log($message);
    }

    public function setErrorReporting($value)
    {
        error_reporting($value);
    }

    /**
     * Create exception based on error type.
     * 
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param in $errline
     * @return \Achsoft\Component\ErrorHandler\Exception based on error number
     */
    protected function createException($errno, $errstr, $errfile, $errline)
    {
        $exceptionTypeMap = [
            E_ERROR             => '\Achsoft\Component\ErrorHandler\Exception\ErrorException',
            E_WARNING           => '\Achsoft\Component\ErrorHandler\Exception\WarningException',
            E_PARSE             => '\Achsoft\Component\ErrorHandler\Exception\ParseException',
            E_NOTICE            => '\Achsoft\Component\ErrorHandler\Exception\NoticeException',
            E_CORE_ERROR        => '\Achsoft\Component\ErrorHandler\Exception\CoreErrorException',
            E_CORE_WARNING      => '\Achsoft\Component\ErrorHandler\Exception\CoreWarningException',
            E_COMPILE_ERROR     => '\Achsoft\Component\ErrorHandler\Exception\CompileErrorException',
            E_COMPILE_WARNING   => '\Achsoft\Component\ErrorHandler\Exception\CompileWarningException',
            E_USER_ERROR        => '\Achsoft\Component\ErrorHandler\Exception\UserErrorException',
            E_USER_WARNING      => '\Achsoft\Component\ErrorHandler\Exception\UserWarningException',
            E_USER_NOTICE       => '\Achsoft\Component\ErrorHandler\Exception\UserNoticeException',
            E_STRICT            => '\Achsoft\Component\ErrorHandler\Exception\StrictException',
            E_RECOVERABLE_ERROR => '\Achsoft\Component\ErrorHandler\Exception\RecoverableErrorException',
            E_DEPRECATED        => '\Achsoft\Component\ErrorHandler\Exception\DeprecatedException',
            E_USER_DEPRECATED   => '\Achsoft\Component\ErrorHandler\Exception\UserDeprecatedException'
        ];

        if (isset($exceptionTypeMap[$errno])) {

            if (!class_exists($exceptionTypeMap[$errno])) {
                require_once __DIR__ . '/Exception/src.php';
            }

            return new $exceptionTypeMap[$errno]($errstr, 0, $errno, $errfile, $errline);
        }
    }

    protected function exceptionToStr($exception)
    {
        $date = date("Y-m-d H:i:s (T)");
        $errclass = get_class($exception);
        $errtype = [
                E_ERROR              => 'Error',
                E_WARNING            => 'Warning',
                E_PARSE              => 'Parsing Error',
                E_NOTICE             => 'Notice',
                E_CORE_ERROR         => 'Core Error',
                E_CORE_WARNING       => 'Core Warning',
                E_COMPILE_ERROR      => 'Compile Error',
                E_COMPILE_WARNING    => 'Compile Warning',
                E_USER_ERROR         => 'User Error',
                E_USER_WARNING       => 'User Warning',
                E_USER_NOTICE        => 'User Notice',
                E_STRICT             => 'Runtime Notice',
                E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
        ];
        $errno = $exception->getCode();
        $errstr = $exception->getMessage();
        $errfile = $exception->getFile();
        $errline = $exception->getLine();
        $errtrace = $exception->getTraceAsString();

        // @todo error log string
        $message = "Exception '%s' with message '%s'\n\nin %s: %n\n\nStack trace:\n%s";

    }
}
