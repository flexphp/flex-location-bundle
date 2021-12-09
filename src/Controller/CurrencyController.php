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

use FlexPHP\Bundle\LocationBundle\Domain\Currency\CurrencyFormType;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\CreateCurrencyRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\DeleteCurrencyRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\IndexCurrencyRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\ReadCurrencyRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\UpdateCurrencyRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\UseCase\CreateCurrencyUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\UseCase\DeleteCurrencyUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\UseCase\IndexCurrencyUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\UseCase\ReadCurrencyUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\UseCase\UpdateCurrencyUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CurrencyController extends AbstractController
{
    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexCurrencyUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest() ? '@FlexPHPLocation/currency/_ajax.html.twig' : '@FlexPHPLocation/currency/index.html.twig';

        $request = new IndexCurrencyRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'currencies' => $response->currencies,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(CurrencyFormType::class);

        return $this->render('@FlexPHPLocation/currency/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateCurrencyUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(CurrencyFormType::class);
        $form->handleRequest($request);

        $request = new CreateCurrencyRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'currency'));

        return $this->redirectToRoute('flexphp.location.currencies.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_READ')", statusCode=401)
     */
    public function read(ReadCurrencyUseCase $useCase, string $id): Response
    {
        $request = new ReadCurrencyRequest($id);

        $response = $useCase->execute($request);

        if (!$response->currency->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPLocation/currency/show.html.twig', [
            'currency' => $response->currency,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_UPDATE')", statusCode=401)
     */
    public function edit(ReadCurrencyUseCase $useCase, string $id): Response
    {
        $request = new ReadCurrencyRequest($id);

        $response = $useCase->execute($request);

        if (!$response->currency->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CurrencyFormType::class, $response->currency);

        return $this->render('@FlexPHPLocation/currency/edit.html.twig', [
            'currency' => $response->currency,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateCurrencyUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $form = $this->createForm(CurrencyFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateCurrencyRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'currency'));

        return $this->redirectToRoute('flexphp.location.currencies.index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_CURRENCY_DELETE')", statusCode=401)
     */
    public function delete(DeleteCurrencyUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $request = new DeleteCurrencyRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'currency'));

        return $this->redirectToRoute('flexphp.location.currencies.index');
    }
}
