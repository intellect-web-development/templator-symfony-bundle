<?php

declare(strict_types=1);

namespace IWD\Templator\Service;

use IWD\Templator\Dto\Renderable;
use PHPUnit\Framework\TestCase;
use stdClass;

class RendererTest extends TestCase
{
    private static Renderer $renderer;

    public function setUp(): void
    {
        self::$renderer = new Renderer();

        parent::setUp();
    }

    public function testStringRender(): void
    {
        $renderable = new Renderable(
            template: 'My first {{ variable }} content',
            variables: [
                'variable' => 'rendered',
            ]
        );

        $expected = 'My first rendered content';

        self::assertSame(
            $expected,
            self::$renderer->render($renderable)
        );
    }

    public function testManyStringRender(): void
    {
        $renderable = new Renderable(
            template: <<<TEXT
                **** {{f.name | classify}} **** {{ a | tableize | pluralize  }} **** {{b | tableize}}  **** {{ c}} **** [{ }] **** {{}}
                TEXT,
            variables: [
                'f' => new class () {
                    public string $name = 'F_NAME';
                },
                'a' => 'AAAA',
                'b' => 'BBBB',
                'c' => 'CCCC',
            ]
        );

        $expected = '**** FNAME **** a_a_a_as **** b_b_b_b  **** CCCC **** [{ }] **** {{}}';

        self::assertSame(
            $expected,
            self::$renderer->render($renderable)
        );
    }

    public function testStringRenderNoSpace(): void
    {
        $renderable = new Renderable(
            template: 'My first {{variable}} content',
            variables: [
                'variable' => 'rendered',
            ]
        );

        $expected = 'My first rendered content';

        self::assertSame(
            $expected,
            self::$renderer->render($renderable)
        );
    }

    public function testObjectStdClassRender(): void
    {
        $renderable = new Renderable(
            template: 'My name is {{ obj.name }}!',
            variables: [
                'obj' => $obj = new stdClass(),
            ]
        );
        $obj->name = 'Alexander';

        $expected = 'My name is Alexander!';

        self::assertSame(
            $expected,
            self::$renderer->render($renderable)
        );
    }

    public function testObjectAnonymousRender(): void
    {
        $renderable = new Renderable(
            template: 'My name is {{ obj.name }}!',
            variables: [
                'obj' => new class () {
                    public string $name = 'Alexander';
                },
            ]
        );

        $expected = 'My name is Alexander!';

        self::assertSame(
            $expected,
            self::$renderer->render($renderable)
        );
    }

    public function testObjectAnonymousNestedRender(): void
    {
        $renderable = new Renderable(
            template: 'My name is {{ obj.name.first }} {{ obj.name.last }}!',
            variables: [
                'obj' => new class () {
                    public object $name;

                    public function __construct()
                    {
                        $this->name = new class () {
                            public string $first = 'Alexander';
                            public string $last = 'Shaman';
                        };
                    }
                },
            ]
        );

        $expected = 'My name is Alexander Shaman!';

        self::assertSame(
            $expected,
            self::$renderer->render($renderable)
        );
    }

    public function testArrayRender(): void
    {
        $renderable = new Renderable(
            template: 'My name is {{ obj.name.first | classify }} {{ obj.name.last | classify }}!',
            variables: [
                'obj' => [
                    'name' => [
                        'first' => 'ivan',
                        'last' => 'petrov',
                    ],
                ],
            ]
        );

        $expected = 'My name is Ivan Petrov!';

        self::assertSame(
            $expected,
            self::$renderer->render($renderable)
        );
    }

    public function testInflectorCases(): void
    {
        $renderable = new Renderable(
            template: '{{a | classify}} {{b | constantize}}, {{c | pluralize | classify}} {{d | pluralize}}',
            variables: [
                'a' => 'hello',
                'b' => 'world',
                'c' => 'summer',
                'd' => 'day',
            ]
        );

        $expected = 'Hello WORLD, Summers days';

        self::assertSame(
            $expected,
            self::$renderer->render($renderable)
        );
    }

    public function testTwoFilters(): void
    {
        $renderable = new Renderable(
            template: '{{a | pluralize | classify}}',
            variables: [
                'a' => 'person',
            ]
        );

        $expected = 'People';

        self::assertSame(
            $expected,
            self::$renderer->render($renderable)
        );
    }
}
