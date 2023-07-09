<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Calculation;
use App\Repository\CalculationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class VatCalculatorManager
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var CalculationRepository */
    private $calculationRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        CalculationRepository $calculationRepository,
    ) {
        $this->entityManager  = $entityManager;
        $this->calculationRepository   = $calculationRepository;
    }

    /**
     * @param Calculation $calculation
     *
     * @throws \Exception
     */
    public function create(Calculation $calculation)
    {
        $this->entityManager->persist($calculation);
        $this->entityManager->flush();
        return $calculation;
    }

    /**
     * @param Calculation $calculation
     *
     * @throws \Exception
     */
    public function update(Calculation $calculation)
    {
        $this->entityManager->flush();
        return $calculation;
    }

    public function getCalculationHistory()
    {
        return $this->calculationRepository->findAll();
    }

    public function clearCalculationHistory() {
        $repository = $this->entityManager->getRepository(Calculation::class);
        $calculations = $repository->findAll();

        foreach ($calculations as $calculation) {
            $this->entityManager->remove($calculation);
        }
        $this->entityManager->flush();
    }
}
