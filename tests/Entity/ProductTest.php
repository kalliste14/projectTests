<?php


namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testcomputeTVAProductTypeFood()
    {
        $product = new Product('nom', PRODUCT::FOOD_PRODUCT,10);
        $result = $product->computeTVA();

        $this->assertSame(0.55, $result);
    }

    public function testcomputeTVAProductTypeNotFood()
    {
        $product = new Product('nom', 'Viande',10);
        $result = $product->computeTVA();

        $this->assertSame(1.96, $result);
    }

    public function testcomputeTVAProductWithException()
    {
        $product = new Product('nom', 'Viande',-10);
        $this->expectException('LogicException');
        $product->computeTVA();

    }

}