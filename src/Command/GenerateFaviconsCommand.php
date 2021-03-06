<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFaviconsCommand extends Command
{
    protected static $defaultName = 'app:generate-favicons';

    protected function configure()
    {
        $this->setDescription('Generate favicons');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->colors as $name => $code) {
            $this->generateFavicon($name, 64);
            $this->generateFavicon($name, 144);
            $this->generateFavicon($name, 512);
        }

        return Command::SUCCESS;
    }

    private $colors = [
        'red' => 'dc3545',
        'orange' => 'fd7e14',
        'yellow' => 'ffc107',
        'green' => '28a745',
        'purple' => '9371d1',
        'gray' => '6c757d',
    ];

    private function generateFavicon($color, $size)
    {
        $file = __DIR__.'/../../public/favicon-'.$color.'-'.$size.'.png';

        $image = imagecreate($size, $size);

        $split = str_split($this->colors[$color], 2);
        $r = hexdec($split[0]);
        $g = hexdec($split[1]);
        $b = hexdec($split[2]);

        $color = imagecolorallocate($image, $r, $g, $b);

        imagerectangle($image, 0, 0, $size, $size, $color);

        imagepng($image, $file);
    }
}
