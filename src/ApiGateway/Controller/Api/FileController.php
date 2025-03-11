<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\Services\FilePath\FilePathService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    #[Route('/api/file', name: 'api_file', methods: ['POST'])]
    public function saveFile(Request $request, KernelInterface $kernel, FilePathService $filePathService): JsonResponse
    {
        /** @var UploadedFile $sendFile */
        $sendFile = $request->files->get('file');
        $entity = $request->request->get('entity');

        $projectRoot = $kernel->getProjectDir();
        $folder = sprintf('images/%s', $entity);
        $destinationDirectory = sprintf("%s/public/%s", $projectRoot, $folder);

        $original = $sendFile->getClientOriginalName();

        $originalFilename = pathinfo($original, PATHINFO_FILENAME);

        //$safeFilename = $slugger->slug($originalFilename);
        $newFilename = sprintf("%s-%s.%s", $originalFilename, uniqid(), $sendFile->guessExtension());

        try {
            $filePathService->createIfNotExistFolder($destinationDirectory);
            $sendFile->move($destinationDirectory, $newFilename);
        } catch (FileException $e) {
           return $this->json(['error' => $e->getMessage()], 400);
        }

        //sleep(30);

        return $this->json([
            'success' => true,
            'data' => [
                'url' => sprintf('/%s/%s', $folder, $newFilename),
                'filename' => $newFilename,
                'content_type' => $sendFile->getClientMimeType(),
            ],
        ]);
    }
}
