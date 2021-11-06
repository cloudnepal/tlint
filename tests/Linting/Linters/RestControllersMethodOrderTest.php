<?php

namespace Tests\Linting\Linters;

use PHPUnit\Framework\TestCase;
use Tighten\TLint\Linters\RestControllersMethodOrder;
use Tighten\TLint\TLint;

class RestControllersMethodOrderTest extends TestCase
{
    /** @test */
    public function catches_wrong_order_rest_methods()
    {
        $file = <<<file
<?php

namespace App;

class Controller
{
    use Thing;

    public function store()
    {
        return view('test.store', ['ok' => 'test']);
    }

    public function create()
    {
        return view('test.create', ['ok' => 'test']);
    }
}
file;

        $lints = (new TLint)->lint(
            new RestControllersMethodOrder($file)
        );

        $this->assertEquals(5, $lints[0]->getNode()->getLine());
    }

    /** @test */
    public function does_not_trigger_on_correctly_ordered_rest_methods()
    {
        $file = <<<file
<?php

namespace App;

class Controller
{
    public function create()
    {
        return view('test.create', ['ok' => 'test']);
    }

    public function store()
    {
        return view('test.store', ['ok' => 'test']);
    }
}
file;

        $lints = (new TLint)->lint(
            new RestControllersMethodOrder($file)
        );

        $this->assertEmpty($lints);
    }
}
