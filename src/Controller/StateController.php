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

use FlexPHP\Bundle\LocationBundle\Domain\State\Request\CreateStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\DeleteStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\FindStateCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\IndexStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\ReadStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\UpdateStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\StateFormType;
use FlexPHP\Bundle\LocationBundle\Domain\State\UseCase\CreateStateUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\State\UseCase\DeleteStateUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\State\UseCase\FindStateCountryUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\State\UseCase\IndexStateUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\State\UseCase\ReadStateUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\State\UseCase\UpdateStateUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class StateController extends AbstractController
{
    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_STATE_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexStateUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest() ? '@FlexPHPLocation/state/_ajax.html.twig' : '@FlexPHPLocation/state/index.html.twig';

        $request = new IndexStateRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'states' => $response->states,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_STATE_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(StateFormType::class);

        return $this->render('@FlexPHPLocation/state/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_STATE_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateStateUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(StateFormType::class);
        $form->handleRequest($request);

        $request = new CreateStateRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'state'));

        return $this->redirectToRoute('flexphp.location.states.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_STATE_READ')", statusCode=401)
     */
    public function read(ReadStateUseCase $useCase, int $id): Response
    {
        $request = new ReadStateRequest($id);

        $response = $useCase->execute($request);

        if (!$response->state->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPLocation/state/show.html.twig', [
            'state' => $response->state,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_STATE_UPDATE')", statusCode=401)
     */
    public function edit(ReadStateUseCase $useCase, int $id): Response
    {
        $request = new ReadStateRequest($id);

        $response = $useCase->execute($request);

        if (!$response->state->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(StateFormType::class, $response->state);

        return $this->render('@FlexPHPLocation/state/edit.html.twig', [
            'state' => $response->state,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_STATE_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateStateUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        $form = $this->createForm(StateFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateStateRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'state'));

        return $this->redirectToRoute('flexphp.location.states.index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_STATE_DELETE')", statusCode=401)
     */
    public function delete(DeleteStateUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        $request = new DeleteStateRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'state'));

        return $this->redirectToRoute('flexphp.location.states.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_COUNTRY_INDEX')", statusCode=401)
     */
    public function findCountry(Request $request, FindStateCountryUseCase $useCase): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $request = new FindStateCountryRequest($request->request->all());

        $response = $useCase->execute($request);

        return new JsonResponse([
            'results' => $response->countries,
            'pagination' => ['more' => false],
        ]);
    }
}
