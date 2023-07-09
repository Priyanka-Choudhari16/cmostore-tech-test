<?php

namespace App\Controller;

use App\Entity\Calculation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Manager\VatCalculatorManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class VatCalculatorController extends AbstractController
{
    /** @var VatCalculatorManager */
    private $vatCalculatorManager;

    public function __construct(
        VatCalculatorManager $vatCalculatorManager
    ) {
        $this->vatCalculatorManager  = $vatCalculatorManager;
    }


    private $calculation = [];

    /**
     * 
     * This endpoint will render the VAT calculator form.
     *
     * @param Request $request The HTTP request object.
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $export_csv = $request->query->get("export_csv");
        return $this->render('vat_calculator/index.html.twig', [
            'calculations' => $this->calculation,
            'export_csv' => $export_csv
        ]);
    }


    /**
     * 
     * This endpoint calculates VAT based on the provided value and VAT rate.
     *
     * @param Request $request The HTTP request object.
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function calculate(Request $request)
    {
        $originalValue = $request->request->get('value');
        $vatRate = $request->request->get('vat_rate');

        try {
            // Calculate incVat values
            $incVatAmount = $originalValue * ($vatRate / 100);
            $incVatTotalValue = $originalValue + $incVatAmount;

            // Calculate exVat values
            $exVatAmount = $originalValue / (1 + ($vatRate / 100)) * ($vatRate / 100);
            $exVatTotalValue = $originalValue - $exVatAmount;

            $calculation = new Calculation();
            $calculation->setOriginalValue($originalValue);
            $calculation->setVatRate($vatRate);
            $calculation->setIncVatvalue($incVatAmount);
            $calculation->setExVatValue($exVatAmount);
            $calculation->setExVatTotalAmount($exVatTotalValue);
            $calculation->setIncVatTotalAmount($incVatTotalValue);
            $calculation->setCreatedAt(new \DateTime());
            $calculation = $this->vatCalculatorManager->create($calculation);
            if (isset($calculation)) {
                $calculations = [$calculation];
                return $this->render('vat_calculator/index.html.twig', [
                    'calculations' => $calculations,
                ]);
            } else {
                throw new Exception("error: Unable to store calculation.");
            }
        } catch (Exception $e) {
            return new JsonResponse(['errors' => [$e->getMessage()]], Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * 
     * This endpoint will clear the calculated history.
     *
     * @param Request $request The HTTP request object.
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function clearHistory()
    {
        $this->vatCalculatorManager->clearCalculationHistory();
        return $this->redirectToRoute('app_vat_calculator');
    }


    /**
     * 
     * This endpoint will fetch calculated history and created csv file in given path.
     *
     * @param Request $request The HTTP request object.
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function exportCsv()
    {
        $calculations = $this->vatCalculatorManager->getCalculationHistory();

        if (sizeof($calculations) > 0) {
            $filename = 'calculations.csv';
            $filePath = $this->getParameter('kernel.project_dir') . '/public/csv/' . $filename;
            $handle = fopen($filePath, 'w');

            fputcsv($handle, ['Original Value', 'VAT Rate', 'Ex VAT Value', 'Ex VAT Total Value', 'Inc VAT Value', 'Inc VAT Total Value']);

            foreach ($calculations as $calculation) {
                fputcsv($handle, [
                    $calculation->getOriginalValue(),
                    $calculation->getVatRate(),
                    $calculation->getExVatValue(),
                    $calculation->getExVatTotalAmount(),
                    $calculation->getIncVatValue(),
                    $calculation->getIncVatTotalAmount(),
                ]);
            }

            fclose($handle); //closing the file handler

            return $this->render('vat_calculator/index.html.twig', [
                'calculations' => $calculations,
                'export_csv' => 'data',
            ]);
        }
        return $this->redirectToRoute('app_vat_calculator', ['export_csv' => 'no data']);
    }
}
