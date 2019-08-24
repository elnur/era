<?php
/*
 * Copyright (c) 2012-2013 Elnur Abdurrakhimov
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Elnur\Era;

use DateTime;
use PHPUnit\Framework\TestCase;

class AgeCalculatorTest extends TestCase
{
    public function testAge()
    {
        $now = new DateTime('2007-05-31');

        $calendar = $this->getMockForAbstractClass(CalendarInterface::class);
        $calendar
            ->expects($this->any())
            ->method('now')
            ->will($this->returnValue($now))
        ;

        $calculator = new AgeCalculator($calendar);

        $this->assertEquals(0, $calculator->age(new DateTime('2010-05-31')));
        $this->assertEquals(19, $calculator->age(new DateTime('1987-06-01')));
        $this->assertEquals(20, $calculator->age(new DateTime('1987-05-30')));
        $this->assertEquals(20, $calculator->age(new DateTime('1987-05-31')));
    }
}
