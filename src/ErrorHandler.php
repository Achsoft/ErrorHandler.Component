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

use Achsoft\Component\ErrorHandler\Exception\CompileErrorException;
use Achsoft\Component\ErrorHandler\Exception\CompileWarningException;
use Achsoft\Component\ErrorHandler\Exception\CoreErrorException;
use Achsoft\Component\ErrorHandler\Exception\CoreWarningException;
use Achsoft\Component\ErrorHandler\Exception\DeprecatedException;
use Achsoft\Component\ErrorHandler\Exception\ErrorException;
use Achsoft\Component\ErrorHandler\Exception\NoticeException;
use Achsoft\Component\ErrorHandler\Exception\ParseException;
use Achsoft\Component\ErrorHandler\Exception\RecoverableErrorException;
use Achsoft\Component\ErrorHandler\Exception\StrictException;
use Achsoft\Component\ErrorHandler\Exception\UserDeprecatedException;
use Achsoft\Component\ErrorHandler\Exception\UserErrorException;
use Achsoft\Component\ErrorHandler\Exception\UserNoticeException;
use Achsoft\Component\ErrorHandler\Exception\UserWarningException;
use Achsoft\Component\ErrorHandler\Exception\WarningException;

/**
 * Handle all Php errors as exceptions.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 * @package Achsoft\Component\ErrorHandler
 * @version 0.2.1
 */
class ErrorHandler
{
    /**
     * Exception error handler.
     */
    public function handle($errno, $errstr, $errfile, $errline)
    {
         switch ($errno) {
             case E_ERROR:
                 throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
             case E_WARNING:
                 throw new WarningException($errstr, 0, $errno, $errfile, $errline);
             case E_PARSE:
                 throw new ParseException($errstr, 0, $errno, $errfile, $errline);
             case E_NOTICE:
                 throw new NoticeException($errstr, 0, $errno, $errfile, $errline);
             case E_CORE_ERROR:
                 throw new CoreErrorException($errstr, 0, $errno, $errfile, $errline);
             case E_CORE_WARNING:
                 throw new CoreWarningException($errstr, 0, $errno, $errfile, $errline);
             case E_COMPILE_ERROR:
                 throw new CompileErrorException($errstr, 0, $errno, $errfile, $errline);
             case E_COMPILE_WARNING:
                 throw new CompileWarningException($errstr, 0, $errno, $errfile, $errline);
             case E_USER_ERROR:
                 throw new UserErrorException($errstr, 0, $errno, $errfile, $errline);
             case E_USER_WARNING:
                 throw new UserWarningException($errstr, 0, $errno, $errfile, $errline);
             case E_USER_NOTICE:
                 throw new UserNoticeException($errstr, 0, $errno, $errfile, $errline);
             case E_STRICT:
                 throw new StrictException($errstr, 0, $errno, $errfile, $errline);
             case E_RECOVERABLE_ERROR:
                 throw new RecoverableErrorException($errstr, 0, $errno, $errfile, $errline);
             case E_DEPRECATED:
                 throw new DeprecatedException($errstr, 0, $errno, $errfile, $errline);
             case E_USER_DEPRECATED:
                 throw new UserDeprecatedException($errstr, 0, $errno, $errfile, $errline);
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
    }

    /**
     * Restore default error handler.
     */
    public function restore()
    {
        restore_error_handler();
    }

    /**
     * Set error reporting.
     *
     * @param int $value
     */
    public function setErrorReporting($value)
    {
        error_reporting($value);
    }

}
