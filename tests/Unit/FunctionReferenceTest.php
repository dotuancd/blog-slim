<?php


namespace Tests\Unit;

use PHPUnit_Framework_TestCase;

class FunctionReferenceTest extends PHPUnit_Framework_TestCase
{
    public function testReference()
    {
        $a = new \stdClass();
        $a->name = 'Alice';

        updateName($a);
        $this->assertEquals('Bob', $a->name);
    }

    public function testReadFiles()
    {
        $folder = __DIR__ . '/../../vendor/nikic/fast-route';

//        $files = $this->directFiles($folder);

        dd(scandir($folder));

        $direct = Files::direct($folder);
        $spl = Files::spl($folder);
        $splRecursive = Files::splRecursive($folder);


        $this->assertEquals($direct, $spl);
        $this->assertEquals($spl, $splRecursive);
//
//        $dir = new \DirectoryIterator($folder);
//
//        $files = [];
//        foreach ($dir as $file) {
//            if ($file->isFile()) {
//                $files[] = $file->getPath();
//            }
//        }
    }

    protected function directFiles($folder)
    {
        $handle = opendir($folder);

        $files = [];
        while ($file = readdir($handle)) {

            if ($file == '.' || $file == '..') {
                continue;
            }

            $path = $folder . '/' . $file;
            if (is_dir($path)) {
                $files = array_merge($files, $this->directFiles($path));
            } else if (is_file($path)) {
                $files[] = realpath($path);
            }
        }

        closedir($handle);

        return $files;
    }

    protected function splFiles($path)
    {

    }

    protected function splRecursive($path)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        $files = [];
        foreach ($iterator as $entry) {
            /** @var \SplFileInfo $entry */
            $path = $entry->getFilename();
            $isDot = $path == '.' || $path == '..';
            if ($isDot) {
                continue;
            }

            $files[] = $entry->getRealPath();
        }

        return $files;
    }
}

function () {

}

