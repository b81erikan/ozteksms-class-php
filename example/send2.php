<?php

  include 'oztek.php';

  $ozteksms = new OztekSMS(["kulad"=>"API Kullanıcı Adı","kulno"=>"Kullanıcı No","sifre"=>"Şifre"]);
  $ozteksms->setOriginator("başlık"); // Sahip olduğunuz sms başlığı

  $ozteksms->setLentgh("multi"); // Her telefon numarasına farklı mesaj gönderimi
  $ozteksms->setSMSType("normal"); // TR karakter içermesin ["normal","tr"]
  $ozteksms->setTime("2020-01-23 12-00-00"); // Mesaj gönderilecek tarih, hemen gönderim için boş bırakın. Format: [Yıl-Ay-Gün Saat-Dakika-Saniye]
  $ozteksms->setTimeout(); // Mesaj gönderim ömrü, boş bırakılabilir. Format: [Yıl-Ay-Gün Saat-Dakika-Saniye]
  $ozteksms->setMessage("Mesaj içeriği."); // Herkese aynı mesaj gönderilecek
  $ozteksms->setData(
    [
      ["numara"=>"55512334567"],
      ["numara"=>"555123345687"],
    ]
  );
  $ozteksms->ready();
  $status = $ozteksms->sent(); // error || ok 
    if( $status == "ok" )
    {
      // Gönderildi
    } 
    else
    {
      // Gönderilirken hata oluştu
    } 
