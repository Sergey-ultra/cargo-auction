<?php

declare(strict_types=1);

namespace App\ApiGateway\Request;

//use Jawira\CaseConverter\Convert;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequest
{
    private array $errors;
    public function __construct(
        protected ValidatorInterface $validator,
        protected RequestStack $requestStack,
    ) {
        $this->populate();
        $this->validate();
    }

    private function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    protected function populate(): void
    {
        $request = $this->getRequest();

        $reflection = new \ReflectionClass($this);

        foreach ($request->request as $attribute => $value) {
            //$attribute = self::camelCase($property);
            if (\property_exists($this, $attribute)) {
                $reflectionProperty = $reflection->getProperty($attribute);
                $reflectionProperty->setValue($this, $value);
            }
        }
    }

    protected function validate(): void
    {
        $violations = $this->validator->validate($this);
        if (count($violations) < 1) {
            return;
        }

        /** @var \Symfony\Component\Validator\ConstraintViolation */
        foreach ($violations as $violation) {
            $this->errors[$violation->getPropertyPath()] =  $violation->getMessage();
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return 0 === \count($this->getErrors());
    }
}

