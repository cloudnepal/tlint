<?php

namespace Tests\Linting\Linters;

use PHPUnit\Framework\TestCase;
use Tighten\TLint\Linters\TrailingCommasOnArrays;
use Tighten\TLint\TLint;

class TrailingCommasOnArraysTest extends TestCase
{
    /** @test */
    public function catches_missing_trailing_comma()
    {
        $file = <<<file
<?php

\$array = [
    1,
    2
];
file;

        $lints = (new TLint)->lint(
            new TrailingCommasOnArrays($file)
        );

        $this->assertEquals(5, $lints[0]->getNode()->getLine());
    }

    /** @test */
    public function ignores_single_line_array()
    {
        $file = <<<file
<?php

\$array = [1, 2];
file;

        $lints = (new TLint)->lint(
            new TrailingCommasOnArrays($file)
        );

        $this->assertEmpty($lints);
    }

    /** @test */
    public function ignores_empty_array()
    {
        $file = <<<file
<?php

\$array = [
    //
];
file;

        $lints = (new TLint)->lint(
            new TrailingCommasOnArrays($file)
        );

        $this->assertEmpty($lints);
    }

    /** @test */
    public function handles_trailing_comments()
    {
        $file = <<<file
<?php

\$array = [
    1,
    2, // inline comment
];
file;

        $lints = (new TLint)->lint(
            new TrailingCommasOnArrays($file)
        );

        $this->assertEmpty($lints);
    }

    /** @test */
    public function handles_trailing_comments_with_comment_characters_in_values()
    {
        $file = <<<file
<?php

\$array = [
    1,
    'url' => 'https://vehiclephotos.vauto.com/53/fe/66/83-3b4f-4da9-9f01-1734905d230b/image-1.jpg', /** ok **/
];
file;

        $lints = (new TLint)->lint(
            new TrailingCommasOnArrays($file)
        );

        $this->assertEmpty($lints);
    }

    /** @test */
    public function catches_multidimensional_arrays()
    {
        $file = <<<file
<?php

\$array = [
    1,
    [
        "ok",
        function () {
            return 1;
        }
    ],
];
file;

        $lints = (new TLint)->lint(
            new TrailingCommasOnArrays($file)
        );

        $this->assertEquals(9, $lints[0]->getNode()->getLine());
    }
}
