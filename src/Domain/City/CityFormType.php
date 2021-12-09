<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\City;

use App\Form\Type\Select2Type;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\ReadStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\UseCase\ReadStateUseCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CityFormType extends AbstractType
{
    private ReadStateUseCase $readStateUseCase;

    private UrlGeneratorInterface $router;

    public function __construct(
        ReadStateUseCase $readStateUseCase,
        UrlGeneratorInterface $router
    ) {
        $this->readStateUseCase = $readStateUseCase;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $stateIdModifier = function (FormInterface $form, ?int $value): void {
            $choices = null;

            if (!empty($value)) {
                $response = $this->readStateUseCase->execute(new ReadStateRequest($value));

                if ($response->state->id()) {
                    $choices = [$response->state->name() => $value];
                }
            }

            $form->add('stateId', Select2Type::class, [
                'label' => 'label.stateId',
                'required' => true,
                'attr' => [
                    'data-autocomplete-url' => $this->router->generate('cities.find.states'),
                ],
                'choices' => $choices,
                'data' => $value,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($stateIdModifier) {
            if (!$event->getData()) {
                return null;
            }

            $stateIdModifier($event->getForm(), $event->getData()->stateId());
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($stateIdModifier): void {
            $stateIdModifier($event->getForm(), (int)$event->getData()['stateId'] ?: null);
        });

        $builder->add('stateId', Select2Type::class, [
            'label' => 'label.stateId',
            'required' => true,
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('cities.find.states'),
            ],
        ]);
        $builder->add('name', InputType\TextType::class, [
            'label' => 'label.name',
            'required' => true,
            'attr' => [
                'maxlength' => 80,
            ],
        ]);
        $builder->add('code', InputType\TextType::class, [
            'label' => 'label.code',
            'required' => true,
            'attr' => [
                'maxlength' => 10,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'city',
        ]);
    }
}
