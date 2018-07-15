<?php

    namespace Jickson\DHL\API;

    abstract class APIAbstract
    {
        protected $_stagingUrl = 'https://xmlpitest-ea.dhl.com/XMLShippingServlet?isUTF8Support=true';
        protected $_productionUrl = 'https://xmlpi-ea.dhl.com/XMLShippingServlet?isUTF8Support=true';

        protected $document;
        protected $results;
        protected $resultsRAW;

        protected $username;
        protected $password;
        protected $accountNumber;

        protected $_mode;

        public function __construct()
        {
            $this->username = env('DHL_ID');
            $this->password = env('DHL_KEY');
            $this->_mode = env('APP_ENV');
            $this->accountNumber = env('DHL_ACCOUNT');
        }

        public function doCurlPost()
        {
            if ($this->_mode == "production") {
                $ch = curl_init($this->_productionUrl);
            } else {
                $ch = curl_init($this->_stagingUrl);
            }

            //$ch = curl_init($this->_productionUrl);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //ssl
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //ssl
//            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml']);
//            curl_setopt($ch, CURLOPT_HEADER, FALSE);
//            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
//            curl_setopt($ch, CURLOPT_NOBODY, FALSE);
//            curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
            curl_setopt($ch, CURLOPT_PORT, 443);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->document());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            $result = curl_exec($ch);

            curl_close($ch);

            $this->resultsRAW = $result;

            try {
                $this->results = simplexml_load_string($result);
//                $results = simplexml_load_string($result);
//                $this->results = $results->children();
            } catch (\Exception $exception) {
                return FALSE;
            }

            return $this->results;
        }

        public function call()
        {
            return $this->doCurlPost();
        }

        public function mode($value = NULL)
        {
            if (empty($value)) {
                return $this->_mode;
            }

            $this->_mode = $value;

            return $this;
        }

        public function document()
        {
            if (!isset($this->document)) {
                $this->toXML();
            }

            return $this->document;
        }

        public function getResultsRAW()
        {
            if (empty($this->resultsRAW)) {
                $this->doCurlPost();
            }

            return $this->resultsRAW;
        }
    }