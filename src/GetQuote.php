<?php

    namespace Jickson\DHL\API;

    class GetQuote extends APIAbstract
    {
        private $reference;

        private $fromCountryCode;
        private $fromPostalCode;
        private $fromCity;

        private $toCountryCode;
        private $toPostalCode;
        private $toCity;

        private $timeZone;
        private $dimensionUnit;
        private $weightUnit;

        private $declaredValue;

        private $products = [];

        public function __construct()
        {
            parent::__construct();
            $this->fromCountryCode = env('DHL_COUNTRYCODE');
            $this->fromPostalCode = env('DHL_POSTALCODE');
            $this->fromCity = env('DHL_CITY');
            $this->timeZone = "+02:00";
            $this->dimensionUnit = 'CM';
            $this->weightUnit = 'KG';
        }

        public function toXML()
        {
            $xml = new \XmlWriter();
            $xml->openMemory();
            $xml->setIndent(TRUE);
            $xml->setIndentString("  ");
            $xml->startDocument('1.0', 'UTF-8');

            $xml->startElement('req:DCTRequest');

            $xml->writeAttribute('xmlns:req', "http://www.dhl.com");
            $xml->writeAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
            $xml->writeAttribute('xsi:schemaLocation', "http://www.dhl.com DCT-req.xsd");
            $xml->startElement('GetQuote');
            $xml->startElement('Request');
            $xml->startElement('ServiceHeader');
            $xml->writeElement('MessageTime', date('Y-m-d') . "T" . date('H:i:s') . ".000+02:00");
            $xml->writeElement('MessageReference', '1234567890123456789012345678901'); //"1234567890123456789012345678901"
            $xml->writeElement('SiteID', $this->username);
            $xml->writeElement('Password', $this->password);
            $xml->endElement();
            $xml->endElement();
            $xml->startElement('From');
            $xml->writeElement('CountryCode', $this->fromCountryCode); //
            $xml->writeElement('Postalcode', $this->fromPostalCode); //
            $xml->writeElement('City', $this->fromCity);
            $xml->endElement();
            $xml->startElement('BkgDetails');
            $xml->writeElement('PaymentCountryCode', $this->fromCountryCode);
            $xml->writeElement('Date', date('Y-m-d'));
            $xml->writeElement('ReadyTime', 'PT12H00M');
            $xml->writeElement('DimensionUnit', $this->dimensionUnit);
            $xml->writeElement('WeightUnit', $this->weightUnit);
//                        $xml->writeElement('NumberOfPieces', ''); //#Optional - Required when no pieces have been registered
//                        $xml->writeElement('ShipmentWeight', ''); //#Optional - Required when no pieces have been registered
//                        $xml->writeElement('Volume', ''); //#Optional - Required when no pieces have been registered
//                        $xml->writeElement('MaxPieceWeight', ''); //#Optional - Required when no pieces have been registered
//                        $xml->writeElement('MaxPieceHeight', ''); //#Optional - Required when no pieces have been registered
//                        $xml->writeElement('MaxPieceDepth', ''); //#Optional - Required when no pieces have been registered
//                        $xml->writeElement('MaxPieceWidth', ''); //#Optional - Required when no pieces have been registered

            $xml->startElement('Pieces');
            /*foreach ($this->products as $key => $product) {
                $xml->startElement('Piece');
                $xml->writeElement('PieceID', $key + 1); //
//                                $xml->writeElement('PackageTypeCode', 'BOX'); //#Optional
                $xml->writeElement('Height', $product['height']); //
                $xml->writeElement('Depth', $product['depth']); //
                $xml->writeElement('Width', $product['width']); //
                $xml->writeElement('Weight', $product['weight']); //
                $xml->endElement();
            }*/

            $xml->startElement('Piece');
            $xml->writeElement('PieceID',  1); //
            $xml->writeElement('Height', 11); //
            $xml->writeElement('Depth', 11); //
            $xml->writeElement('Width', 11); //
            $xml->writeElement('Weight', 11); //
            $xml->endElement();

            $xml->endElement();

            $xml->writeElement('PaymentAccountNumber', $this->accountNumber); //#Optional
            $xml->writeElement('IsDutiable', 'Y');
//                        $xml->writeElement('NetworkTypeCode', 'AL'); //#Optional
            $xml->startElement('QtdShp');
            $xml->writeElement('GlobalProductCode', 'P');
            $xml->writeElement('LocalProductCode', 'P'); //#Optional
            $xml->startElement('QtdShpExChrg');
//            $xml->writeElement('SpecialServiceType', 'WY');
            $xml->endElement();
            $xml->endElement();
            $xml->endElement();
            $xml->startElement('To');
            $xml->writeElement('CountryCode', $this->toCountryCode); //
            if (!empty($this->postalCode)) {
                $xml->writeElement('Postalcode', $this->toPostalCode); //
            }
            $xml->writeElement('City', $this->toCity); //
            $xml->endElement();
            $xml->startElement('Dutiable');
            $xml->writeElement('DeclaredCurrency', 'USD'); //
            $xml->writeElement('DeclaredValue', $this->declaredValue); //
            $xml->endElement();
            $xml->endElement();
            $xml->endElement();

            $xml->endDocument();

            return $this->document = $xml->outputMemory();
        }

        public function toCountryCode($value = NULL)
        {
            if (empty($value)) {
                return $this->toCountryCode;
            }

            $this->toCountryCode = $value;

            return $this;
        }

        public function toPostalCode($value = NULL)
        {
            if (empty($value)) {
                return $this->toPostalCode;
            }

            $this->toPostalCode = $value;

            return $this;
        }

        public function toCity($value = NULL)
        {
            if (empty($value)) {
                return $this->toCity;
            }

            $this->toCity = $value;

            return $this;
        }

        public function fromCountryCode($value = NULL)
        {
            if (empty($value)) {
                return $this->fromCountryCode;
            }

            $this->fromCountryCode = $value;

            return $this;
        }

        public function fromPostalCode($value = NULL)
        {
            if (empty($value)) {
                return $this->toPostalCode;
            }

            $this->toPostalCode = $value;

            return $this;
        }

        public function fromCity($value = NULL)
        {
            if (empty($value)) {
                return $this->fromCity;
            }

            $this->fromCity = $value;

            return $this;
        }

        public function user($user)
        {
            $this->toCity('Harare')
                ->toCountryCode('ZW')
                ->toPostalCode('1234');

            return $this;
        }

        public function addProduct($value)
        {
            if (is_array(reset($value))) {
                foreach ($value as $product) {
                    if (is_array($product)) {
                        $this->products[] = $product;
                    }
                }
            } else {
                $this->products[] = $value;
            }

            return $this;
        }

        public function products($value = NULL)
        {
            if (empty($value)) {
                return $this->products;
            }

//            $this->products[] = $value;

            return $this;
        }

        public function declaredValue($value = NULL)
        {
            if (empty($value)) {
                return $this->declaredValue;
            }

            $this->declaredValue = $value;

            return $this;
        }

        public function reference($value = NULL)
        {
            if (empty($value)) {
                return $this->reference;
            }

            $this->reference = $value;

            return $this;
        }
    }