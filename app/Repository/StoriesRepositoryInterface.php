<?php

namespace App\Repository;

interface StoriesRepositoryInterface
{
        public function index() ;

        public function showMyStory() ;

        public function seeStory($request) ;

        public function store($request) ;

        public function delete($story) ;
}
