<?php

  include 'oztek.php';

  $ozteksms = new OztekSMS(["kulad"=>"API Kullanıcı Adı","kulno"=>"Kullanıcı No","sifre"=>"Şifre"]);
  $ozteksms->setOriginator("başlık"); // Sahip olduğunuz sms başlığı

  $ozteksms->setLentgh("single"); // Her telefon numarasına farklı mesaj gönderimi
  $ozteksms->setSMSType("normal"); // TR karakter içermesin ["normal","tr"]
  $ozteksms->setTime("2020-01-23 12-00-00"); // Mesaj gönderilecek tarih, hemen gönderim için boş bırakın. Format: [Yıl-Ay-Gün Saat-Dakika-Saniye]
  $ozteksms->setTimeout(); // Mesaj gönderim ömrü, boş bırakılabilir. Format: [Yıl-Ay-Gün Saat-Dakika-Saniye]
  $ozteksms->setData(
    [
      ["numara"=>"55512334567","mesaj"=>"Test"],
      ["numara"=>"55512334567","mesaj"=>"Deneme mesaj.\nWeb sitemize hoş geldiniz."],
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
