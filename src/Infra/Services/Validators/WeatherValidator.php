<?php

declare(strict_types=1);

namespace App\Infra\Services\Validators;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Collection;

class WeatherValidator
{
    private ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    public function validate(array $data): array
    {
        $errors = [];
        foreach ($this->validator->validate($data, $this->getRules()) as $result) {
            /** @var ConstraintViolationInterface $result */
            $errors[] = sprintf('%s: %s', $result->getPropertyPath(), $result->getMessage());
        }

        return $errors;
    }

    /**
     * @return Collection[]
     */
    private function getRules(): array
    {
        return [
            new Collection([
                'fields' => [
                    'name' => new NotBlank(),
                    'weather' => new Collection([
                        'fields' => [],
                        'allowExtraFields' => true,
                        'allowMissingFields' => false
                    ])
                ],
                'allowExtraFields' => true,
                'allowMissingFields' => false
            ])
        ];
    }
}