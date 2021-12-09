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

use FlexPHP\Bundle\LocationBundle\Domain\Country\CountryFormType;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\CreateCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\DeleteCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\IndexCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\ReadCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\UpdateCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\UseCase\CreateCountryUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\Country\UseCase\DeleteCountryUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\Country\UseCase\IndexCountryUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\Country\UseCase\ReadCountryUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\Country\UseCase\UpdateCountryUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CountryController extends AbstractController
{
    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_COUNTRY_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexCountryUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest() ? '@FlexPHPLocation/country/_ajax.html.twig' : '@FlexPHPLocation/country/index.html.twig';

        $request = new IndexCountryRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'countries' => $response->countries,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_COUNTRY_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(CountryFormType::class);

        return $this->render('@FlexPHPLocation/country/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_COUNTRY_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateCountryUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(CountryFormType::class);
        $form->handleRequest($request);

        $request = new CreateCountryRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'country'));

        return $this->redirectToRoute('flexphp.location.countries.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_COUNTRY_READ')", statusCode=401)
     */
    public function read(ReadCountryUseCase $useCase, int $id): Response
    {
        $request = new ReadCountryRequest($id);

        $response = $useCase->execute($request);

        if (!$response->country->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPLocation/country/show.html.twig', [
            'country' => $response->country,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_COUNTRY_UPDATE')", statusCode=401)
     */
    public function edit(ReadCountryUseCase $useCase, int $id): Response
    {
        $request = new ReadCountryRequest($id);

        $response = $useCase->execute($request);

        if (!$response->country->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CountryFormType::class, $response->country);

        return $this->render('@FlexPHPLocation/country/edit.html.twig', [
            'country' => $response->country,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_COUNTRY_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateCountryUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        $form = $this->createForm(CountryFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateCountryRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'country'));

        return $this->redirectToRoute('flexphp.location.countries.index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_COUNTRY_DELETE')", statusCode=401)
     */
    public function delete(DeleteCountryUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        $request = new DeleteCountryRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'country'));

        return $this->redirectToRoute('flexphp.location.countries.index');
    }
}
