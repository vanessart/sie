<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<script src="<?php echo HOME_URI; ?>/includes/js/bmi/jquery-1.11.1.min.js" type="text/javascript"></script>
 <link rel="stylesheet" href="<?php echo HOME_URI; ?>/includes/css/bmi/jquery.mobile.icons.min.css" />
<script src="<?php echo HOME_URI; ?>/includes/js/bmi/jquery.mobile-1.4.5.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo HOME_URI; ?>/includes/js/bmi/jquery.mobile-1.4.5.min.css" />
<link rel="stylesheet" href="<?php echo HOME_URI; ?>/includes/js/bmi/Pedz.min.css" />
<link href="<?php echo HOME_URI; ?>/includes/css/bmi/rechner.css" rel="stylesheet" type="text/css" />
<script src="<?php echo HOME_URI; ?>/includes/js/bmi/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script src="<?php echo HOME_URI; ?>/includes/js/bmi/langpack/de.js" charset="utf-8" type="text/javascript"></script>
<script src="<?php echo HOME_URI; ?>/includes/js/bmi/langpack/es.js" charset="utf-8" type="text/javascript"></script>
<script src="<?php echo HOME_URI; ?>/includes/js/bmi/common.js" type="text/javascript"></script>
<script type="text/javascript">
// Chrome AJAX Fix
 $(document).bind('mobileinit',function(){
        $.mobile.pushStateEnabled = false;
    });

// Detect iOS System (because we can use smoother page transitions
var agent = navigator.userAgent.toLowerCase();

if ((agent.search('iphone') > -1)  ||
(agent.search('ipod') > -1) ||
(agent.search('ipad') > -1))
{
  $(document).bind("mobileinit", function(){
  $.mobile.defaultPageTransition = 'slide';
});
}
else {
  $(document).bind("mobileinit", function(){
  $.mobile.defaultPageTransition = 'none';
}); }

function changeLanguage() {
  window.lang = new jquery_lang_js();
  $().ready(function() {
    window.lang.run();
    
    // Some Android devices won't show a decimal separator (. or ,) when using a html input type=number. Using type=tel is an (ugly) workaround
    if (agent.search('android') > -1) {
$('input[type=number]').each(function() { $(this).prop('type', 'tel'); });
}
  });

var lang_short = getBrowserLanguage();
if (lang_short == 'de')
    window.lang.change('de');
else if (lang_short == 'es')
  window.lang.change('es');
}
</script>
</head>

<body>
<!-- InstanceBeginEditable name="pageContent" -->
<div data-role="page" id="bmi" data-theme="a">
  <link href="<?php echo HOME_URI; ?>/includes/css/bmi/mobiscroll.custom-2.5.0.min.css" rel="stylesheet" type="text/css" />
  <script src="<?php echo HOME_URI; ?>/includes/js/bmi/mobiscroll.custom-2.5.0.min.js" type="text/javascript"></script> 
  <script src="<?php echo HOME_URI; ?>/includes/js/bmi/mobiscroll.i18n.de.js" type="text/javascript"></script>
  <link href="<?php echo HOME_URI; ?>/includes/css/bootstrap5.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo HOME_URI; ?>/includes/css/style.css">
  <script type="text/javascript" src="<?php echo HOME_URI; ?>/includes/js/bmi/z-values.js"></script> 
  <script type="text/javascript" src="<?php echo HOME_URI; ?>/includes/js/bmi/common.js"></script> 
  <script type="text/javascript" src="<?php echo HOME_URI; ?>/includes/js/bmi/bmi.js"></script> 
  <script type="text/javascript" src="<?php echo HOME_URI; ?>/includes/js/bmi/bmi-data.js"></script> 
 
  <script type="text/javascript">
$(document).delegate("#bmi", "pagebeforecreate", function() {
  $().ready(function() {
    window.lang.run();
  });
  changeLanguage(window.lang);

setStrings();
setDatePicker();
setUnits();

setCurrentDate("current");
bmi_calc();
  $("#dateInput_flip").bind("change", function(event, ui) {
    var dateInput = $("#dateInput_flip").val();
      showDateInput(dateInput);
      setDateInputStorage(dateInput);
      bmi_calc();
  });
    $("#units_flip").bind("change", function(event, ui) {
    var units = $("#units_flip").val();
      setUnitsStorage(units);
      bmi_calc();
  });

  $("#radio").find("input[type='radio']").bind("change", function() {
    bmi_calc();
  });
  $("#prefer_radio").find("input[type='radio']").bind("change", function() {
    bmi_calc();
  });
  
  $("input[name^='date']").keyup(function() {
  dateInputKeyUp($(this), "bmi");
});
});

