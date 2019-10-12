<?php
namespace Sys\Mvc;


use LSS\Array2XML;
use stdClass;
use Mustache_Engine;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Exception;
use Sys\Application;
use Sys\Config;
use Auryn\InjectionException;

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
     * @throws InjectionException
     */
    public function send()
    {
        if (empty($this->data['result']) && $this->format !== 'xml') {
            $this->data['result'] = new stdClass();
        }
        if ($this->getStatusCode() !== 200) {
            $this->htmlTemplate = 'errors/' . strval($this->getStatusCode());
        }
        switch ($this->format) {
            case 'raw':
                return parent::send();
            case 'html': case 'htm':
                try {
                    $this->setContent($this->htmlEngine->loadTemplate($this->htmlTemplate)->render($this->data['result']))
                        ->headers->set('Content-Type', 'text/html;charset=utf-8');
                    break;
                } catch (Exception $e) {
                    $this->format = 'json';
                }
            case 'json':
                $this->setContent(json_encode($this->data));
                $this->headers->set('Content-Type', 'application/json;charset=utf-8');
                break;
            case 'xml':
                $this->setContent(Array2XML::createXML('root', $this->data)->saveXML());
                $this->headers->set('Content-Type', 'text/xml;charset=utf-8');
                break;
        }
        $this->headers->set('Access-Control-Allow-Origin', Application::instance()->make(Config::class)->get('cors.origin'));
        $this->headers->set('Access-Control-Allow-Methods', Application::instance()->make(Config::class)->get('cors.methods'));
        $this->headers->set('Access-Control-Allow-Headers', Application::instance()->make(Config::class)->get('cors.headers'));
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
