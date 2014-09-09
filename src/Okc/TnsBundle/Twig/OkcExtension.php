<?php

namespace Okc\TnsBundle\Twig;

class OkcExtension extends \Twig_Extension
{

  public function getFilters()
  {
    return array(
      new \Twig_SimpleFilter('euros', array($this, 'euros')),
    );
  }

  public function euros($number, $decimals = 2, $decPoint = ',', $thousandsSep = ' ', $sigle = ' €')
  {
    $price = number_format($number, $decimals, $decPoint, $thousandsSep);
    $price = $price . $sigle;
    return $price;
  }

  public function getName()
  {
    return 'okc_extension';
  }

}