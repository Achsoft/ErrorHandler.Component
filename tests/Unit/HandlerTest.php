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

namespace Achsoft\Tests\Unit\ErrorHandler;

/**
 * Handler test class.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */
class HandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testRaiseException()
    {
        $this->setExpectedException('\Achsoft\Component\ErrorHandler\ErrorException');
        
        $errorHandler = new \Achsoft\Component\ErrorHandler\Handler();
        $errorHandler->register();
        
        trigger_error(E_USER_ERROR);
    }
    
    public function testSetException()
    {
        $exception = '\ErrorException';
        $this->setExpectedException($exception);
        
        $errorHandler = new \Achsoft\Component\ErrorHandler\Handler();
        $errorHandler->register();
        $errorHandler->setException($exception);
        
        trigger_error(E_USER_ERROR);
    }
}

