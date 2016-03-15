<?php
/**
 * @file
 * Contains BaselineTest - test cases for Baseline entity.
 */

namespace AppBundle\Tests\Entity;

use AppBundle\DBAL\Types\Baseline\GUFFastsaettesEfterType;
use AppBundle\Entity\ELOKategori;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Entity\Baseline;

class BaselineTest extends KernelTestCase {
  public function testCalculateElPrimaer() {
    $baseline = new Baseline();
    $baseline->setElForbrugsdataPrimaer1Forbrug(32.0);
    $baseline->setElForbrugsdataPrimaer3Forbrug(64.0);
    $baseline->calculate();

    $this->assertEquals(48.0, $baseline->getElForbrugsdataPrimaerGennemsnit());
    $this->assertEquals(null, $baseline->getElForbrugsdataPrimaerNoegetal());

    $baseline->setArealTilNoegletalsanalyse(96.0);
    $baseline->calculate();

    $this->assertEquals(0.5, $baseline->getElForbrugsdataPrimaerNoegetal());
  }

  public function testCalculateElSekundaer() {
    $baseline = new Baseline();
    $baseline->setElForbrugsdataSekundaer1Forbrug(32.0);
    $baseline->setElForbrugsdataSekundaer3Forbrug(64.0);
    $baseline->calculate();

    $this->assertEquals(48.0, $baseline->getElForbrugsdataSekundaerGennemsnit());
    $this->assertEquals(null, $baseline->getElForbrugsdataSekundaerNoegetal());

    $baseline->setArealTilNoegletalsanalyse(96.0);
    $baseline->calculate();

    $this->assertEquals(0.5, $baseline->getElForbrugsdataSekundaerNoegetal());
  }

  public function testCalculateElBaselineNoegletalForEjendom() {
    $baseline = new Baseline();
    $baseline->setArealTilNoegletalsanalyse(160.0);
    $baseline->calculate();

    $this->assertEquals(null, $baseline->getElBaselineNoegletalForEjendom());

    $baseline->setElBaselineFastsatForEjendom(80.0);
    $baseline->calculate();

    $this->assertEquals(0.5, $baseline->getElBaselineNoegletalForEjendom());
  }

  public function testCalculateGUFRegAar() {
    $eloKategori = new ELOKategori();
    $eloKategori->setAndelVarmeGUFFaktor(.2);

    $baseline = new Baseline();
    $baseline->setEloKategori($eloKategori);
    $baseline->calculate();

    $this->assertNull($baseline->getVarmeForbrugsdataPrimaer1GUFRegAar());
    $this->assertInstanceOf(ELOKategori::class, $baseline->getEloKategori());

    $baseline->setVarmeForbrugsdataPrimaer1Forbrug(50.0);
    $baseline->setVarmeForbrudsdataPrimaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
    $baseline->calculate();

    $this->assertEquals(10.0, $baseline->getVarmeForbrugsdataPrimaer1GUFRegAar());

    $baseline->setVarmeForbrugsdataPrimaer1SamletVarmeforbrugJuniJuliAugust(12.0);
    $baseline->calculate();

    $this->assertEquals(10.0, $baseline->getVarmeForbrugsdataPrimaer1GUFRegAar());

    $baseline->setVarmeForbrudsdataPrimaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::SAMLET_MAANEDSFORBRUG_FOR_JUNI_JULI_AUGUST);
    $baseline->calculate();

