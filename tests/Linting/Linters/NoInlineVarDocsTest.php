<?php

namespace Tests\Linting\Linters;

use PHPUnit\Framework\TestCase;
use Tighten\TLint\Linters\NoInlineVarDocs;
use Tighten\TLint\TLint;

class NoInlineVarDocsTest extends TestCase
{
    /** @test */
    public function catches_inline_var_doc()
    {
        $file = <<<file
<?php

use Test\ThingA;

/** @var ThingA \$ok */
\$ok = new ThingA;

file;

        $lints = (new TLint)->lint(
            new NoInlineVarDocs($file)
        );

        $this->assertEquals(6, $lints[0]->getNode()->getLine());
    }

    /** @test */
    public function does_not_trigger_on_non_var_doc()
    {
        $file = <<<file
<?php

use Test\ThingA;

/** Description of the thing. */
\$ok = new ThingA;

file;

        $lints = (new TLint)->lint(
            new NoInlineVarDocs($file)
        );

        $this->assertEmpty($lints);
    }

    /** @test */
    public function only_triggers_once_for_per_doc()
    {
        $file = <<<file
<?php

foreach (\$lints as \$lint) {
    /** @var Lint \$lint */

    [\$title, \$codeLine] = explode(PHP_EOL, (string) \$lint);

    \$output->writeln([
        "<fg=yellow>{\$title}</>",
        \$codeLine,
    ]);
}

file;

        $lints = (new TLint)->lint(
            new NoInlineVarDocs($file)
        );

        $this->assertCount(1, $lints);
    }
}
