<?php

declare(strict_types=1);

namespace XM\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use XM\Domain\Facade\FormDataHandlerFacade;
use XM\Domain\Form\QuoteFilterForm;

class DashboardController extends AbstractController
{
    public function __construct(
        private readonly FormDataHandlerFacade $facade
    ) {
    }

    #[Route('/', name: 'app_quotes')]
    public function quotes(Request $request): Response
    {
        $responseData = [];
        $form = $this->createForm(QuoteFilterForm::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $responseData = $this->facade->handleData($data);
        }

        return $this->render('quotes/index.html.twig', [
            'filter_form' => $form->createView(),
            'data' => $responseData,
        ]);
    }
}