    $this->assertEquals(48.0, $baseline->getVarmeForbrugsdataPrimaer1GUFRegAar());
  }

  public function testCalculateGAFRegAar() {
    $eloKategori = new ELOKategori();
    $eloKategori->setAndelVarmeGUFFaktor(.2);

    $baseline = new Baseline();
    $baseline->setEloKategori($eloKategori);
    $baseline->calculate();

    $this->assertNull(null, $baseline->getVarmeForbrugsdataPrimaer1GAFRegAar());

    $baseline->setVarmeForbrugsdataPrimaer1Forbrug(50.0);
    $baseline->setVarmeForbrudsdataPrimaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
    $baseline->calculate();

    $this->assertEquals(40.0, $baseline->getVarmeForbrugsdataPrimaer1GAFRegAar());
  }

  public function testCalculateVarmeForbrugsdata() {
    $eloKategori = new ELOKategori();
    $eloKategori->setAndelVarmeGUFFaktor(.2);

    $baseline = new Baseline();
    $baseline->setEloKategori($eloKategori);
    $baseline->calculate();

    $baseline->setArealTilNoegletalsanalyse(95.0);

    $baseline->setVarmeForbrugsdataPrimaer1Forbrug(50.0);
    $baseline->setVarmeForbrudsdataPrimaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
    $baseline->setVarmeForbrugsdataPrimaer1GDPeriode(1500.0);

    $baseline->setVarmeForbrugsdataPrimaer2Forbrug(100.0);
    $baseline->setVarmeForbrugsdataPrimaer2GDPeriode(3000.0);

    $baseline->calculate(3000.0);

    $this->assertEquals(80.0, $baseline->getVarmeForbrugsdataPrimaer1GAFnormal());
    $this->assertEquals(90.0, $baseline->getVarmeForbrugsdataPrimaer1ForbrugKlimakorrigeret());

    $this->assertEquals(80.0, $baseline->getVarmeForbrugsdataPrimaer2GAFnormal());
    $this->assertEquals(100.0, $baseline->getVarmeForbrugsdataPrimaer2ForbrugKlimakorrigeret());

    // Assert averages
    $this->assertEquals(80.0, $baseline->getVarmeForbrugsdataPrimaerGAFGennemsnit());
    $this->assertEquals(15.0, $baseline->getVarmeForbrugsdataPrimaerGUFGennemsnit());
    $this->assertEquals(95.0, $baseline->getVarmeForbrugsdataPrimaerGennemsnitKlimakorrigeret());

    $this->assertEquals(1.0, $baseline->getVarmeForbrugsdataPrimaerNoegletal());
  }

  public function testCalculateSekundaerGUFRegAar() {
    $eloKategori = new ELOKategori();
    $eloKategori->setAndelVarmeGUFFaktor(.2);

    $baseline = new Baseline();
    $baseline->setEloKategori($eloKategori);
    $baseline->calculate();

    $this->assertNull($baseline->getVarmeForbrugsdataSekundaer1GUFRegAar());
    $this->assertInstanceOf(ELOKategori::class, $baseline->getEloKategori());

    $baseline->setVarmeForbrugsdataSekundaer1Forbrug(50.0);
    $baseline->setVarmeForbrudsdataSekundaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
    $baseline->calculate();

    $this->assertEquals(10.0, $baseline->getVarmeForbrugsdataSekundaer1GUFRegAar());

    $baseline->setVarmeForbrugsdataSekundaer1SamletVarmeforbrugJuniJuliAugust(12.0);
    $baseline->calculate();

    $this->assertEquals(10.0, $baseline->getVarmeForbrugsdataSekundaer1GUFRegAar());

    $baseline->setVarmeForbrudsdataSekundaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::SAMLET_MAANEDSFORBRUG_FOR_JUNI_JULI_AUGUST);
    $baseline->calculate();

    $this->assertEquals(48.0, $baseline->getVarmeForbrugsdataSekundaer1GUFRegAar());
  }

  public function testCalculateSekundaerGAFRegAar() {
    $eloKategori = new ELOKategori();
    $eloKategori->setAndelVarmeGUFFaktor(.2);

    $baseline = new Baseline();
    $baseline->setEloKategori($eloKategori);
    $baseline->calculate();

    $this->assertNull(null, $baseline->getVarmeForbrugsdataSekundaer1GAFRegAar());

    $baseline->setVarmeForbrugsdataSekundaer1Forbrug(50.0);
    $baseline->setVarmeForbrudsdataSekundaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
    $baseline->calculate();

    $this->assertEquals(40.0, $baseline->getVarmeForbrugsdataSekundaer1GAFRegAar());
  }

  public function testCalculateSekundaerVarmeForbrugsdata() {
    $eloKategori = new ELOKategori();
    $eloKategori->setAndelVarmeGUFFaktor(.2);

    $baseline = new Baseline();
    $baseline->setEloKategori($eloKategori);
    $baseline->calculate();

    $baseline->setArealTilNoegletalsanalyse(95.0);

    $baseline->setVarmeForbrugsdataSekundaer1Forbrug(50.0);
    $baseline->setVarmeForbrudsdataSekundaerGUFForbrugFastsaettesEfter(GUFFastsaettesEfterType::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL);
    $baseline->setVarmeForbrugsdataSekundaer1GDPeriode(1500.0);

    $baseline->setVarmeForbrugsdataSekundaer2Forbrug(100.0);
    $baseline->setVarmeForbrugsdataSekundaer2GDPeriode(3000.0);

    $baseline->calculate(3000.0);

    $this->assertEquals(80.0, $baseline->getVarmeForbrugsdataSekundaer1GAFnormal());
    $this->assertEquals(90.0, $baseline->getVarmeForbrugsdataSekundaer1ForbrugKlimakorrigeret());

    $this->assertEquals(80.0, $baseline->getVarmeForbrugsdataSekundaer2GAFnormal());
    $this->assertEquals(100.0, $baseline->getVarmeForbrugsdataSekundaer2ForbrugKlimakorrigeret());

    // Assert averages
    $this->assertEquals(80.0, $baseline->getVarmeForbrugsdataSekundaerGAFGennemsnit());
    $this->assertEquals(15.0, $baseline->getVarmeForbrugsdataSekundaerGUFGennemsnit());
    $this->assertEquals(95.0, $baseline->getVarmeForbrugsdataSekundaerGennemsnitKlimakorrigeret());

    $this->assertEquals(1.0, $baseline->getVarmeForbrugsdataSekundaerNoegletal());
  }

  public function testCalculateVarmeBaselineFastsatForEjendomAndCalculateVarmeBaselineNoegletalForEjendom() {
    $baseline = new Baseline();
    $baseline->setArealTilNoegletalsanalyse(200.0);
    $baseline->setVarmeGAFForbrug(50.0);
    $baseline->setVarmeGUFForbrug(50.0);
    $baseline->calculate();

    $this->assertEquals(100.0, $baseline->getVarmeBaselineFastsatForEjendom());
    $this->assertEquals(0.5, $baseline->getVarmeBaselineNoegletalForEjendom());
  }
}
