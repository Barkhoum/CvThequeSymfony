<?php

namespace App\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyCustomTwigExtensions extends AbstractExtension
{
 public function getFilters(): array
 {
     return [
         new TwigFilter(name:'defaultImage', callable:null)
     ];
 }
 public function defaultImage(string $path): string{
     if (strlen(trim($path)) == 0){
         return 'image.jpg';
     }
     return $path;
 }
}
