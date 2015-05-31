<?php 
class CursBNR
{
    /**
     * xml document
     * @var string
     */
    private $xmlDocument = "";
     
     
    /**
     * exchange date
     * BNR date format is Y-m-d
     * @var string
     */
    private $date = "";
     
     
    /**
     * currency
     * @var associative array
     */
    private $currency = array();
     
     
    /**
     * Class constructor
     *
     * @access        public
     * @param         $url        string
     * @return        void
    */
    function __construct($url = "http://www.bnr.ro/nbrfxrates.xml")
    {
        $this->xmlDocument = file_get_contents($url);
        $this->parseXMLDocument();
    }

    /**
     * parseXMLDocument method
     *
     * @access        private
     * @return         void
     */
    private function parseXMLDocument()
    {
        $xml = new SimpleXMLElement($this->xmlDocument);
         
        $this->date=$xml->Header->PublishingDate;
         
        foreach($xml->Body->Cube->Rate as $line)
        {
            $this->currency[]=array("name"=>$line["currency"], "value"=>$line, "multiplier"=>$line["multiplier"]);
        }
    }

    /**
     * getCurs method
     *
     * get current exchange rate: example getCurs("USD")
     *
     * @access        public
     * @return         double
     */
    public function getCurs($currency)
    {
        foreach($this->currency as $line)
        {
            if($line["name"]==$currency)
            {
                return (float)$line["value"];
            }
        }

        return "Incorrect currency!";
    }
}