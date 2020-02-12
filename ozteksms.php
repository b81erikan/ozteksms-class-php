<?php

  /**
   *
   * Burak Erikan - burakerikan.com.tr - github.com/b81erikan
   * 15.01.2020
   * özteksms.com
   *
   **/

  class OztekSMS
  {

    private $username, $userid, $password, $originator, $smsType;
    private $smsLength, $message, $xml, $smsData = [];
    private $time, $timeout;
    private $apiURL = [
      "single"  =>  "http://www.ozteksms.com/panel/smsgonderNNpost.php",
      "multi"  =>  "http://www.ozteksms.com/panel/smsgonder1Npost.php",
    ];

    function __construct(array $arr = [] )
    {
      $this->username       = $arr["kulad"];
      $this->userid         = $arr["kulno"];
      $this->password       = $arr["sifre"];
    }

    public function setOriginator( $field )
    {
      $this->originator = $field;
    }

    public function setLentgh( $lentgh )
    {
      $this->smsLength  = $lentgh;
    }

    public function setSMSType( $field )
    {
      $this->smsType = $field;
    }

    public function setData( array $arr = [] )
    {
      foreach ($arr as $data) {
        $this->smsData[count($this->smsData)]["numara"] = $data["numara"];
        $this->smsData[count($this->smsData)-1]["mesaj"] = $data["mesaj"];
      }
    }

    public function ready()
    {
      return $this->xml = $this->generateXML();
    }

    public function sent()
    {
        $headers = [
            'Content-Type: text/html; charset=utf-8',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $this->apiURL[$this->smsLength]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        return $this->getResponse($response);
	  }

    public function setTimeout( $field )
    {
      $this->timeout  = $field;
    }

    public function setMessage( $field )
    {
      $this->message  = $field;
    }

    public function setTime( $field )
    {
      $this->time  = $field;
    }

    public function generateXML()
    {
      $content  = "data=<sms>
                      <kno>".$this->userid."</kno>
                      <kulad>".$this->username."</kulad>
                      <sifre>".$this->password."</sifre>
                      <tur>".$this->getType()."</tur>
                      <gonderen>".$this->originator."</gonderen>
                      ";

                      if( $this->smsLength == "multi" )
                      {
                        $content.="<numaralar>";
                        foreach ($this->smsData as $data) {
                          $numbers.= $data["numara"].",";
                        }
                        $content.= rtrim($numbers,',')."</numaralar>
                        <mesaj>".$this->filterMessage($this->message)."</mesaj>";
                      }

                      if( $this->smsLength == "single" )
                      {
                        $content.= "<telmesajlar>";
                        foreach ($this->smsData as $data) {
                          $content.= "<telmesaj>
                            <tel>".$data["numara"]."</tel>
                            <mesaj>".$this->filterMessage($data["mesaj"])."</mesaj>
                          </telmesaj>";
                        }
                        $content   .= "</telmesajlar>";
                      }


      if( $this->timeout )
      {
        $content   .= "<zamanasimi>".$this->timeout."</zamanasimi>";
      }
      if( $this->time )
      {
        $content   .= "<zaman>".$this->time."</zaman>";
      }

      return $content   .= "</sms>";
    }

    private function filterMessage( $text )
    {

      $symbols    = [ "@", "£", "$", "€", "_",
                      "!", "'", "#", "%", "&",
                      "(", ")", "*", "+", "-",
                      "/", ":", ";", "<", "=",
                      ">", "?", "{", "}", "~",
                      "^", "ö", "ü", "ç", "ş",
                      "ğ", "ı", "Ö", "Ü", "Ç",
                      "Ş", "İ", "Ğ"
                    ];
      $characters = [ "|01|", "|02|", "|03|", "|05|", "|14|", "|26|", "|27|", "|28|", "|30|", "|31|", "|33|", "|34|", "|35|", "|36|", "|38|", "|39|", "|40|", "|41|",
                      "|42|", "|43|", "|44|", "|45|", "|46|", "|47|", "|49|", "|51|", "|62|", "|63|", "|64|", "|65|", "|66|", "|67|", "|68|", "|69|", "|70|", "|71|",
                      "|72|", "|73|",
                    ];


      return str_replace($symbols,$characters,$text);
    }

    private function getResponse( $response )
    {
      $split  = explode(":",$response);

      if( $split[0] == 2 )
      {
        return "error";
      }
      else
      {
        return "ok";
      }
    }

    private function getType()
    {
      $typeArr  = ["tr"=>"Turkce","normal"=>"Normal"];
      return $typeArr[strtolower($this->smsType)] ?? "Normal";
    }

  }
