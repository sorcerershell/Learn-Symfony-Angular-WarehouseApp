<?php

namespace AppBundle\Controller\Traits;


trait FractalTrait {

    public function transform(\League\Fractal\Resource\ResourceInterface $resource){
        $manager = new \League\Fractal\Manager();
        return $manager->createData($resource);
    }
}