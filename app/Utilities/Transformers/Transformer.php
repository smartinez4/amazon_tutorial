<?php

/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 19/06/2018
 * Time: 14:21
 */

namespace App\Utilities\Transformers;

abstract class Transformer {

    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    public abstract function transform($item);

}