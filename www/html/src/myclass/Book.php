<?php

class Book{

    public function __construct(
        public string $title,
        public int $price
    )
    {}
}