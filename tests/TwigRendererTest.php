<?php
namespace Duktig\View\Adapter\Twig;

use PHPUnit\Framework\TestCase;
use Twig_Environment;
use Twig_Error_Loader;
use Duktig\Core\Exception\TemplateNotFoundException;

class TwigRendererTest extends TestCase
{
    private $template;
    private $data;
    private $mockTwigEnvironment;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->template = 'templateName.html.twig';
        $this->data = ['param1' => 'value'];
        $this->mockTwigEnvironment = \Mockery::mock(Twig_Environment::class);
    }
    
    public function tearDown()
    {
        parent::tearDown();
        if ($container = \Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        \Mockery::close();
        
        unset($this->template, $this->data, $this->mockTwigEnvironment);
    }
    
    public function testCallsTwigRenderer()
    {
        $this->mockTwigEnvironment->shouldReceive('render')->with($this->template, $this->data)
            ->andReturn('');
        $adapter = new TwigRenderer($this->mockTwigEnvironment);
        $adapter->render($this->template, $this->data);
    }
    
    public function testThrowsExceptionIfTwigCannotRender()
    {
        $this->expectException(TemplateNotFoundException::class);
        $this->expectExceptionMessage("Template 'templateName.html.twig' not found"
            ." by Twig renderer");
        $this->mockTwigEnvironment->shouldReceive('render')->with($this->template, $this->data)
            ->andReturnUsing(function(){ throw new Twig_Error_Loader("error message");});
        $adapter = new TwigRenderer($this->mockTwigEnvironment);
        $adapter->render($this->template, $this->data);
    }
}