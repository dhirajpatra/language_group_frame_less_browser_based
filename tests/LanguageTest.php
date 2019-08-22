<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Curl\Curl;
use PHPUnit\Framework\TestCase;
use LanguageApp\Language;
use Faker\Factory;

final class LanguageTest extends TestCase
{
    protected $curl;
    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->curl = new Curl();  
        $this->faker = Factory::create();    
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * This will test getLanguageDetails method with stub
     */
    public function testGetLanguageDetails(): void
    {
        $mock = $this->createMock(Language::class);
        $mock->expects($this->once())
            ->method('getLanguageDetails')
            ->with('Germany')
            ->willReturn('Germany speaks same language with these countries: Austria, Belgium, Holy See, Liechtenstein, Luxembourg, Switzerland');
            
        $this->assertSame('Germany speaks same language with these countries: Austria, Belgium, Holy See, Liechtenstein, Luxembourg, Switzerland', $mock->getLanguageDetails('Germany'));
        
    }

    /**
     * this will test getSimilarity method with stub
     */
    public function testGetSimilarity(): void
    {
        $mock = $this->createMock(Language::class);
        $mock->expects($this->once())
            ->method('getSimilarity')
            ->with('Germany', 'Belgium')
            ->willReturn('Germany and Belgium speak the same language');
            
        $this->assertSame('Germany and Belgium speak the same language', $mock->getSimilarity('Germany', 'Belgium'));
    } 

    /**
     * this will test getLanguageDetails with mock
     */
    public function testGetLanguageDetailsMock(): void
    {
        $country = $this->faker->country;
        $language = $this->getMockBuilder(Language::class)
                        ->setConstructorArgs([$this->curl])
                        ->setMethods(['getLanguageDetails'])
                        ->getMock();
        $language->expects($this->once())
            ->method('getLanguageDetails')
            ->with($this->equalTo($country));

        $language->getLanguageDetails($country);
    }

    /**
     * this will test getSimilarity method with mock
     */
    public function testGetSimilarityMock(): void
    {
        $countryFirst = $this->faker->country;
        $countrySecond = $this->faker->country;

        $language = $this->getMockBuilder(Language::class)
                        ->setConstructorArgs([$this->curl])
                        ->setMethods(['getSimilarity'])
                        ->getMock();
        $language->expects($this->once())
            ->method('getSimilarity')
            ->with($countryFirst, $countrySecond);

        $language->getSimilarity($countryFirst, $countrySecond);
    }
}