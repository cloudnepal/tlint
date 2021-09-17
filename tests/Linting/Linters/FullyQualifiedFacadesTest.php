<?php

namespace Tests\Linting\Linters;

use PHPUnit\Framework\TestCase;
use Tighten\TLint\Linters\FullyQualifiedFacades;
use Tighten\TLint\TLint;

class FullyQualifiedFacadesTest extends TestCase
{
    /** @test */
    public function does_not_trigger_when_file_is_not_namespaced()
    {
        $file = <<<file
<?php

Hash::make('test');
file;

        $lints = (new TLint)->lint(
            new FullyQualifiedFacades($file)
        );

        $this->assertEmpty($lints);
    }

    /** @test */
    public function does_not_trigger_on_alias_usage_without_import()
    {
        $file = <<<file
<?php

namespace Test;

File::thisClassExistsInSameDirectoryButIsNotAFacade();
file;

        $lints = (new TLint)->lint(
            new FullyQualifiedFacades($file)
        );

        $this->assertEmpty($lints);
    }

    /** @test */
    public function does_not_trigger_on_facade_usage_with_import()
    {
        $file = <<<file
<?php

namespace Test;

use Illuminate\Support\Facades\Hash;

Hash::make('test');
file;

        $lints = (new TLint)->lint(
            new FullyQualifiedFacades($file)
        );

        $this->assertEmpty($lints);
    }

    /** @test */
    public function does_not_trigger_on_facade_usage_with_nova_import()
    {
        $file = <<<file
<?php

namespace Test;

use Laravel\Nova\Fields\Password;

Password::make('Password');
file;

        $lints = (new TLint)->lint(
            new FullyQualifiedFacades($file)
        );

        $this->assertEmpty($lints);
    }

    /** @test */
    public function does_not_trigger_on_facade_usage_with_custom_aliased_import()
    {
        $file = <<<file
<?php

namespace Test;

use MyNamespace\MyClass as Config;

Config::get('test');
file;

        $lints = (new TLint)->lint(
            new FullyQualifiedFacades($file)
        );

        $this->assertEmpty($lints);
    }

    /** @test */
    public function does_not_trigger_on_facade_usage_with_grouped_import()
    {
        $file = <<<file
<?php

namespace Test;

use Illuminate\Support\Facades\{Config, Hash};

Config::get('test');
file;

        $lints = (new TLint)->lint(
            new FullyQualifiedFacades($file)
        );

        $this->assertEmpty($lints);
    }

    /** @test */
    public function does_not_throw_on_variable_class_static_calls()
    {
        $file = <<<file
<?php

namespace Test;

class Relationships
{
    static function randomOrCreate(\$className)
    {
        if (\$className::all()->count() > 0) {
            return \$className::all()->random();
        }

        return factory(\$className)->create();
    }
}
file;

        $lints = (new TLint)->lint(
            new FullyQualifiedFacades($file)
        );

        $this->assertEmpty($lints);
    }
}
