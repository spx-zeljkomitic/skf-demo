<?php

namespace App\Model;

use Pagerfanta\Pagerfanta;

interface AdminConfigInterface
{
    public function getName(): string;

    public function getPagination(): Pagerfanta;

    public function getColumns(): array;
}

