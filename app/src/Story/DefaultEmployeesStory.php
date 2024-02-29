<?php

namespace App\Story;

use App\Factory\EmployeeFactory;
use Zenstruck\Foundry\Story;

final class DefaultEmployeesStory extends Story
{
    public function build(): void
    {
        EmployeeFactory::createMany(100);
    }
}
