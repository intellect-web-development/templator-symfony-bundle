<?php

declare(strict_types=1);

namespace IWD\Templator\Service;

use IWD\Templator\Dto\Renderable;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RendererTest extends KernelTestCase
{
    private static Renderer $renderer;

    public function setUp(): void
    {
        self::bootKernel();

        /** @var Renderer $renderer */
        $renderer = self::getContainer()->get(Renderer::class);
        self::$renderer = $renderer;

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

    public function testNotFoundVarSafeRender(): void
    {
        $renderable = new Renderable(
            template: 'My {{has_var}}first {{ my_var }} content',
            variables: [
                'other' => 'demo',
                'has_var' => null,
            ]
        );

        $expected = 'My first {{ my_var }} content';

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
        $obj->name = 'Templator';

        $expected = 'My name is Templator!';

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
                    public string $name = 'Templator';
                },
            ]
        );

        $expected = 'My name is Templator!';

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
                            public string $first = 'Templator';
                            public string $last = 'Symfony';
                        };
                    }
                },
            ]
        );

        $expected = 'My name is Templator Symfony!';

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

    public function testWithSpecSymbols(): void
    {
        $renderable = new Renderable(
            template: <<<'TEMPLATE'
                {% if data is not null %}
                    {% if (data.id is defined) %}
                        <a class="ui labeled icon" href="{{ path('app_{{boundedContextName | tableize}}.{{entity_name | tableize}}_show'}}"</a>
                        <a class="ui labeled icon" href="{{ path('app_{{boundedContextName | tableize}}....{{entity_name | tableize}}_show'}}"</a>
                        {{ has }}
                    {% endif %}
                {% endif %}
                TEMPLATE,
            variables: [
                'boundedContextName' => 'Profile',
                'entity_name' => 'Client',
            ],
        );

        $expected = <<<'TEMPLATE'
            {% if data is not null %}
                {% if (data.id is defined) %}
                    <a class="ui labeled icon" href="{{ path('app_profile.client_show'}}"</a>
                    <a class="ui labeled icon" href="{{ path('app_profile....client_show'}}"</a>
                    {{ has }}
                {% endif %}
            {% endif %}
            TEMPLATE;


        self::assertSame(
            $expected,
            self::$renderer->render($renderable)
        );
    }

    public function testCollapse(): void
    {
        self::assertSame(
            <<<'TEMPLATE'
                class {
                123
                }
                TEMPLATE,
            self::$renderer->render(new Renderable(
                template: <<<'TEMPLATE'
                    class {
                    {{var}}
                    }
                    TEMPLATE,
                variables: [
                    'var' => '123',
                ],
            ))
        );

        self::assertSame(
            <<<'TEMPLATE'
                class {
                }
                TEMPLATE,
            self::$renderer->render(new Renderable(
                template: <<<'TEMPLATE'
                    class {
                    {{var}}
                    }
                    TEMPLATE,
                variables: [
                    'var' => null,
                ],
            ))
        );
    }
}
