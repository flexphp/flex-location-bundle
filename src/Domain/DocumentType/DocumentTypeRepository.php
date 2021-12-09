<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\DocumentType;

use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\CreateDocumentTypeRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\DeleteDocumentTypeRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\IndexDocumentTypeRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\ReadDocumentTypeRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\UpdateDocumentTypeRequest;

final class DocumentTypeRepository
{
    private DocumentTypeGateway $gateway;

    public function __construct(DocumentTypeGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<DocumentType>
     */
    public function findBy(IndexDocumentTypeRequest $request): array
    {
        return \array_map(function (array $documentType) {
            return (new DocumentTypeFactory())->make($documentType);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateDocumentTypeRequest $request): DocumentType
    {
        $documentType = (new DocumentTypeFactory())->make($request);

        $documentType->setId($this->gateway->push($documentType));

        return $documentType;
    }

    public function getById(ReadDocumentTypeRequest $request): DocumentType
    {
        $factory = new DocumentTypeFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdateDocumentTypeRequest $request): DocumentType
    {
        $documentType = (new DocumentTypeFactory())->make($request);

        $this->gateway->shift($documentType);

        return $documentType;
    }

    public function remove(DeleteDocumentTypeRequest $request): DocumentType
    {
        $factory = new DocumentTypeFactory();
        $data = $this->gateway->get($factory->make($request));

        $documentType = $factory->make($data);

        $this->gateway->pop($documentType);

        return $documentType;
    }
}
