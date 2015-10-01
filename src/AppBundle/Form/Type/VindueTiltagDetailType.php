<?php
/**
 * @file
 * @TODO: Missing description.
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class VindueTiltagDetailType
 * @package AppBundle\Form
 */
class VindueTiltagDetailType extends KlimaskaermTiltagDetailType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    parent::buildForm($builder, $options);
    $builder
      ->remove('andelAfArealDerEfterisoleres')
      ->remove('tIndeC')
      ->remove('tUdeC')
      ->remove('tOpvarmningTimerAar')
      ->remove('andelAfArealDerEfterisoleres')
      ;

    $this->insertAfter($builder, $builder->get('uNyWM2K'), array(
      array('solenergitransmittansEks', 'percent'),
      array('solenergitransmittansNy', 'percent'),
    ));
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\VindueTiltagDetail'
    ));
  }

  public function getName() {
    return 'appbundle_vinduetiltagdetail';
  }
}
