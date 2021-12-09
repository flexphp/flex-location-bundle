<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexDocumentTypeResponse implements ResponseInterface
{
    public $documentTypes;

    public function __construct(array $documentTypes)
    {
        $this->documentTypes = $documentTypes;
    }
}
