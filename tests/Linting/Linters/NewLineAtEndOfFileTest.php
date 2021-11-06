<?php

namespace Tests\Linting\Linters;

use PHPUnit\Framework\TestCase;
use Tighten\TLint\Linters\NewLineAtEndOfFile;
use Tighten\TLint\TLint;

class NewLineAtEndOfFileTest extends TestCase
{
    /** @test */
    public function catches_file_without_new_line_at_end()
    {
        $file = <<<file
<?php

use B\A as AA;
use A\Z\Z;

\$ok = 'thing';
file;

        $lints = (new TLint)->lint(
            new NewLineAtEndOfFile($file)
        );

        $this->assertEquals(6, $lints[0]->getNode()->getLine());
    }

    /** @test */
    public function does_not_trigger_on_file_with_newline_at_end()
    {
        $file = <<<file
<?php

use B\A as AA;
use A\Z\Z;

\$ok = 'thing';

file;

        $lints = (new TLint)->lint(
            new NewLineAtEndOfFile($file)
        );

        $this->assertEmpty($lints);
    }
}
