<?php

namespace App\Form\Model;

class SearchMemberModel
{
    public function __construct(
        public ?string $slug = null,
    )
    {}
}