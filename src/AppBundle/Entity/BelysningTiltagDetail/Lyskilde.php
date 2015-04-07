<?php
/**
 * @file
 * @TODO: Missing description.
 */

namespace AppBundle\Entity\BelysningTiltagDetail;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * BelysningTiltagDetail\Lyskilde
 *
 * @ORM\Table("BelysningTiltagDetail_Lyskilde")
 * @ORM\Entity
 */
class Lyskilde {
  use TimestampableEntity;

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="navn", type="string", length=255)
   */
  private $navn;

  /**
   * @var string
   *
   * @ORM\Column(name="type", type="string", length=255)
   */
  private $type;

  /**
   * @var string
   *
   * @ORM\Column(name="forkobling", type="string", length=255)
   */
  private $forkobling;

  /**
   * @var double
   *
   * @ORM\Column(name="udgift", type="decimal", scale=2)
   */
  private $udgift;

  /**
   * @var integer
   *
   * @ORM\Column(name="levetid", type="integer")
   */
  private $levetid;


  /**
   * Get id
   *
   * @return integer
   */
  final public function getId() {
    return $this->id;
  }

  public function setNavn($navn) {
    $this->navn = $navn;
    return $this;
  }

  public function getNavn() {
    return $this->navn;
  }

  public function setType($type) {
    $this->type = $type;
    return $this;
  }

  public function getType() {
    return $this->type;
  }

  public function setForkobling($forkobling) {
    $this->forkobling = $forkobling;
    return $this;
  }

  public function getForkobling() {
    return $this->forkobling;
  }

  public function setUdgift($udgift) {
    $this->udgift = $udgift;
    return $this;
  }

  public function getUdgift() {
    return $this->udgift;
  }

  public function setLevetid($levetid) {
    $this->levetid = $levetid;
    return $this;
  }

  public function getLevetid() {
    return $this->levetid;
  }

  public function __toString() {
    return $this->navn;
  }

}