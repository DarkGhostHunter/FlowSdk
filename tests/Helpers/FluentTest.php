<?php

namespace Tests\Helpers;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;
use PHPUnit\Framework\TestCase;

class FluentTest extends TestCase
{

    public function test__unset()
    {
        $fluent = new Fluent([
            'foo' => 'bar'
        ]);

        unset($fluent['foo']);

        $this->assertEmpty($fluent->toArray());
    }

    public function testGetHidden()
    {
        $fluent = new class([
            'hidden' => true,
            'nothidden' => 'nothiddenValue'
        ]) extends Fluent {
            protected $hidden = ['hidden'];
        };

        $this->assertArrayNotHasKey('hidden', $fluent->toArray());

    }

    public function testGet()
    {
        $fluent = new Fluent([
            'foo' => 'bar',
            'closure' => function () { return 'isClosure'; },
        ]);

        $this->assertEquals('bar', $fluent->get('foo'));
        $this->assertEquals('isClosure', $fluent->get('closure'));
    }

    public function testOffsetSet()
    {
        $fluent = new Fluent([
            'foo' => 'bar'
        ]);

        $this->assertEquals('bar', $fluent['foo']);
    }

    public function test__toString()
    {
        $fluent = new Fluent([
            'foo' => 'bar',
            'key' => 'value',
            'json' => '{\}',
        ]);

        $this->assertJson((string)$fluent);
    }

    public function test__call()
    {
        $fluent = new Fluent();

        $this->assertInstanceOf(Fluent::class, $fluent->foo('bar'));
        $this->assertEquals('bar', $fluent->foo);
    }

    public function testToArray()
    {
        $fluent = new Fluent([
            'foo' => 'bar'
        ]);

        $this->assertIsArray($fluent->toArray());
    }

    public function testGetMerge()
    {
        $fluent = new class([
            'foo' => 'bar'
        ]) extends Fluent {
            protected $merge = ['toMerge'];

            protected $toMerge = [
                'key' => 'value',
                'foo' => 'notBar',
            ];
        };

        $this->assertIsArray($fluent->toArray());
        $this->assertEquals([
            'key' => 'value',
            'foo' => 'notBar',
        ], $fluent->toArray()['toMerge']);
        $this->assertArrayHasKey('toMerge', $fluent->toArray());
        $this->assertEquals('bar', $fluent['foo']);
    }

    public function test__set()
    {
        $fluent = new Fluent();

        $fluent->foo = 'bar';

        $this->assertEquals('bar', $fluent->get('foo'));
    }

    public function testFromJson()
    {
        $string = '{"foo":"bar","key":"value","json":"{\\\\}"}';

        $fluent = Fluent::fromJson($string);

        $this->assertIsString((string)$fluent);
        $this->assertEquals(json_decode($string, true), $fluent->toArray());
    }

    public function testGetRawAttributes()
    {
        $fluent = new class($array = ['foo' => 'bar']) extends Fluent {
            public function getFooAttribute()
            {
                return 'notBar';
            }
        };

        $this->assertEquals($array, $fluent->getRawAttributes());
    }

    public function test__construct()
    {
        $fluent = new Fluent(['foo' => 'bar']);

        $this->assertInstanceOf(Fluent::class, $fluent);
    }

    public function testToJson()
    {
        $fluent = new Fluent(['foo' => 'bar']);

        $this->assertJson($fluent->toJson());
    }

    public function testOffsetGet()
    {
        $fluent = new Fluent(['foo' => 'bar']);

        $this->assertEquals('bar', $fluent['foo']);
    }

    public function testSet()
    {
        $fluent = new Fluent(['foo' => 'bar']);

        $fluent->set('foo', 'new bar');

        $this->assertEquals('new bar', $fluent->get('foo'));
    }

    public function testCount()
    {
        $fluent = new Fluent($array = ['foo' => 'bar', 'key' => 'value']);

        $this->assertEquals(count($array), $fluent->count());

    }

    public function testJsonSerialize()
    {
        $fluent = new Fluent($array = ['foo' => 'bar']);

        $this->assertJson(json_encode($fluent));

        $this->assertEquals($array, json_decode(json_encode($fluent), true));

    }

    public function testGetAttributes()
    {
        $fluent = new class($array = ['foo' => 'bar', 'notFoo' => 'notBar']) extends Fluent {
            public function getKeyAttribute()
            {
                return 'value';
            }
            public function getNotFooAttribute()
            {
                return 'lol';
            }
        };

        $this->assertEquals(['foo' => 'bar', 'notFoo' => 'lol'], $fluent->toArray());
        $this->assertEquals('lol', $fluent->toArray()['notFoo']);
        $this->assertEquals(['foo' => 'bar'], $fluent->getAttributes('foo'));
    }

    public function testOffsetUnset()
    {
        $fluent = new Fluent(['foo' => 'bar']);

        unset($fluent['foo']);

        $this->assertEmpty($fluent->get('foo'));
    }

    public function testMake()
    {
        $fluent = Fluent::make($array = ['foo' => 'bar']);

        $this->assertInstanceOf(Fluent::class, $fluent);
        $this->assertEquals($array, $fluent->toArray());
    }

    public function testSetAttributes()
    {
        $fluent = new Fluent(['foo' => 'bar']);

        $fluent->setAttributes([
            'key' => 'value'
        ]);

        $this->assertEquals(['foo' => 'bar', 'key' => 'value'], $fluent->toArray());
    }

    public function testSetRawAttributes()
    {
        $fluent = new class extends Fluent {
            public function setKeyAttribute()
            {
                return 'noValue';
            }
            public function setFooAttribute()
            {
                return 'noBar';
            }
        };

        $fluent->setRawAttributes($array = [
            'key' => 'value',
            'foo' => 'bar'
        ]);

        $this->assertEquals($array, $fluent->getRawAttributes());

    }

    public function testGetRawAttribute()
    {
        $fluent = new class($array = ['foo' => 'bar', 'notFoo' => 'notBar']) extends Fluent {
            public function getKeyAttribute()
            {
                return 'value';
            }
            public function getNotFooAttribute()
            {
                return 'lol';
            }
        };

        $this->assertEquals('bar', $fluent->getRawAttribute('foo'));
    }

    public function testOffsetExists()
    {
        $fluent = new Fluent(['foo' => 'bar']);

        $this->assertTrue(isset($fluent['foo']));
    }

    public function test__get()
    {
        $fluent = new Fluent(['foo' => 'bar']);

        $this->assertEquals('bar', $fluent->foo);
        $this->assertEmpty($fluent->bar);

    }

    public function test__isset()
    {
        $fluent = new Fluent(['foo' => 'bar']);

        $this->assertTrue(isset($fluent->foo));
        $this->assertFalse(isset($fluent->bar));
    }
}
