<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use function file_exists;

final class MoviePosterExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly string $assetsImagesMoviePath,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof MoviePosterExists) {
            throw new UnexpectedTypeException($constraint, MoviePosterExists::class);
        }

        if (null === $value) {
            return;
        }

        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new UnexpectedValueException($value, 'string');
        }

        $stringValue = (string) $value;

        $filePath = "{$this->assetsImagesMoviePath}{$stringValue}";

        if (file_exists($filePath)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ filename }}', $stringValue)
            ->addViolation()
        ;
    }
}
