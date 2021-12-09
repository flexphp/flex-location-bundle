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
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\UpdateDocumentTypeRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Response\UpdateDocumentTypeResponse;

final class UpdateDocumentTypeUseCase
{
    private DocumentTypeRepository $documentTypeRepository;

    public function __construct(DocumentTypeRepository $documentTypeRepository)
    {
        $this->documentTypeRepository = $documentTypeRepository;
    }

    public function execute(UpdateDocumentTypeRequest $request): UpdateDocumentTypeResponse
    {
        return new UpdateDocumentTypeResponse($this->documentTypeRepository->change($request));
    }
}
