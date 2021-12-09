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

use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\PaymentMethodFormType;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\CreatePaymentMethodRequest;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\DeletePaymentMethodRequest;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\IndexPaymentMethodRequest;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\ReadPaymentMethodRequest;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\UpdatePaymentMethodRequest;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\UseCase\CreatePaymentMethodUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\UseCase\DeletePaymentMethodUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\UseCase\IndexPaymentMethodUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\UseCase\ReadPaymentMethodUseCase;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\UseCase\UpdatePaymentMethodUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class PaymentMethodController extends AbstractController
{
    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_PAYMENTMETHOD_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexPaymentMethodUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest() ? '@FlexPHPLocation/paymentMethod/_ajax.html.twig' : '@FlexPHPLocation/paymentMethod/index.html.twig';

        $request = new IndexPaymentMethodRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'paymentMethods' => $response->paymentMethods,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_PAYMENTMETHOD_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(PaymentMethodFormType::class);

        return $this->render('@FlexPHPLocation/paymentMethod/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_PAYMENTMETHOD_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreatePaymentMethodUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(PaymentMethodFormType::class);
        $form->handleRequest($request);

        $request = new CreatePaymentMethodRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'paymentMethod'));

        return $this->redirectToRoute('flexphp.location.payment-methods.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_PAYMENTMETHOD_READ')", statusCode=401)
     */
    public function read(ReadPaymentMethodUseCase $useCase, string $id): Response
    {
        $request = new ReadPaymentMethodRequest($id);

        $response = $useCase->execute($request);

        if (!$response->paymentMethod->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPLocation/paymentMethod/show.html.twig', [
            'paymentMethod' => $response->paymentMethod,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_PAYMENTMETHOD_UPDATE')", statusCode=401)
     */
    public function edit(ReadPaymentMethodUseCase $useCase, string $id): Response
    {
        $request = new ReadPaymentMethodRequest($id);

        $response = $useCase->execute($request);

        if (!$response->paymentMethod->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(PaymentMethodFormType::class, $response->paymentMethod);

        return $this->render('@FlexPHPLocation/paymentMethod/edit.html.twig', [
            'paymentMethod' => $response->paymentMethod,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_PAYMENTMETHOD_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdatePaymentMethodUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $form = $this->createForm(PaymentMethodFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdatePaymentMethodRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'paymentMethod'));

        return $this->redirectToRoute('flexphp.location.payment-methods.index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_PAYMENTMETHOD_DELETE')", statusCode=401)
     */
    public function delete(DeletePaymentMethodUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $request = new DeletePaymentMethodRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'paymentMethod'));

        return $this->redirectToRoute('flexphp.location.payment-methods.index');
    }
}
