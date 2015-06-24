Era
===

[![Build Status](https://secure.travis-ci.org/elnur/era.png)](http://travis-ci.org/elnur/era)

Era is a date & time library.

Calendar
--------

Testing datetime-based code is difficult if it's coupled to the system time. Suppose you have an age calculator:

```php
<?php
class AgeCalculator
{
    /**
     * @param DateTime $from
     * @return int
     */
    public function age(DateTime $from)
    {
        $now = new DateTime;

        return $from->diff($now)->y;
    }
}
```

And a test for it:

```php
<?php
class AgeCalculatorTest extends PHPUnit_Framework_TestCase
{
    public function testAge()
    {
        $ageCalculator = new AgeCalculator;
        $birthdate = new DateTime('1987-05-31');

        $this->assertEquals(25, $ageCalculator->age($birthdate));
    }
}
```

Now, the test is brittle because it will pass only between `2012-05-31` and `2013-05-30`. After that, it'll start
failing.

To uncouple your code from the system time, you need an abstraction for it. Here comes `Calendar`:

```php
<?php
use Elnur\Era\CalendarInterface;

class AgeCalculator
{
    /**
     * @var CalendarInterface
     */
    private $calendar;

    /**
     * @var CalendarInterface $calendar
     */
    public function __construct(CalendarInterface $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @param DateTime $from
     * @return int
     */
    public function age(DateTime $from)
    {
        $now = $this->calendar->now();

        return $from->diff($now)->y;
    }
}
```

Notice how we use a `CalendarInterface` instance to get the current datetime. Now, you can mock it to make the test
solid:

```php
<?php
class AgeCalculatorTest extends PHPUnit_Framework_TestCase
{
    public function testAge()
    {
        $now = new DateTime('2012-05-31');

        $calendar = $this->getMockForAbstractClass('Elnur\Era\CalendarInterface');
        $calendar
            ->expects($this->any())
            ->method('now')
            ->will($this->returnValue($now))
        ;

        $ageCalculator = new AgeCalculator($calendar);
        $birthdate = new DateTime('1987-05-31');

        $this->assertEquals(25, $ageCalculator->age($birthdate));
    }
}
```

Now, the test won't fail just because some time have passed.

And here's how you use your new shiny `AgeCalculator`:

```php
<?php
$calendar = new Calendar;
$ageCalculator = new AgeCalculator($calendar);

$birthdate = new DateTime('1987-05-31');
$age = $ageCalculator->age($birthdate);
```

AgeCalculator
-------------

`AgeCalculator` is the class implemented above but with a tweak: if you ask it to figure out the age of something from
the future, it returns `0`.
