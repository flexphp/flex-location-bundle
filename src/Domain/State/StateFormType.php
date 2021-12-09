<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\State;

use App\Form\Type\Select2Type;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\ReadCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\UseCase\ReadCountryUseCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class StateFormType extends AbstractType
{
    private ReadCountryUseCase $readCountryUseCase;

    private UrlGeneratorInterface $router;

    public function __construct(
        ReadCountryUseCase $readCountryUseCase,
        UrlGeneratorInterface $router
    ) {
        $this->readCountryUseCase = $readCountryUseCase;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countryIdModifier = function (FormInterface $form, ?int $value): void {
            $choices = null;

            if (!empty($value)) {
                $response = $this->readCountryUseCase->execute(new ReadCountryRequest($value));

                if ($response->country->id()) {
                    $choices = [$response->country->name() => $value];
                }
            }

            $form->add('countryId', Select2Type::class, [
                'label' => 'label.countryId',
                'required' => true,
                'attr' => [
                    'data-autocomplete-url' => $this->router->generate('flexphp.location.states.find.countries'),
                ],
                'choices' => $choices,
                'data' => $value,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($countryIdModifier) {
            if (!$event->getData()) {
                return null;
            }

            $countryIdModifier($event->getForm(), $event->getData()->countryId());
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($countryIdModifier): void {
            $countryIdModifier($event->getForm(), (int)$event->getData()['countryId'] ?: null);
        });

        $builder->add('countryId', Select2Type::class, [
            'label' => 'label.countryId',
            'required' => true,
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('flexphp.location.states.find.countries'),
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
            'translation_domain' => 'state',
        ]);
    }
}
