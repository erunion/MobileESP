<?php

/**
 * The MobileEsp class encapsulates information about a browser's connection to
 * your website. You can use it to find out whether the browser asking for your
 * site's content is probably running on a mobile device.
 *
 * Code was extracted and modified from the original by Anthony Hand at:
 * http://code.google.com/p/mobileesp/
 *
 * @author Jon Ursenbach <jon@ursenba.ch>
 * @link http://github.com/jonursenbach/MobileESP
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
class MobileEsp {
  public $useragent = '';
  public $httpaccept = '';

  /**
   * Whether the device is an iPhone or iPod Touch.
   *
   * @var boolean
   */
  public $isIphone = false;

  /**
   * Whether the device is a (small-ish) Android phone or media player.
   *
   * @var boolean
   */
  public $isAndroidPhone = false;

  /**
   * Whether is the Tablet (HTML5-capable, larger screen) tier of devices.
   *
   * @var boolean
   */
  public $isTierTablet = false;

  /**
   * Whether is the iPhone tier of devices.
   *
   * @var boolean
   */
  public $isTierIphone = false;

  /**
   * Whether the device can probably support Rich CSS, but JavaScript support is
   * not assumed. (e.g., newer BlackBerry, Windows Mobile).
   *
   * @var boolean
   */
  public $isTierRichCss = false;

  /**
   * Whether it is another mobile device, which cannot be assumed to support CSS
   * or JS (eg, older BlackBerry, RAZR).
   *
   * @var boolean
   */
  public $isTierGenericMobile = false; //Stores

  // Initialize some initial smartphone string variables.
  public $engineWebKit = 'webkit';
  public $deviceIphone = 'iphone';
  public $deviceIpod = 'ipod';
  public $deviceIpad = 'ipad';
  public $deviceMacPpc = 'macintosh'; // Used for disambiguation

  public $deviceAndroid = 'android';
  public $deviceGoogleTV = 'googletv';
  public $deviceXoom = 'xoom'; // Motorola Xoom
  public $deviceHtcFlyer = 'htc_flyer'; // HTC Flyer

  public $deviceNuvifone = 'nuvifone'; // Garmin Nuvifone

  public $deviceSymbian = 'symbian';
  public $deviceS60 = 'series60';
  public $deviceS70 = 'series70';
  public $deviceS80 = 'series80';
  public $deviceS90 = 'series90';

  public $deviceWinPhone7 = 'windows phone os 7';
  public $deviceWinMob = 'windows ce';
  public $deviceWindows = 'windows';
  public $deviceIeMob = 'iemobile';
  public $devicePpc = 'ppc'; // Stands for PocketPC
  public $enginePie = 'wm5 pie'; // An old Windows Mobile

  public $deviceBB = 'blackberry';
  public $vndRIM = 'vnd.rim'; // Detectable when BB devices emulate IE or Firefox
  public $deviceBBStorm = 'blackberry95'; // Storm 1 and 2
  public $deviceBBBold = 'blackberry97'; // Bold 97x0 (non-touch)
  public $deviceBBBoldTouch = 'blackberry 99'; // Bold 99x0 (touchscreen)
  public $deviceBBTour = 'blackberry96'; // Tour
  public $deviceBBCurve = 'blackberry89'; // Curve2
  public $deviceBBCurveTouch = 'blackberry 938'; //Curve Touch
  public $deviceBBTorch = 'blackberry 98'; // Torch
  public $deviceBBPlaybook = 'playbook'; // PlayBook tablet

  public $devicePalm = 'palm';
  public $deviceWebOS = 'webos'; // For Palm's line of WebOS devices
  public $deviceWebOShp = 'hpwos'; // For HP's line of WebOS devices

  public $engineBlazer = 'blazer'; // Old Palm browser
  public $engineXiino = 'xiino'; // Another old Palm

  public $deviceKindle = 'kindle'; // Amazon Kindle, eInk one.

  // Initialize variables for mobile-specific content.
  public $vndwap = 'vnd.wap';
  public $wml = 'wml';

  // Initialize variables for other random devices and mobile browsers.
  public $deviceTablet = 'tablet'; // Generic term for slate and tablet devices
  public $deviceBrew = 'brew';
  public $deviceDanger = 'danger';
  public $deviceHiptop = 'hiptop';
  public $devicePlaystation = 'playstation';
  public $deviceNintendoDs = 'nitro';
  public $deviceNintendo = 'nintendo';
  public $deviceWii = 'wii';
  public $deviceXbox = 'xbox';
  public $deviceArchos = 'archos';

  public $engineOpera = 'opera'; // Popular browser
  public $engineNetfront = 'netfront'; //C ommon embedded OS browser
  public $engineUpBrowser = 'up.browser'; // common on some phones
  public $engineOpenWeb = 'openweb'; // Transcoding by OpenWave server
  public $deviceMidp = 'midp'; // a mobile Java technology
  public $uplink = 'up.link';
  public $engineTelecaQ = 'teleca q'; // a modern feature phone browser

  public $devicePda = 'pda'; // some devices report themselves as PDAs
  public $mini = 'mini';  // Some mobile browsers put 'mini' in their names.
  public $mobile = 'mobile'; // Some mobile browsers put 'mobile' in their user agent strings.
  public $mobi = 'mobi'; // Some mobile browsers put 'mobi' in their user agent strings.

  // Use Maemo, Tablet, and Linux to test for Nokia's Internet Tablets.
  public $maemo = 'maemo';
  public $linux = 'linux';
  public $qtembedded = 'qt embedded'; // for Sony Mylo and others
  public $mylocom2 = 'com2'; // for Sony Mylo also

  // In some UserAgents, the only clue is the manufacturer.
  public $manuSonyEricsson = 'sonyericsson';
  public $manuericsson = 'ericsson';
  public $manuSamsung1 = 'sec-sgh';
  public $manuSony = 'sony';
  public $manuHtc = 'htc'; // Popular Android and WinMo manufacturer

  // In some UserAgents, the only clue is the operator.
  public $svcDocomo = 'docomo';
  public $svcKddi = 'kddi';
  public $svcVodafone = 'vodafone';

  // Disambiguation strings.
  public $disUpdate = 'update'; // pda vs. update

  public function __construct() {
    $this->useragent = (isset($_SERVER['HTTP_USER_AGENT'])) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
    $this->httpaccept = (isset($_SERVER['HTTP_ACCEPT'])) ? strtolower($_SERVER['HTTP_ACCEPT']) : '';

    $this->initializeDeviceScan();
  }

  /**
   * Initialize the device scan.
   *
   */
  private function initializeDeviceScan() {
    $this->isIphone = $this->DetectIphoneOrIpod();
    $this->isAndroidPhone = $this->DetectAndroidPhone();
    $this->isTierIphone = $this->DetectTierIphone();
    $this->isTierTablet = $this->DetectTierTablet();

    $this->isTierRichCss = $this->DetectTierRichCss();
    $this->isTierGenericMobile = $this->DetectTierOtherPhones();
  }

  /**
   * Returns the contents of the User Agent value, in lower case.
   *
   * @return string.
   */
  public function getUserAgent() {
    return $this->useragent;
  }

  /**
   * Returns the contents of the HTTP Accept value, in lower case.
   *
   * @return string
   */
  public function getHttpAccept() {
    return $this->httpaccept;
  }

  /**
   * Detects if the current device is an iPhone.
   *
   * @return boolean
   */
  public function DetectIphone() {
    if (stripos($this->useragent, $this->deviceIphone) > -1) {
      // Both iPad and iPod Touch mask as an iPhone, so let's disambiguate.
      if (
        $this->DetectIpad() ||
        $this->DetectIpod()
      ) {
        return false;
      } else {
        return true;
      }
    }

    return false;
  }

  /**
   * Detects if the current device is an iPod Touch.
   *
   * @return boolean
   */
  public function DetectIpod() {
    if (stripos($this->useragent, $this->deviceIpod) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is an iPad tablet.
   *
   * @return boolean
   */
  public function DetectIpad() {
    if (
      stripos($this->useragent, $this->deviceIpad) > -1 &&
      $this->DetectWebkit()
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is an iPhone or iPod Touch.
   *
   * @return boolean
   */
  public function DetectIphoneOrIpod() {
    // We repeat the searches here because some iPods may report themselves as
    // an iPhone, which would be okay.
    if (
      stripos($this->useragent, $this->deviceIphone) > -1 ||
      stripos($this->useragent, $this->deviceIpod) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects *any* iOS device: iPhone, iPod Touch, iPad.
   *
   * @return boolean
   */
  public function DetectIos() {
    if (
      $this->DetectIphoneOrIpod() ||
      $this->DetectIpad()
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects *any* Android OS-based device: phone, tablet, and multi-media
   * player. Also detects Google TV.
   *
   * @return boolean
   */
  public function DetectAndroid() {
    if (
      stripos($this->useragent, $this->deviceAndroid) > -1 ||
      $this->DetectGoogleTV()
    ) {
      return true;
    }

    // Special check for the HTC Flyer 7" tablet
    if (stripos($this->useragent, $this->deviceHtcFlyer) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is a (small-ish) Android OS-based device
   * used for calling and/or multi-media (like a Samsung Galaxy Player). Google
   * says these devices will have 'Android' AND 'mobile' in user agent.
   * Tablets are not phones, so they're ignored.
   *
   * @return boolean
   */
  public function DetectAndroidPhone() {
    if (
      $this->DetectAndroid() &&
      stripos($this->useragent, $this->mobile) > -1
    ) {
      return true;
    }

    // Special check for Android phones with Opera Mobile. They should report here.
    if ($this->DetectOperaAndroidPhone()) {
      return true;
    }

    //Special check for the HTC Flyer 7" tablet. It should report here.
    if (stripos($this->useragent, $this->deviceHtcFlyer) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is a (self-reported) Android tablet. Google
   * says these devices will have 'Android' and NOT 'mobile' in their user
   * agent.
   *
   * @return boolean
   */
  public function DetectAndroidTablet() {
    if (!$this->DetectAndroid()) {
      return false;
    }

    // Special check for Opera Android Phones. They should NOT report here.
    if ($this->DetectOperaMobile()) {
      return false;
    }

    // Special check for the HTC Flyer 7" tablet. It should NOT report here.
    if (stripos($this->useragent, $this->deviceHtcFlyer) > -1) {
      return false;
    }

    // Otherwise, if it's Android and does NOT have 'mobile' in it, Google says
    // it's a tablet.
    if (stripos($this->useragent, $this->mobile) > -1) {
      return false;
    }

    return true;
  }

  /**
   * Detects if the current device is an Android OS-based device and the
   * browser is based on WebKit.
   *
   * @return boolean
   */
  public function DetectAndroidWebKit() {
    if (
      $this->DetectAndroid() &&
      $this->DetectWebkit()
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is a GoogleTV.
   *
   * @return boolean
   */
  public function DetectGoogleTV() {
    if (stripos($this->useragent, $this->deviceGoogleTV) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is based on WebKit.
   *
   * @return boolean
   */
  public function DetectWebkit() {
    if (stripos($this->useragent, $this->engineWebKit) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is the Nokia S60 Open Source Browser.
   *
   * @return boolean
   */
  public function DetectS60OssBrowser() {
    // First, test for WebKit, then make sure it's either Symbian or S60.
    if ($this->DetectWebkit()) {
      if (
        stripos($this->useragent, $this->deviceSymbian) > -1 ||
        stripos($this->useragent, $this->deviceS60) > -1
      ) {
        return true;
      }
    }

    return false;
  }

  /**
   * Detects if the current device is any Symbian OS-based device, including
   * older S60, Series 70, Series 80, Series 90, and UIQ, or other browsers
   * running on these devices.
   *
   * @return boolean
   */
  public function DetectSymbianOS() {
    if (
      stripos($this->useragent, $this->deviceSymbian) > -1 ||
      stripos($this->useragent, $this->deviceS60) > -1 ||
      stripos($this->useragent, $this->deviceS70) > -1 ||
      stripos($this->useragent, $this->deviceS80) > -1 ||
      stripos($this->useragent, $this->deviceS90) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is a Windows Phone 7 device.
   *
   * @return boolean
   */
  public function DetectWindowsPhone7() {
    if (stripos($this->useragent, $this->deviceWinPhone7) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is a Windows Mobile device. Excludes
   * Windows Phone 7 devices and just focuses on Windows Mobile 6.xx or earlier.
   *
   * @return boolean
   */
  public function DetectWindowsMobile() {
    if ($this->DetectWindowsPhone7()) {
      return false;
    }

    // Most devices use 'Windows CE', but some report 'iemobile' and some older
    // ones report as 'PIE' for Pocket IE.
    if (
      stripos($this->useragent, $this->deviceWinMob) > -1 ||
      stripos($this->useragent, $this->deviceIeMob) > -1 ||
      stripos($this->useragent, $this->enginePie) > -1
    ) {
      return true;
    }

    // Test for Windows Mobile PPC but not old Macintosh PowerPC.
    if (
      stripos($this->useragent, $this->devicePpc) > -1 &&
      !(stripos($this->useragent, $this->deviceMacPpc) > 1)
    ) {
      return true;
    }

    // Test for certain Windwos Mobile-based HTC devices.
    if (
      stripos($this->useragent, $this->manuHtc) > -1 &&
      stripos($this->useragent, $this->deviceWindows) > -1
    ) {
      return true;
    }

    if (
      $this->DetectWapWml() &&
      stripos($this->useragent, $this->deviceWindows) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is any BlackBerry device. Includes the
   * PlayBook.
   *
   * @return boolean
   */
  public function DetectBlackBerry() {
    if (
      stripos($this->useragent, $this->deviceBB) > -1 ||
      stripos($this->httpaccept, $this->vndRIM) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is on a BlackBerry tablet device.
   *
   * @return boolean
   */
  public function DetectBlackBerryTablet() {
    if (stripos($this->useragent, $this->deviceBBPlaybook) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is a BlackBerry phone device AND uses a
   * WebKit-based browser. These are signatures for the new BlackBerry OS 6.
   * Examples: Torch. Includes the Playbook.
   *
   * @return boolean
   */
  public function DetectBlackBerryWebKit() {
    if (
      $this->DetectBlackBerry() &&
      $this->DetectWebkit()
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is a BlackBerry Touch phone device with a
   * large screen, such as the Storm, Torch, and Bold Touch. Excludes the
   * Playbook.
   *
   * @return boolean
   */
  public function DetectBlackBerryTouch() {
    if (
      stripos($this->useragent, $this->deviceBBStorm) > -1 ||
      stripos($this->useragent, $this->deviceBBTorch) > -1 ||
      stripos($this->useragent, $this->deviceBBBoldTouch) > -1 ||
      stripos($this->useragent, $this->deviceBBCurveTouch) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is a BlackBerry OS 5 device and has a more
   * capable recent browser. Excludes the Playbook, Storm, Bold, Tour, Curve2.
   * Excludes the new BlackBerry OS 6 and 7 browser.
   *
   * @return boolean
   */
  public function DetectBlackBerryHigh() {
    // Disambiguate for BlackBerry OS 6 or 7 (WebKit) browser
    if ($this->DetectBlackBerryWebKit()) {
      return false;
    }

    if ($this->DetectBlackBerry()) {
      if (
        $this->DetectBlackBerryTouch() ||
        stripos($this->useragent, $this->deviceBBBold) > -1 ||
        stripos($this->useragent, $this->deviceBBTour) > -1 ||
        stripos($this->useragent, $this->deviceBBCurve) > -1
      ) {
        return true;
      }
    }

    return false;
  }

  /**
   * Detects if the current browser is a BlackBerry device and has an older,
   * less capable browser. Examples: Pearl, 8800, Curve1.
   *
   * @return boolean
   */
  public function DetectBlackBerryLow() {
    if ($this->DetectBlackBerry()) {
      // Assume that if it's not in the High tier, then it's Low.
      if (
        $this->DetectBlackBerryHigh() ||
        $this->DetectBlackBerryWebKit()
      ) {
        return false;
      } else {
        return true;
      }
    }

    return false;
  }

  /**
   * Detects if the current browser is on a PalmOS device.
   *
   * @return boolean
   */
  public function DetectPalmOS() {
    // Most devices nowadays report as 'Palm', but some older ones reported as
    // Blazer or Xiino.
    if (
      stripos($this->useragent, $this->devicePalm) > -1 ||
      stripos($this->useragent, $this->engineBlazer) > -1 ||
      stripos($this->useragent, $this->engineXiino) > -1
    ) {
      // Make sure it's not WebOS.
      if (!$this->DetectPalmWebOS()) {
        return true;
      }
    }

    return false;
  }

  /**
   * Detects if the current browser is on a Palm device running the new WebOS.
   *
   * @return boolean
   */
  public function DetectPalmWebOS() {
    if (stripos($this->useragent, $this->deviceWebOS) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is on an HP tablet running WebOS.
   *
   * @return boolean
   */
  public function DetectWebOSTablet() {
    if (
      stripos($this->useragent, $this->deviceWebOShp) > -1 &&
      stripos($this->useragent, $this->deviceTablet) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is a Garmin Nuvifone.
   *
   * @return boolean
   */
  public function DetectGarminNuvifone() {
    if (stripos($this->useragent, $this->deviceNuvifone) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Check to see whether the device is any device in the 'smartphone' category.
   *
   * @return boolean
   */
  public function DetectSmartphone() {
    if (
      $this->isIphone ||
      $this->isAndroidPhone ||
      $this->isTierIphone ||
      $this->DetectS60OssBrowser() ||
      $this->DetectSymbianOS() ||
      $this->DetectWindowsMobile() ||
      $this->DetectWindowsPhone7() ||
      $this->DetectBlackBerry() ||
      $this->DetectPalmWebOS() ||
      $this->DetectPalmOS() ||
      $this->DetectGarminNuvifone()
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects whether the device is a Brew-powered device.
   *
   * @return boolean
   */
  public function DetectBrewDevice() {
    if (stripos($this->useragent, $this->deviceBrew) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects the Danger Hiptop device.
   *
   * @return boolean
   */
  public function DetectDangerHiptop() {
    if (
      stripos($this->useragent, $this->deviceDanger) > -1 ||
      stripos($this->useragent, $this->deviceHiptop) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is Opera Mobile or Mini.
   *
   * @return boolean
   */
  public function DetectOperaMobile() {
    if (stripos($this->useragent, $this->engineOpera) > -1) {
      if (
        (stripos($this->useragent, $this->mini) > -1) ||
        (stripos($this->useragent, $this->mobi) > -1)
      ) {
        return true;
      }
    }

    return false;
  }

  /**
   * Detects if the current browser is Opera Mobile running on an Android phone.
   *
   * @return boolean
   */
  public function DetectOperaAndroidPhone() {
    if (
      stripos($this->useragent, $this->engineOpera) > -1 &&
      stripos($this->useragent, $this->deviceAndroid) > -1 &&
      stripos($this->useragent, $this->mobi) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is Opera Mobile running on an Android
   * tablet.
   *
   * @return boolean
   */
  public function DetectOperaAndroidTablet() {
    if (
      stripos($this->useragent, $this->engineOpera) > -1 &&
      stripos($this->useragent, $this->deviceAndroid) > -1 &&
      stripos($this->useragent, $this->deviceTablet) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects whether the device supports WAP or WML.
   *
   * @return boolean
   */
  public function DetectWapWml() {
    if (
      stripos($this->httpaccept, $this->vndwap) > -1 ||
      stripos($this->httpaccept, $this->wml) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is an Amazon Kindle (eInk devices only).
   * Note: For the Kindle Fire, use the normal Android methods.
   *
   * @return boolean
   */
  public function DetectKindle() {
    if (
      stripos($this->useragent, $this->deviceKindle) > -1 &&
      !$this->DetectAndroid()
    ) {
      return true;
    }

    return false;
  }

  /**
   * The quick way to detect for a mobile device. Will probably detect most
   * recent/current mid-tier Feature Phones as well as smartphone-class devices.
   * Excludes Apple iPads and other modern tablets.
   *
   * @return boolean
   */
  public function DetectMobileQuick() {
    if ($this->isTierTablet) {
      return false;
    }

    // Most mobile browsing is done on smartphones
    if ($this->DetectSmartphone()) {
      return true;
    }

    if (
      $this->DetectWapWml() ||
      $this->DetectBrewDevice() ||
      $this->DetectOperaMobile()
    ) {
      return true;
    }

    if (
      stripos($this->useragent, $this->engineNetfront) > -1 ||
      stripos($this->useragent, $this->engineUpBrowser) > -1 ||
      stripos($this->useragent, $this->engineOpenWeb) > -1
    ) {
      return true;
    }

    if (
      $this->DetectDangerHiptop() ||
      $this->DetectMidpCapable() ||
      $this->DetectMaemoTablet() ||
      $this->DetectArchos()
    ) {
      return true;
    }

    if (
      stripos($this->useragent, $this->devicePda) > -1 &&
      !(stripos($this->useragent, $this->disUpdate) > -1)
    ) {
      return true;
    }

    if (stripos($this->useragent, $this->mobile) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is a Sony Playstation.
   *
   * @return boolean
   */
  public function DetectSonyPlaystation() {
    if (stripos($this->useragent, $this->devicePlaystation) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is a Nintendo game device.
   *
   * @return boolean
   */
  function DetectNintendo() {
    if (
      stripos($this->useragent, $this->deviceNintendo) > -1 ||
      stripos($this->useragent, $this->deviceWii) > -1 ||
      stripos($this->useragent, $this->deviceNintendoDs) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is a Microsoft Xbox.
   *
   * @return boolean
   */
  public function DetectXbox() {
    if (stripos($this->useragent, $this->deviceXbox) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is an Internet-capable game console.
   *
   * @return boolean
   */
  public function DetectGameConsole() {
    if (
      $this->DetectSonyPlaystation() ||
      $this->DetectNintendo() ||
      $this->DetectXbox()
    ) {
      return true;
    }

    return false;
  }

  // Detects if the current device supports MIDP, a mobile Java technology.
  public function DetectMidpCapable() {
    if (
      stripos($this->useragent, $this->deviceMidp) > -1 ||
      stripos($this->httpaccept, $this->deviceMidp) > -1
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is on one of the Maemo-based Nokia Internet
   * Tablets.
   *
   * @return boolean
   */
  public function DetectMaemoTablet() {
    if (stripos($this->useragent, $this->maemo) > -1) {
      return true;
    }

    // For Nokia N810, must be Linux + Tablet, or else it could be something
    // else.
    if (
      (stripos($this->useragent, $this->linux) > -1) &&
      (stripos($this->useragent, $this->deviceTablet) > -1) &&
      !$this->DetectWebOSTablet() &&
      !$this->DetectAndroid()
    ) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current device is an Archos media player/Internet tablet.
   *
   * @return boolean
   */
  public function DetectArchos() {
    if (stripos($this->useragent, $this->deviceArchos) > -1) {
      return true;
    }

    return false;
  }

  /**
   * Detects if the current browser is a Sony Mylo device.
   *
   * @return boolean
   */
  public function DetectSonyMylo() {
    if (stripos($this->useragent, $this->manuSony) > -1) {
      if (
        (stripos($this->useragent, $this->qtembedded) > -1) ||
        (stripos($this->useragent, $this->mylocom2) > -1)
      ) {
        return true;
      }
    }

    return false;
  }

  /**
   * The longer and more thorough way to detect for a mobile device. Will
   * probably detect most feature phones, smartphone-class devices, Internet
   * Tablets, Internet-enabled game consoles, etc. This ought to catch a lot of
   * the more obscure and older devices, also -- but no promises on
   * thoroughness!
   *
   * @return boolean
   */
  public function DetectMobileLong() {
    if ($this->DetectMobileQuick()) {
      return true;
    }

    if ($this->DetectGameConsole()) {
      return true;
    }

    if ($this->DetectSonyMylo()) {
      return true;
    }

    // Detect older phones from certain manufacturers and operators.
    if (stripos($this->useragent, $this->uplink) > -1) {
      return true;
    }

    if (stripos($this->useragent, $this->manuSonyEricsson) > -1) {
      return true;
    }

    if (stripos($this->useragent, $this->manuericsson) > -1) {
      return true;
    }

    if (stripos($this->useragent, $this->manuSamsung1) > -1) {
      return true;
    }

    if (stripos($this->useragent, $this->svcDocomo) > -1) {
      return true;
    }

    if (stripos($this->useragent, $this->svcKddi) > -1) {
      return true;
    }

    if (stripos($this->useragent, $this->svcVodafone) > -1) {
      return true;
    }

    return false;
  }

  /**
   * The quick way to detect for a tier of devices. This method detects for the
   * new generation of HTML 5 capable, larger screen tablets. Includes iPad,
   * Android (e.g., Xoom), BB Playbook, WebOS, etc.
   *
   * @return boolean
   */
  public function DetectTierTablet() {
    if (
      $this->DetectIpad() ||
      $this->DetectAndroidTablet() ||
      $this->DetectBlackBerryTablet() ||
      $this->DetectWebOSTablet()
    ) {
      return true;
    }

    return false;
  }

  /**
   * The quick way to detect for a tier of devices. This method detects for
   * devices which can display iPhone-optimized web content. Includes iPhone,
   * iPod Touch, Android, Windows Phone 7, WebOS, etc.
   *
   * @return boolean
   */
  public function DetectTierIphone() {
    if (
      $this->isIphone ||
      $this->isAndroidPhone
    ) {
      return true;
    }

    if (
      $this->DetectBlackBerryWebKit() &&
      $this->DetectBlackBerryTouch()
    ) {
      return true;
    }

    if ($this->DetectWindowsPhone7()) {
      return true;
    }

    if ($this->DetectPalmWebOS()) {
      return true;
    }

    if ($this->DetectGarminNuvifone()) {
      return true;
    }

    return false;
  }

  /**
   * The quick way to detect for a tier of devices. This method detects for
   * devices which are likely to be capable of viewing CSS content optimized for
   * the iPhone, but may not necessarily support JavaScript. Excludes all iPhone
   * Tier devices.
   *
   * @return boolean
   */
  public function DetectTierRichCss() {
    if ($this->DetectMobileQuick()) {
      if ($this->DetectTierIphone()) {
        return false;
      }

      // The following devices are explicitly ok.
      if ($this->DetectWebkit()) {
        return true;
      }

      if ($this->DetectS60OssBrowser()) {
        return true;
      }

      // Note: 'High' BlackBerry devices ONLY
      if ($this->DetectBlackBerryHigh()) {
        return true;
      }

      // Older Windows 'Mobile' isn't good enough for iPhone Tier.
      if ($this->DetectWindowsMobile()) {
        return true;
      }

      if (stripos($this->useragent, $this->engineTelecaQ) > -1) {
        return true;
      }
    }

    return false;
  }

  /**
   * The quick way to detect for a tier of devices. This method detects for all
   * other types of phones, but excludes the iPhone and RichCSS Tier devices.
   *
   * @return boolean
   */
  public function DetectTierOtherPhones() {
    // Exclude devices in the other 2 categories
    if (
      $this->DetectMobileLong() &&
      $this->DetectTierIphone() &&
      $this->DetectTierRichCss()
    ) {
      return true;
    }

    return false;
  }
}