$(function() {
  var browserLang = getBrowserLanguage();

  $('#birthday').mobiscroll().date({
    theme: 'ios',
    display: 'modal',
    mode: 'scroller',
    lang: browserLang,
    dateOrder: 'ddMMyy',
    onClose: function(html, inst) {
      $("#birthday").val(html);
      bmi_calc();
    }
  });
  $('#currentdate').mobiscroll().date({
    theme: 'ios',
    display: 'modal',
    mode: 'scroller',
    lang: browserLang,
    dateOrder: 'ddMMyy',
    onClose: function(html, inst) {
      $("#currentdate").val(html);
      bmi_calc();
    }
  });
});

</script>
  <?= toolErp::divAlert('info','Os gráficos representam o intervalo: -2z, 0z, +2z') ?>
  <div data-role="content" data-theme="a" >
    <div class="bodyWrapper">
      <form name="form" id="form" action="" method="post">
        <fieldset data-role="controlgroup" id="radio" data-type="horizontal" >
          <input type="radio" name="radio" id="radio_male" value="male" checked="checked" />
          <label for="radio_male"><span lang="en">Menino</span></label>
          <input type="radio" name="radio" id="radio_female" value="female"  />
          <label for="radio_female"><span lang="en">Menina</span></label>
        </fieldset>
        <div id="dateInputMobile">
          <div class="pedzDate">
            <label  for="birthday"><span lang="en">Data de Nascimento</span></label>
            <input name="birthday" id="birthday">
          </div>
          <div class="pedzDate">
            <label for="currentdate" ><span lang="en">Data Atual</span></label>
            <input name="currentdate" id="currentdate"  >
          </div>
        </div>
        
        <div id="dateInputDesktop">
          <div class="pedzDate">
            <label for="birth_d"><span lang="en">Data de Nascimento</span></label><br>
            <div class="pedZDateInput">
              <input type="text" maxlength="2"  name="date_birth_d" id="birth_d" value="" placeholder="DD" />
            </div>
            <div class="pedZDateInput">
              <input type="text" maxlength="2"  name="date_birth_m" id="birth_m" value=""  placeholder="MM"/>
            </div>
            <div class="pedZDateInput">
              <input type="text" maxlength="4"  name="date_birth_y" id="birth_y" value="" placeholder="YYYY" />
            </div>
          </div>
          <div style="display: none;" class="pedzDate">
            <label for="current_d"><span lang="en">Data Atual</span> </label><br>
            <div class="pedZDateInput">
              <input type="text" maxlength="2"  name="date_current_d" id="current_d" value="" placeholder="DD" />
            </div>
            <div class="pedZDateInput">
              <input type="text" maxlength="2"  name="date_current_m" id="current_m" value="" placeholder="MM" />
            </div>
            <div class="pedZDateInput">
              <input type="text" maxlength="4"  name="date_current_y" id="current_y" value="" placeholder="YYYY"/>
            </div>
          </div>
        </div>
        <div id="headerResult"><span lang="en">Idade Calculada</span>: <span id="calculatedAgeYears"></span> <span id="calculatedAgeMonthsUpper"></span>/ <span id="calculatedAgeMonthsLower">12</span> <span lang="en">Anos</span> </div>
        <div style="display: none;" data-role="collapsible" class="ui-mini" data-content-theme="d">
          <h3><span lang="en">Options</span></h3>
          <div data-role="fieldcontain">
            <fieldset data-role="controlgroup" id="prefer_radio" data-type="vertical" >
              <input type="radio" name="prefer_radio" id="radio_kromeyer" value="kromeyer" />
              <label for="radio_kromeyer"><span lang="en">Prefer Kromeyer-H. data</span></label>
              
              <input type="radio" name="prefer_radio" id="radio_kiggs" value="kiggs"/>
              <label for="radio_kiggs"><span lang="en">Prefer KiGGS data</span></label>
              
               <input type="radio" name="prefer_radio" id="radio_hesse" value="hesse"/>
              <label for="radio_hesse"><span lang="en">Prefer Hesse data</span></label>
              
               <input type="radio" name="prefer_radio" id="radio_redlefsen" value="redlefsen"/>
              <label for="radio_redlefsen"><span lang="en">Prefer turkish/german data</span></label>
                           
              <input type="radio" name="prefer_radio" id="radio_cdc" value="cdc" checked="checked"/>
              <label for="radio_cdc"><span lang="en">Prefer CDC/WHO data</span></label>
              
               <input type="radio" name="prefer_radio" id="radio_down" value="down"/>
              <label for="radio_down"><span lang="en">Prefer Mb Down data</span></label>
                
            <input type="radio" name="prefer_radio" id="radio_koerner" value="koerner"/>
              <label for="radio_koerner"><span lang="en">Prefer obese data</span></label>
            </fieldset>
          </div>
          <hr>
          <fieldset data-role="controlgroup" data-type="horizontal">
            <legend><span lang="en">Gestational age (weeks : days)</span></legend>
            <label for="weeks" class="ui-hidden-accessible"><span lang="en">Weeks</span></label>
            <select name="weeks" id="weeks" onChange="bmi_calc()">
              <option value="22">22</option>
              <option value="23">23</option>
              <option value="24">24</option>
              <option value="25">25</option>
              <option value="26">26</option>
              <option value="27">27</option>
              <option value="28">28</option>
              <option value="29">29</option>
              <option value="30">30</option>
              <option value="31">31</option>
              <option value="32">32</option>
              <option value="33">33</option>
              <option value="34">34</option>
              <option value="35">35</option>
              <option value="36">36</option>
              <option value="37">37</option>
              <option value="38">38</option>
              <option value="39">39</option>
              <option value="40" selected>40</option>
              <option value="41">41</option>
              <option value="42">42</option>
            </select>
            <label for="days" class="ui-hidden-accessible"><span lang="en">Days</span></label>
            <select name="days" id="days"  onChange="bmi_calc()">
              <option value="0" selected>0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <!-- etc. -->
            </select>
          </fieldset>
          
            <label for="units_flip"><span lang="en">Prefered units</span></label>
            <select name="units_flip" id="units_flip" data-mini="true" data-role="flipswitch" data-wrapper-class="custom-size-flipswitch">
              <option value="metric" lang="en">Metric</option>
              <option value="inchpound" lang="en">Inch-pound</option>
            </select>

          <div class="flip_div">
            <label for="dateInput_flip"><span lang="en">Date input method</span></label>
            <select name="dateInput_flip" id="dateInput_flip" data-mini="true" data-role="flipswitch" data-wrapper-class="custom-size-flipswitch">
              <option value="desktop" lang="en">Desktop</option>
              <option value="mobile" lang="en">Mobile</option>
            </select>
          </div>
        </div>
        <ul class="zs-list">
          <li id="li-Weight">
            <div class="zs-box-left">
              <label for="Weight"><span lang="en">Peso</span></label>
              <input type="number" placeholder="kg" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Weight" id="Weight" value=""  />
            </div>
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Weight_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Weight_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Weight_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Weight"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Weight"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
            <li id="li-Weightweek">
            <div class="zs-box-left">
              <label for="Weightweek"><span lang="en">Peso/semana</span></label>
              <input type="number" placeholder="g" value="" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Weightweek" id="Weightweek"/>
            </div>
            <div class="zs-box-right"> <a href="#popupWeightweek" id="btnWeightweek" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupWeightweek" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorWeightweek" class="infoError"></div></p>
              <div id="infoWeightweek" class="zscoreinfo"></div>     
            </div>
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Weightweek_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Weightweek_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Weightweek_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Weightweek"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Weightweek"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li> 
   
          <li id="li-Height">
            <div class="zs-box-left">
              <label for="Height"><span lang="en">Altura</span></label>
              <input type="number" placeholder="cm" value="" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Height" id="Height"/>
            </div>
            <div class="zs-box-right"> <a href="#popupHeight" id="btnHeight" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupHeight" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorHeight" class="infoError"></div></p>
              <div id="infoHeight" class="zscoreinfo"></div>     
            </div>
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Height_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Height_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Height_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Height"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Height"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
    
       <li id="li-Heightobese">
            <div class="zs-box-left">
              <label for="Heightobese"><span lang="en"  style="white-space: nowrap;">Altura para Obesidade</span></label>
               &#8618; <div id="Heightobese" class="fixedParamsInput">n.d.</div>
            </div>
            <div class="zs-box-right"> <a href="#popupHeightobese" id="btnHeightobese" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupHeightobese" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorHeightobese" class="infoError"></div></p>
              <div id="infoHeightobese" class="zscoreinfo"></div>     
            </div>
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Heightobese_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Heightobese_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Heightobese_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Heightobese"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Heightobese"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
            <li id="li-Growthrateheight">
            <div class="zs-box-left">
              <label for="Growthrateheight"><span lang="en">IC Altura</span></label>
              <input type="number" placeholder="cm/y" value="" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Growthrateheight" id="Growthrateheight"/>
            </div>
            <div class="zs-box-right"> <a href="#popupGrowthrateheight" id="btnGrowthrateheight" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupGrowthrateheight" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorGrowthrateheight" class="infoError"></div></p>
              <div id="infoGrowthrateheight" class="zscoreinfo"></div>     
            </div>
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Growthrateheight_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Growthrateheight_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Growthrateheight_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Growthrateheight"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Growthrateheight"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
          <li id="li-Bmi">
            <div class="zs-box-left">
              <label for="Bmi"><span lang="en">IMC</span></label>
              <input type="number" placeholder="kg/m2" data-mini="true" onKeyUp="bmi_calc()" readonly maxlength="6"  name="Bmi" id="Bmi" value=""  />
            </div>
           <div class="zs-box-right"> <a href="#popupBmi" id="btnBmi" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupBmi" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorBmi" class="infoError"></div></p>
              <div id="infoBmi" class="zscoreinfo"></div> 
              </div>    
             <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Bmi_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Bmi_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Bmi_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Bmi"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Bmi"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>
       
     <!--     <li id="li-Headheight">
            <div class="zs-box-left">
              <label for="Headheight"><span lang="en">Head/Height</span></label>
              <input type="number" placeholder="cm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Headheight" id="Headheight" value="" readonly />
            </div>
          <div class="zs-box-right"> <a href="#popupHeadheight" id="btnHeadheight" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupHeadheight" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorHeadheight" class="infoError"></div></p>
              <div id="infoHeadheight" class="zscoreinfo"></div> 
              </div>   
              <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Headheight_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Headheight_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Headheight_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Headheight"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Headheight"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li> -->          
              
         <li id="li-Weightheight">
            <div class="zs-box-left">
              <label for="Weightheight"><span lang="en">Peso</span>/<span lang="en">Altura</span></label>
              <input type="number" placeholder="cm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Weightheight" id="Weightheight" value=""  readonly />
            </div>
            <div class="zs-box-right"> <a href="#popupWeightheight" id="btnWeightheight" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupWeightheight" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorWeightheight" class="infoError"></div></p>
              <div id="infoWeightheight" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Weightheight_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Weightheight_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Weightheight_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Weightheight"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Weightheight"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>  
   <li id="li-Head">
            <div class="zs-box-left">
              <label for="Head"><span lang="en">Cabeça</span></label>
              <input type="number" placeholder="cm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Head" id="Head" value=""  />
            </div>
          <div class="zs-box-right"> <a href="#popupHead" id="btnHead" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupHead" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorHead" class="infoError"></div></p>
              <div id="infoHead" class="zscoreinfo"></div> 
              </div>   
              <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Head_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Head_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Head_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Head"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Head"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li> 
     <li id="li-Growthratehead">
            <div class="zs-box-left">
              <label for="Growthratehead"><span lang="en">IC cabeça</span></label>
              <input type="number" placeholder="cm/y" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Growthratehead" id="Growthratehead" value=""  />
            </div>
          <div class="zs-box-right"> <a href="#popupGrowthratehead" id="btnGrowthratehead" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupGrowthratehead" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorGrowthratehead" class="infoError"></div></p>
              <div id="infoGrowthratehead" class="zscoreinfo"></div> 
              </div>   
              <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Growthratehead_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Growthratehead_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Growthratehead_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Growthratehead"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Growthratehead"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>   
     <li id="li-Chestcircumference">
            <div class="zs-box-left">
              <label for="Chestcircumference"><span lang="en">Chest circumference</span></label>
              <input type="number" placeholder="" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Chestcircumference" id="Chestcircumference" value="" />
            </div>
                  <div class="zs-box-right"> <a href="#popupChestcircumference" id="btnChestcircumference" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupChestcircumference" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorChestcircumference" class="infoError"></div></p>
              <div id="infoChestcircumference" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Chestcircumference_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Chestcircumference_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Chestcircumference_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Chestcircumference"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Chestcircumference"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
              <li id="li-Chestwidth">
            <div class="zs-box-left">
              <label for="Chestwidth"><span lang="en">Chest width</span></label>
              <input type="number" placeholder="cm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Chestwidth" id="Chestwidth" value="" />
            </div>
                  <div class="zs-box-right"> <a href="#popupChestwidth" id="btnChestwidth" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupChestwidth" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorChestwidth" class="infoError"></div></p>
              <div id="infoChestwidth" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Chestwidth_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Chestwidth_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Chestwidth_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Chestwidth"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Chestwidth"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>
              <li id="li-Chestdepth">
            <div class="zs-box-left">
              <label for="Chestdepth"><span lang="en">Chest depth</span></label>
              <input type="number" placeholder="cm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Chestdepth" id="Chestdepth" value="" />
            </div>
                  <div class="zs-box-right"> <a href="#popupChestdepth" id="btnChestdepth" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupChestdepth" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorChestdepth" class="infoError"></div></p>
              <div id="infoChestdepth" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Chestdepth_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Chestdepth_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Chestdepth_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Chestdepth"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Chestdepth"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>
          <li id="li-Waist">
            <div class="zs-box-left">
              <label for="Waist"><span lang="en">Waist</span></label>
              <input type="number" placeholder="cm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Waist" id="Waist" value=""  />
            </div>
                  <div class="zs-box-right"> <a href="#popupWaist" id="btnWaist" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupWaist" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorWaist" class="infoError"></div></p>
              <div id="infoWaist" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Waist_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Waist_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Waist_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Waist"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Waist"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>
          <li id="li-Hip">
            <div class="zs-box-left">
              <label for="Hip"><span lang="en">Hip</span></label>
              <input type="number" placeholder="cm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Hip" id="Hip" value=""  />
            </div>
                <div class="zs-box-right"> <a href="#popupHip" id="btnHip" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupHip" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorHip" class="infoError"></div></p>
              <div id="infoHip" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Hip_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Hip_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Hip_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Hip"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Hip"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
           <li id="li-Hipheight">
            <div class="zs-box-left">
              <label for="Hipheight"><span lang="en">Hip/Height</span></label>
              <input type="number" placeholder="" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Hipheight" id="Hipheight" value="" readonly />
            </div>
                <div class="zs-box-right"> <a href="#popupHipheight" id="btnHipheight" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupHipheight" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorHipheight" class="infoError"></div></p>
              <div id="infoHipheight" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Hipheight_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Hipheight_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Hipheight_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Hipheight"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Hipheight"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
          <li id="li-Waisthip">
            <div class="zs-box-left">
              <label for="Waisthip"><span lang="en">Waist</span>/<span lang="en">Hip</span></label>
              <input type="number" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Waisthip" id="Waisthip" value="" placeholder="" readonly />
            </div>
             <div class="zs-box-right"> <a href="#popupWaisthip" id="btnWaisthip" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupWaisthip" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorWaisthip" class="infoError"></div></p>
              <div id="infoWaisthip" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Waisthip_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Waisthip_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Waisthip_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Waisthip"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Waisthip"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>
          <li id="li-Waistheight">
            <div class="zs-box-left">
              <label for="Waistheight"><span lang="en">Waist/Height</span></label>
              <input type="number" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Waistheight" id="Waistheight" value=""  />
            </div>
              <div class="zs-box-right"> <a href="#popupWaistheight" id="btnWaistheight" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupWaistheight" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorWaistheight" class="infoError"></div></p>
              <div id="infoWaistheight" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Waistheight_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Waistheight_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Waistheight_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Waistheight"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Waistheight"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>
          <li id="li-Triceps">
            <div class="zs-box-left">
              <label for="Triceps"><span lang="en" style="white-space: nowrap;">Triceps skinfold</span></label>
              <input type="number" placeholder="mm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Triceps" id="Triceps" value=""  />
            </div>
          <div class="zs-box-right"> <a href="#popupTriceps" id="btnTriceps" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupTriceps" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorTriceps" class="infoError"></div></p>
              <div id="infoTriceps" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Triceps_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Triceps_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Triceps_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Triceps"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Triceps"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>
          <li id="li-Subscapular">
            <div class="zs-box-left">
              <label for="Subscapular"><span lang="en">Subscapular skinfold</span></label>
              <input type="number" placeholder="mm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Subscapular" id="Subscapular" value=""  />
            </div>
                <div class="zs-box-right"> <a href="#popupSubscapular" id="btnSubscapular" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupSubscapular" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorSubscapular" class="infoError"></div></p>
              <div id="infoSubscapular" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Subscapular_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Subscapular_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Subscapular_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Subscapular"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Subscapular"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
              <li id="li-Suprailiac">
            <div class="zs-box-left">
              <label for="Suprailiac"><span lang="en">Suprailiac skinfold</span></label>
              <input type="number" placeholder="mm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Suprailiac" id="Suprailiac" value=""  />
            </div>
                <div class="zs-box-right"> <a href="#popupSuprailiac" id="btnSuprailiac" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupSuprailiac" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorSuprailiac" class="infoError"></div></p>
              <div id="infoSuprailiac" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Suprailiac_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Suprailiac_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Suprailiac_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Suprailiac"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Suprailiac"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
          <li id="li-Skinfoldsum">
            <div class="zs-box-left">
              <label for="Skinfoldsum"><span lang="en">Skinfold sum</span></label>
              <input type="number" placeholder="mm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Skinfoldsum" id="Skinfoldsum" value="" readonly />
            </div>
                  <div class="zs-box-right"> <a href="#popupSkinfoldsum" id="btnSkinfoldsum" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupSkinfoldsum" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorSkinfoldsum" class="infoError"></div></p>
              <div id="infoSkinfoldsum" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Skinfoldsum_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Skinfoldsum_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Skinfoldsum_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Skinfoldsum"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Skinfoldsum"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
             <li id="li-Upperarm">
            <div class="zs-box-left">
              <label for="Upperarm"><span lang="en">Upper arm</span></label>
              <input type="number" placeholder="cm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Upperarm" id="Upperarm" value=""  />
            </div>
                  <div class="zs-box-right"> <a href="#popupUpperarm" id="btnUpperarm" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupUpperarm" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorUpperarm" class="infoError"></div></p>
              <div id="infoUpperarm" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Upperarm_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Upperarm_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Upperarm_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Upperarm"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Upperarm"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
               <li id="li-Epicondyle">
            <div class="zs-box-left">
              <label for="Epicondyle"><span lang="en">Elbow width</span></label>
              <input type="number" placeholder="mm" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Epicondyle" id="Epicondyle" value=""  />
            </div>
                  <div class="zs-box-right"> <a href="#popupEpicondyle" id="btnEpicondyle" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupEpicondyle" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorEpicondyle" class="infoError"></div></p>
              <div id="infoEpicondyle" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Epicondyle_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Epicondyle_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Epicondyle_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Epicondyle"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Epicondyle"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
             <li id="li-Frame">
            <div class="zs-box-left">
              <label for="Frame"><span lang="en">Frame index</span></label>
              <input type="number" placeholder="" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Frame" id="Frame" value="" readonly />
            </div>
                  <div class="zs-box-right"> <a href="#popupFrame" id="btnFrame" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupFrame" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorFrame" class="infoError"></div></p>
              <div id="infoFrame" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Frame_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Frame_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Frame_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Frame"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Frame"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>          
            <li id="li-Metric">
            <div class="zs-box-left">
              <label for="Metric"><span lang="en">Metric index</span></label>
              <input type="number" placeholder="" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Metric" id="Metric" value="" />
            </div>
                  <div class="zs-box-right"> <a href="#popupMetric" id="btnMetric" data-rel="popup" class="ui-btn ui-corner-all mmodeInfoButton ui-shadow ui-icon-info ui-btn-icon-notext" data-transition="pop" data-theme="b">Info</a> </div>
            <div data-role="popup" id="popupMetric" class="ui-content" data-theme="a" style="max-width:350px;">
              <p class="zscoreinfo"><div id="errorMetric" class="infoError"></div></p>
              <div id="infoMetric" class="zscoreinfo"></div> 
              </div>   
            <div class="zs-box-center">
              <div class="zs-diagram">
                <div class="zs-baseline">
                  <div class="zs-lln-marker">
                    <div class="zs-lln-value">
                      <div id="Metric_lln"></div>
                    </div>
                  </div>
                  <div class="zs-uln-marker">
                    <div class="zs-uln-value">
                      <div id="Metric_uln"></div>
                    </div>
                  </div>
                  <div class="zs-mean-marker">
                    <div class="zs-mean-value">
                      <div id="Metric_mean"></div>
                    </div>
                  </div>
                  <div class="zs-result-marker">
                    <div class="zs-result-value">
                      <div id="sds_Metric"></div>
                    </div>
                    <div class="zs-result-perc-value">
                      <div id="percentile_Metric"></div>
                    </div>
                  </div>
                  <div class="zs-arrow-left"></div>
                  <div class="zs-arrow-right"></div>
                </div>
              </div>
            </div>
            <div class="clearboth"></div>
          </li>            
                      
        </ul>
      </form>
    </div>
  </div>
 