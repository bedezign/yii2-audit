<?php
namespace bedezign\yii2\audit\components;

use bedezign\yii2\audit\Audit;

class SoapClient extends \SoapClient
{
    private $_data = [];

    public function __construct($wsdl, $options = null)
    {
        $this->_data['wsdl'] = $wsdl;
        $this->_data['options'] = $options;

        try {
            parent::__construct($wsdl, $options);
        }
        catch (\SoapFault $e) {
            // Catches errors like being unable to read the WSDL and so on
            $this->logSoapFault($e);
            throw $e;
        }
    }

    public function __setLocation($new_location = null)
    {
        $this->_data['location'] = $new_location;
        return parent::__setLocation($new_location);
    }

    public function __call($function_name, $arguments)
    {
        return $this->__soapCall($function_name, $arguments);
    }

    public function __soapCall($function_name, $arguments, $options = null, $input_headers = null, &$output_headers = null)
    {
        $started = microtime(true);
        $this->_data = array_merge($this->_data, [
            'function_name'     => $function_name,
            'arguments'         => $arguments,
            'function_options'  => $options,
            'input_headers'     => $input_headers,
            'start_time'        => date('Y-m-d H:i:s'),
        ]);
        try {
            $result = parent::__soapCall($function_name, $arguments, $options, $input_headers, $output_headers);
            $this->_data['duration'] = microtime(true) - $started;
            if ($output_headers)
                $this->_data['output_headers'] = (array) $output_headers;

            if (is_soap_fault($result))
                // Cover non-exception variant
                return $this->logSoapFault($result);

            $this->_data['result_object'] = $result;
            $this->finalize();

            return $result;
        }
        catch (\Exception $error) {
            $this->_data['duration'] = microtime(true) - $started;
            if ($output_headers)
                $this->_data['output_headers'] = (array) $output_headers;

            $this->logSoapFault($error);
            throw $error;
        }
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $this->_data = array_merge($this->_data, [
            'request'       => $request,
            'location'      => $location,
            'action'        => $action,
            'version'       => $version,
            'one_way'       => $one_way
        ]);
        return $this->_data['result'] = parent::__doRequest($request, $location, $action, $version, $one_way);
    }

    public function __setCookie ($name, $value = null)
    {
        if (!isset($this->_data['cookies']))
            $this->_data['cookies'] = [];
        $this->_data['cookies'][$name] = $value;

        parent::__setCookie($name, $value);
    }

    protected function logSoapFault($error)
    {
        $this->_data['error'] = $error;
        $this->finalize();
        return $error;
    }

    protected function finalize()
    {
        $_panel = Audit::getInstance()->getPanel('audit/soap');
        if ($_panel)
            $_panel->logSoapRequest($this->_data);
    }
}