<?php
class a 
{
	public function testA()
	{
		return static::testB();
		// return self::testB();
	}

	public static function testB()
	{
		return 'b';
	}
}


class B extends a
{
	public static function testB()
	{
		return 'c';
	}
}

$b = new B();
echo $b->testA(), PHP_EOL;


// class DomainObject
// {
// 	public static function create()
// 	{
// 		return new static();
// 	}
// }



// class Document extends DomainObject
// {

// }



// $a = Document::create();

// var_dump($a);
