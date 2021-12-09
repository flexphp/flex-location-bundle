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

use FlexPHP\Bundle\LocationBundle\Domain\City\CityFormType;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\CreateCityRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\DeleteCityRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\FindCityStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\IndexCityRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\ReadCityRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\UpdateCityRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\UseCase\CreateCityUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\City\UseCase\DeleteCityUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\City\UseCase\FindCityStateUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\City\UseCase\IndexCityUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\City\UseCase\ReadCityUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\City\UseCase\UpdateCityUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CityController extends AbstractController
{
    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CITY_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexCityUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest() ? '@FlexPHPLocation/city/_ajax.html.twig' : '@FlexPHPLocation/city/index.html.twig';

        $request = new IndexCityRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'cities' => $response->cities,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CITY_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(CityFormType::class);

        return $this->render('@FlexPHPLocation/city/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CITY_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateCityUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(CityFormType::class);
        $form->handleRequest($request);

        $request = new CreateCityRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'city'));

        return $this->redirectToRoute('flexphp.location.cities.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CITY_READ')", statusCode=401)
     */
    public function read(ReadCityUseCase $useCase, int $id): Response
    {
        $request = new ReadCityRequest($id);

        $response = $useCase->execute($request);

        if (!$response->city->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPLocation/city/show.html.twig', [
            'city' => $response->city,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CITY_UPDATE')", statusCode=401)
     */
    public function edit(ReadCityUseCase $useCase, int $id): Response
    {
        $request = new ReadCityRequest($id);

        $response = $useCase->execute($request);

        if (!$response->city->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CityFormType::class, $response->city);

        return $this->render('@FlexPHPLocation/city/edit.html.twig', [
            'city' => $response->city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CITY_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateCityUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        $form = $this->createForm(CityFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateCityRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'city'));

        return $this->redirectToRoute('flexphp.location.cities.index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CITY_DELETE')", statusCode=401)
     */
    public function delete(DeleteCityUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        $request = new DeleteCityRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'city'));

        return $this->redirectToRoute('flexphp.location.cities.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_STATE_INDEX')", statusCode=401)
     */
    public function findState(Request $request, FindCityStateUseCase $useCase): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $request = new FindCityStateRequest($request->request->all());

        $response = $useCase->execute($request);

        return new JsonResponse([
            'results' => $response->states,
            'pagination' => ['more' => false],
        ]);
    }
}
