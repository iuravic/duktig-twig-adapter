<?php
namespace Duktig\View\Adapter\Twig;

use Twig_Environment;
use Twig_Error_Loader;
use Duktig\Core\Exception\TemplateNotFoundException;
use Duktig\Core\View\RendererInterface;

class TwigRenderer implements RendererInterface
{
    private $renderer;
    
    public function __construct(Twig_Environment $renderer)
    {
        $this->renderer = $renderer;
    }
    
    /**
     * {@inheritDoc}
     * @see \Duktig\Core\View\RendererInterface::render()
     */
    public function render(string $template, array $data = []) : string
    {
        try {
            return $this->renderer->render($template, $data);
        } catch (Twig_Error_Loader $e) {
            throw new TemplateNotFoundException("Template '$template' not found by Twig renderer",
                null, $e);
        }
    }
}