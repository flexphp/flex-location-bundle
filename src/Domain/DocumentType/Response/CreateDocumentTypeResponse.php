<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Response;

use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\DocumentType;
use FlexPHP\Messages\ResponseInterface;

final class CreateDocumentTypeResponse implements ResponseInterface
{
    public $documentType;

    public function __construct(DocumentType $documentType)
    {
        $this->documentType = $documentType;
    }
}
