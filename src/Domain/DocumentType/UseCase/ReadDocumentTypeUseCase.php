<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\DocumentType\UseCase;

use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\DocumentTypeRepository;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\ReadDocumentTypeRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Response\ReadDocumentTypeResponse;

final class ReadDocumentTypeUseCase
{
    private DocumentTypeRepository $documentTypeRepository;

    public function __construct(DocumentTypeRepository $documentTypeRepository)
    {
        $this->documentTypeRepository = $documentTypeRepository;
    }

    public function execute(ReadDocumentTypeRequest $request): ReadDocumentTypeResponse
    {
        $documentType = $this->documentTypeRepository->getById($request);

        return new ReadDocumentTypeResponse($documentType);
    }
}
