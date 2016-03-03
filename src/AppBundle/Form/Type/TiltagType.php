<?php
/**
 * @file
 * @TODO: Missing description.
 */

namespace AppBundle\Form\Type;

use AppBundle\AppBundle;
use AppBundle\Form\Type\RisikovurderingType;
use AppBundle\Entity\Tiltag;
use AppBundle\Entity\PumpeTiltag;
use AppBundle\Entity\SolcelleTiltag;
use AppBundle\Entity\TekniskIsoleringTiltag;
use AppBundle\Entity\KlimaskaermTiltag;
use AppBundle\Entity\SpecialTiltag;
use AppBundle\Entity\VindueTiltag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class TiltagType
 * @package AppBundle\Form
 */
class TiltagType extends AbstractType {
  protected $tiltag;
  protected $authorizationChecker;

  public function __construct(Tiltag $tiltag, AuthorizationCheckerInterface $authorizationChecker) {
    $this->tiltag = $tiltag;
    $this->authorizationChecker = $authorizationChecker;
  }

  /**
   * @TODO: Missing description.
   *
   * @param FormBuilderInterface $builder
   * @TODO: Missing description.
   * @param array $options
   * @TODO: Missing description.
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    if ($this->authorizationChecker && !$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
      $builder->add('tilvalgtAfRaadgiver');
    }
    else {
      $builder->add('tilvalgtAfAaPlus')
        ->add('tilvalgtAfMagistrat')
        ->add('tilvalgtbegrundelse');
    }
    $builder->add('title')
      ->add('faktorForReinvesteringer');

    if ($this->authorizationChecker && $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
      $builder
        ->add('genopretning')
        ->add('modernisering');
    }

    $builder->add('forsyningVarme', 'entity', array(
      'class' => 'AppBundle:Energiforsyning',
      'choices' => $this->tiltag->getRapport()->getEnergiforsyninger(),
      'required' => FALSE,
    ))
      ->add('forsyningEl', 'entity', array(
        'class' => 'AppBundle:Energiforsyning',
        'choices' => $this->tiltag->getRapport()->getEnergiforsyninger(),
        'required' => FALSE,
      ))
      ->add('beskrivelseNuvaerende')
      ->add('beskrivelseForslag')
      ->add('beskrivelseOevrige')
      ->add('risikovurdering')
      ->add('placering')
      ->add('beskrivelseDriftOgVedligeholdelse')
      ->add('indeklima')
      ->add('reelAnlaegsinvestering');

    $builder->add('risikovurderingTeknisk', new RisikovurderingType(), array());
    $builder->add('risikovurderingBrugsmoenster', new RisikovurderingType(), array());
    $builder->add('risikovurderingDatagrundlag', new RisikovurderingType(), array());
    $builder->add('risikovurderingDiverse', new RisikovurderingType(), array());
    $builder->add('risikovurderingAendringIBesparelseFaktor', 'percent', array('required' => FALSE))
            ->add('risikovurderingOekonomiskKompenseringIftInvesteringFaktor', 'percent', array('required' => FALSE));

    if ($this->tiltag instanceof TekniskIsoleringTiltag) {
      $builder
        ->add('besparelseDriftOgVedligeholdelse')
        ->add('besparelseStrafafkoelingsafgift')
        ->add('levetid');
    }
    if ($this->tiltag instanceof PumpeTiltag) {
      $builder
        ->add('besparelseDriftOgVedligeholdelse')
        ->add('levetid');
    }
    if ($this->tiltag instanceof VindueTiltag) {
      $builder
        ->add('besparelseDriftOgVedligeholdelse');
    }
    elseif ($this->tiltag instanceof SolcelleTiltag) {
      $builder
        ->add('levetid');
    }
    elseif ($this->tiltag instanceof KlimaskaermTiltag) {
      $builder
        ->add('besparelseDriftOgVedligeholdelse');
    }
    elseif ($this->tiltag instanceof SpecialTiltag) {
      $builder
        ->add('besparelseDriftOgVedligeholdelse')
        ->add('besparelseStrafafkoelingsafgift')
        ->add('anlaegsInvestering')
        ->add('besparelseGUF')
        ->add('besparelseGAF')
        ->add('besparelseEl')
        ->add('yderligereBesparelse')
        ->add('levetid');

      $builder->add('primaerEnterprise', 'choice',
        array(
          'choices' => array(
            'el' => 'El',
            't/i' => 'Tømrer/Isolatør',
            've' => 'VE',
            'vvs' => 'VVS',
            'hh' => 'Hårde hvidevarer',
            'a' => 'Automatik',
            'ia' => 'Interne i AAK'
          ),
          'required' => false,
          'empty_value' => '--'
        ))
        ->add('tiltagskategori');
    }
  }

  /**
   * @TODO: Missing description.
   *
   * @param OptionsResolver $resolver
   * @TODO: Missing description.
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\Tiltag'
    ));
  }

  /**
   * @TODO: Missing description.
   *
   * @return string
   * @TODO: Missing description.
   */
  public function getName() {
    return 'appbundle_tiltag';
  }
}
