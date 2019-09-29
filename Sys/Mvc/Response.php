<?php
namespace Sys\Mvc;


use LSS\Array2XML;
use stdClass;
use Mustache_Engine;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Exception;

class Response extends \Symfony\Component\HttpFoundation\Response
{
    private $data = ['code' => 200, 'result' => [], 'message' => 'OK'];
    private $format = 'html';

    /**
     * @var Mustache_Engine
     */
    private $htmlEngine = null;
    /**
     * @var string
     */
    private $htmlTemplate = null;

    /**
     * @param string|array $k
     * @param $v
     * @return $this
     */
    public function assign($k, $v = null)
    {
        if (is_string($k)) {
            $this->data['result'][$k] = $v;
        } elseif (is_array($k)) {
            $this->data['result'] = array_merge($this->data['result'], $k);
        } else {
            $this->data = $k;
        }
        return $this;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat(string $format)
    {
        $this->format = strtolower($format);
        return $this;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = new ResponseHeaderBag($headers);
    }

    /**
     * @param int $code
     * @param null $text
     * @return $this|\Symfony\Component\HttpFoundation\Response
     */
    public function setStatusCode(int $code, $text = null)
    {
        parent::setStatusCode($code);
        $this->data['code'] = $code;
        $this->data['message'] = $text ? $text : $this->statusText;
        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function send()
    {
        if (empty($this->data['result'])) {
            $this->data['result'] = new stdClass();
        }
        switch ($this->format) {
            case 'raw':
                return parent::send();
            case 'json':
                $this->setContent(json_encode($this->data))
                    ->headers->set('Content-Type', 'application/json;charset=utf-8');
                break;
            case 'xml':
                $this->setContent(Array2XML::createXML('root', $this->data)->saveXML())
                    ->headers->set('Content-Type', 'text/xml;charset=utf-8');
                break;
            case 'html':
                try {
                    $this->setContent($this->htmlEngine->loadTemplate($this->htmlTemplate)->render($this->data['result']))
                        ->headers->set('Content-Type', 'text/html;charset=utf-8');
                } catch (Exception $e) {
                    $this->setContent(json_encode($this->data))
                        ->headers->set('Content-Type', 'application/json;charset=utf-8');
                }
                break;
        }
        return parent::send();
    }

    public function setTemplate(string $file)
    {
        $this->htmlTemplate = $file;
    }

    /**
     * @param Mustache_Engine $engine
     * @return $this
     */
    public function setEngine(Mustache_Engine $engine)
    {
        $this->htmlEngine = $engine;
        return $this;
    }
}
