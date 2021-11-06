<?php

namespace Tests\Formatting\Formatters;

use PHPUnit\Framework\TestCase;
use Tighten\TLint\Formatters\AlphabeticalImports;
use Tighten\TLint\TFormat;

class AlphabeticalImportsTest extends TestCase
{
    /** @test */
    public function fixes_non_alphabetical_imports()
    {
        $file = <<<file
<?php

use B\\A as AA;
use A\\Z\\Z;

\$ok = 'thing';
file;

        $formatted = (new TFormat)->format(
            new AlphabeticalImports($file)
        );

        $correctlyFormatted = <<<file
<?php

use A\\Z\\Z;
use B\\A as AA;

\$ok = 'thing';
file;

        $this->assertEquals($correctlyFormatted, $formatted);
    }

    /** @test */
    public function fixes_non_alphabetical_imports_in_namespace()
    {
        $file = <<<file
<?php

namespace Test;

use B\\A as AA;
use A\\Z\\Z;

\$ok = 'thing';
file;

        $formatted = (new TFormat)->format(
            new AlphabeticalImports($file)
        );

        $correctlyFormatted = <<<file
<?php

namespace Test;

use A\\Z\\Z;
use B\\A as AA;

\$ok = 'thing';
file;

        $this->assertEquals($correctlyFormatted, $formatted);
    }

    /** @test */
    public function does_not_throw_when_require_is_the_first_expression()
    {
        $file = <<<file
<?php

require __DIR__ . '/vendor/autoload.php';

use PhpParser\\ParserFactory;

file;

        $formatted = (new TFormat)->format(
            new AlphabeticalImports($file)
        );

        $correctlyFormatted = <<<file
<?php

require __DIR__ . '/vendor/autoload.php';

use PhpParser\\ParserFactory;

file;

        $this->assertEquals($correctlyFormatted, $formatted);
    }

    /** @test */
    public function works_with_function_imports()
    {
        $file = <<<file
<?php

namespace Tests;

use function Tighten\\TLint\\version;
use function PHPUnit\\Framework\\test;

file;

        $formatted = (new TFormat)->format(
            new AlphabeticalImports($file)
        );

        $correctlyFormatted = <<<file
<?php

namespace Tests;

use function PHPUnit\\Framework\\test;
use function Tighten\\TLint\\version;

file;

        $this->assertEquals($correctlyFormatted, $formatted);
    }

    /** @test */
    public function works_with_const_imports()
    {
        $file = <<<file
<?php

namespace Tests;

use const Tighten\\TLint\\VERSION;
use const PHPUnit\\Framework\\TEST;

file;

        $formatted = (new TFormat)->format(
            new AlphabeticalImports($file)
        );

        $correctlyFormatted = <<<file
<?php

namespace Tests;

use const PHPUnit\\Framework\\TEST;
use const Tighten\\TLint\\VERSION;

file;

        $this->assertEquals($correctlyFormatted, $formatted);
    }

    /** @test */
    public function orders_import_types_by_class_function_const_with_a_line_between()
    {
        $file = <<<file
<?php

namespace Tests;

use com\\test\\ClassA;
use const com\\test\\ConstA;
use function com\\test\\fn_b;

file;

        $formatted = (new TFormat)->format(
            new AlphabeticalImports($file)
        );

        $correctlyFormatted = <<<file
<?php

namespace Tests;

use com\\test\\ClassA;
use function com\\test\\fn_b;
use const com\\test\\ConstA;

file;

        $this->assertEquals($correctlyFormatted, $formatted);
    }

    /** @test */
    public function groups_types_of_imports_properly()
    {
        $file = <<<file
<?php

namespace Tests;

use const com\\test\\ConstB;
use com\\test\\ClassB;
use const com\\test\\ConstA;
use function com\\test\\fn_b;

file;

        $formatted = (new TFormat)->format(
            new AlphabeticalImports($file)
        );

        $correctlyFormatted = <<<file
<?php

namespace Tests;

use com\\test\\ClassB;
use function com\\test\\fn_b;
use const com\\test\\ConstA;
use const com\\test\\ConstB;

file;

        $this->assertEquals($correctlyFormatted, $formatted);
    }

    /** @test */
    public function does_nothing_when_group_imports_are_used()
    {
        $file = <<<file
<?php

use Z;
use Symfony\\Component\\{Console\\Application, Console\\Tester\\CommandTester};

\$ok = 'thing';

file;

        $formatted = (new TFormat)->format(
            new AlphabeticalImports($file)
        );

        $correctlyFormatted = <<<file
<?php

use Z;
use Symfony\\Component\\{Console\\Application, Console\\Tester\\CommandTester};

\$ok = 'thing';

file;

        $this->assertEquals($correctlyFormatted, $formatted);
    }

    /** @test */
    public function ignores_case_correctly()
    {
        $file = <<<file
<?php

use App\Reportable;
use App\ReportFactory;

\$ok = 'thing';

file;

        $formatted = (new TFormat)->format(
            new AlphabeticalImports($file)
        );

        $correctlyFormatted = <<<file
<?php

use App\Reportable;
use App\ReportFactory;

\$ok = 'thing';

file;

        $this->assertEquals($correctlyFormatted, $formatted);
    }
}
