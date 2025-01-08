<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/docss')]
#[IsGranted("ROLE_USER")]
class DocusaurusController extends AbstractController
{
    #[Route('/{req}', name: 'docusaurus', requirements: ['req' => '.+'], defaults: ['req' => 'index.html'])]
    public function index(string $req): Response
    {

        $sitePath = $this->getParameter('kernel.project_dir') . '/public/docs';
        $filePath = rtrim($sitePath . '/' . $req, '/');

        if (!file_exists($filePath)) {
            $filePath = $filePath . '/index.html';
            if (!file_exists($filePath)) {
                throw $this->createNotFoundException('The file does not exist.');
            }
        }

        $response = new BinaryFileResponse($filePath);
        $response->headers->set('Content-Type', mime_content_type($filePath));

        return $response;
    }
}
