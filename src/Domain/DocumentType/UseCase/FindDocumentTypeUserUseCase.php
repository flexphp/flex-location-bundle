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
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\FindDocumentTypeUserRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Response\FindDocumentTypeUserResponse;

final class FindDocumentTypeUserUseCase
{
    private DocumentTypeRepository $documentTypeRepository;

    public function __construct(DocumentTypeRepository $documentTypeRepository)
    {
        $this->documentTypeRepository = $documentTypeRepository;
    }

    public function execute(FindDocumentTypeUserRequest $request): FindDocumentTypeUserResponse
    {
        $users = $this->documentTypeRepository->findUsersBy($request);

        return new FindDocumentTypeUserResponse($users);
    }
}
