<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Controller;

use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\DocumentTypeFormType;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\CreateDocumentTypeRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\DeleteDocumentTypeRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\IndexDocumentTypeRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\ReadDocumentTypeRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Request\UpdateDocumentTypeRequest;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\UseCase\CreateDocumentTypeUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\UseCase\DeleteDocumentTypeUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\UseCase\IndexDocumentTypeUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\UseCase\ReadDocumentTypeUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\UseCase\UpdateDocumentTypeUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class DocumentTypeController extends AbstractController
{
    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_DOCUMENTTYPE_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexDocumentTypeUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest() ? '@FlexPHPLocation/documentType/_ajax.html.twig' : '@FlexPHPLocation/documentType/index.html.twig';

        $request = new IndexDocumentTypeRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'documentTypes' => $response->documentTypes,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_DOCUMENTTYPE_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(DocumentTypeFormType::class);

        return $this->render('@FlexPHPLocation/documentType/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_DOCUMENTTYPE_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateDocumentTypeUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(DocumentTypeFormType::class);
        $form->handleRequest($request);

        $request = new CreateDocumentTypeRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'documentType'));

        return $this->redirectToRoute('flexphp.location.document-types.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_DOCUMENTTYPE_READ')", statusCode=401)
     */
    public function read(ReadDocumentTypeUseCase $useCase, string $id): Response
    {
        $request = new ReadDocumentTypeRequest($id);

        $response = $useCase->execute($request);

        if (!$response->documentType->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPLocation/documentType/show.html.twig', [
            'documentType' => $response->documentType,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_DOCUMENTTYPE_UPDATE')", statusCode=401)
     */
    public function edit(ReadDocumentTypeUseCase $useCase, string $id): Response
    {
        $request = new ReadDocumentTypeRequest($id);

        $response = $useCase->execute($request);

        if (!$response->documentType->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(DocumentTypeFormType::class, $response->documentType);

        return $this->render('@FlexPHPLocation/documentType/edit.html.twig', [
            'documentType' => $response->documentType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_DOCUMENTTYPE_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateDocumentTypeUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $form = $this->createForm(DocumentTypeFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateDocumentTypeRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'documentType'));

        return $this->redirectToRoute('flexphp.location.document-types.index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_DOCUMENTTYPE_DELETE')", statusCode=401)
     */
    public function delete(DeleteDocumentTypeUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $request = new DeleteDocumentTypeRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'documentType'));

        return $this->redirectToRoute('flexphp.location.document-types.index');
    }
}
