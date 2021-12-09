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

interface DocumentTypeGateway
{
    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array;

    public function push(DocumentType $documentType): string;

    public function get(DocumentType $documentType): array;

    public function shift(DocumentType $documentType): void;

    public function pop(DocumentType $documentType): void;
}
