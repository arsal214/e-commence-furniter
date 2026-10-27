<?php

/*
| Upload rules per place an image appears. One source of truth: the upload forms validate
| against it and the storefront draws each slot with the same ratio, so what a seller
| uploads is what shoppers see, never stretched or cropped.
|   ratio     width:height the picture must have (within `tolerance`)
|   min       smallest accepted width x height in pixels
*/
return [
    'max_side'  => 6000,
    'tolerance' => 0.02,
    'slots' => [
        'product'  => ['label' => 'Product photo',  'ratio' => [1, 1],  'min' => [1000, 1000]],
        'logo'     => ['label' => 'Store logo',     'ratio' => [1, 1],  'min' => [400, 400]],
        'banner'   => ['label' => 'Store banner',   'ratio' => [16, 9], 'min' => [1600, 900]],
        'hero'     => ['label' => 'Hero banner',    'ratio' => [16, 7], 'min' => [1600, 700]],
        'category' => ['label' => 'Category image', 'ratio' => [1, 1],  'min' => [600, 600]],
        'review'   => ['label' => 'Review photo',   'ratio' => [1, 1],  'min' => [600, 600]],
    ],
];
