<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $compiledViewPath = implode(DIRECTORY_SEPARATOR, [
            sys_get_temp_dir(),
            'poktan-tests',
            'views-'.getmypid(),
        ]);
        File::ensureDirectoryExists($compiledViewPath);
        config(['view.compiled' => $compiledViewPath]);
    }
}
