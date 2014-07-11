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

namespace Test\Unit\ErrorHandler;

/**
 * ErrorHandler test class.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */
class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        error_reporting(E_ALL);
    }
    
    /**
     * @dataProvider exceptionProvider
     */
    public function testRaiseException($errno, $exception)
    {
        $this->setExpectedException($exception);
        
        $errorHandler = new \Achsoft\Component\ErrorHandler\ErrorHandler();
        $errorHandler->register();
        
        // only works with the E_USER family of constant
        trigger_error($errno);
    }
    
    public function exceptionProvider()
    {
        return [
            [E_ERROR, '\Achsoft\Component\ErrorHandler\Exception\ErrorException'],
//            [E_WARNING, '\Achsoft\Component\ErrorHandler\Exception\WarningException'],
//            [E_PARSE, '\Achsoft\Component\ErrorHandler\Exception\ParseException'],
//            [E_NOTICE, '\Achsoft\Component\ErrorHandler\Exception\NoticeException'],
//            [E_CORE_ERROR, '\Achsoft\Component\ErrorHandler\Exception\CoreErrorException'],
//            [E_CORE_WARNING, '\Achsoft\Component\ErrorHandler\Exception\CoreWarningException'],
//            [E_COMPILE_ERROR, '\Achsoft\Component\ErrorHandler\Exception\CompileErrorException'],
//            [E_COMPILE_WARNING, '\Achsoft\Component\ErrorHandler\Exception\CompileWarningException'],
//            [E_USER_ERROR, '\Achsoft\Component\ErrorHandler\Exception\UserErrorException'],
//            [E_USER_WARNING, '\Achsoft\Component\ErrorHandler\Exception\UserWarningException'],
            [E_USER_NOTICE, '\Achsoft\Component\ErrorHandler\Exception\UserNoticeException'],
//            [E_STRICT, '\Achsoft\Component\ErrorHandler\Exception\StrictException'],
//            [E_RECOVERABLE_ERROR, '\Achsoft\Component\ErrorHandler\Exception\RecoverableException'],
//            [E_DEPRECATED, '\Achsoft\Component\ErrorHandler\Exception\DeprecatedException'],
//            [E_USER_DEPRECATED, '\Achsoft\Component\ErrorHandler\Exception\UserDeprecatedException'],
        ];
    }
    
    public function testRestoreHandler()
    {
        $output = 'RESTORED PREVIOUS ERROR HANDLER...';
        $this->expectOutputString($output);
    
        set_error_handler(function () use ($output) {echo $output;});
        
        $errorHandler = new \Achsoft\Component\ErrorHandler\ErrorHandler();
        $errorHandler->register();
        $errorHandler->restore();
        
        trigger_error(E_USER_ERROR);
    }
    
    public function testFatalError()
    {
        $exception = '\RuntimeException';
        $this->setExpectedException($exception);
        $errorHandler = new \Achsoft\Component\ErrorHandler\ErrorHandler();
        $errorHandler->register();
        
        $x = null;
        $x->method();
    }
}
