<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\Movie;
use Attribute;
use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints\Regex;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
final class ValidMovieSlug extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Regex('#'.Movie::SLUG_FORMAT.'#'),
        ];
    }
}
