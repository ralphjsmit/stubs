<?php

use RalphJSmit\Stubs\Stubs;

beforeEach(function () {
    if ( file_exists(__DIR__ . '/tmp') ) {
        rmdir_recursive(__DIR__ . '/tmp');
    }

    mkdir(__DIR__ . '/tmp', 0777, true);
    mkdir(__DIR__ . '/tmp/demo-application', 0777, true);

    $this->stubs = Stubs::dir(__DIR__);
});

it('can copy a file to a new location', function () {
    Stubs::file(__DIR__ . '/__fixtures__/demo-application/CopyFile.php')
        ->copy(__DIR__ . '/tmp/demo-application');

    expect(
        file_get_contents(__DIR__ . '/__fixtures__/demo-application/CopyFile.php')
    )->toBe(
        file_get_contents(__DIR__ . '/tmp/demo-application/CopyFile.php')
    );
});

it('can move a file to a new location', function () {
    Stubs::file(__DIR__ . '/__fixtures__/demo-application/MoveFile.php')
        ->copy(__DIR__ . '/tmp/demo-application/a');

    $contents = file_get_contents(__DIR__ . '/__fixtures__/demo-application/MoveFile.php');
    expect($contents)->toBe(file_get_contents(__DIR__ . '/tmp/demo-application/a/MoveFile.php'));

    Stubs::file(__DIR__ . '/tmp/demo-application/a/MoveFile.php')
        ->move(__DIR__ . '/tmp/demo-application/b');

    expect(__DIR__ . '/__fixtures__/demo-application/a/MoveFile.php')->not->toExist();
    expect(__DIR__ . '/tmp/demo-application/b/MoveFile.php')->toHaveContents($contents);
});

it('can copy a file to a new location with relative __DIR__', function () {
    $this->stubs->getFile('/__fixtures__/demo-application/CopyFile.php')
        ->copy('/tmp/demo-application');

    expect(
        file_get_contents(__DIR__ . '/__fixtures__/demo-application/CopyFile.php')
    )->toBe(
        file_get_contents(__DIR__ . '/tmp/demo-application/CopyFile.php')
    );
});

it('can move a file to a new location with relative __DIR__', function () {
    $this->stubs->getFile('/__fixtures__/demo-application/MoveFile.php')
        ->copy('/tmp/demo-application/a');

    $contents = file_get_contents(__DIR__ . '/__fixtures__/demo-application/MoveFile.php');
    expect($contents)->toBe(file_get_contents(__DIR__ . '/tmp/demo-application/a/MoveFile.php'));

    $this->stubs->getFile('/tmp/demo-application/a/MoveFile.php')
        ->move('/tmp/demo-application/b');

    expect(__DIR__ . '/__fixtures__/demo-application/a/MoveFile.php')
        ->not->toExist();
    expect(__DIR__ . '/tmp/demo-application/b/MoveFile.php')
        ->toHaveContents($contents);
});
