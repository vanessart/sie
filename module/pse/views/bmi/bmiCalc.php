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
<style>
    input.form-control-plaintext {
        background-color: white !important;
        margin-left: 5px !important;
    }

    .input-group, .input-group table {
        width: 100%;
    }

    fieldset.add-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.add-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:inherit; /* Or auto */
        padding:0 10px; /* To give a bit of padding on the left and right */
        border-bottom:none;
        margin-top: -10px;
        background-color: white;
    }
</style>

<input type="hidden" name="units_flip" id="units_flip" value="metric">
<input type="hidden" name="dateInput_flip" id="dateInput_flip" value="desktop">
<input type="hidden" name="Head" id="Head" />
<input type="hidden" name="Waist" id="Waist" />
<input type="hidden" name="Hip" id="Hip" />
<input type="hidden" name="Triceps" id="Triceps" />
<input type="hidden" name="Subscapular" id="Subscapular" />
<input type="hidden" name="Frame" id="Frame" readonly />
<input type="hidden" name="Suprailiac" id="Suprailiac" />
<input type="hidden" name="Epicondyle" id="Epicondyle" />
<input type="hidden" name="Upperarm" id="Upperarm" />
<input type="hidden" name="Hipheight" id="Hipheight" readonly />
<input type="hidden" name="Metric" id="Metric" />
<input type="hidden" name="Growthrateheight" id="Growthrateheight"/>
<input type="hidden" name="Growthratehead" id="Growthratehead" />
<input type="hidden" name="Chestwidth" id="Chestwidth" />
<input type="hidden" name="Chestdepth" id="Chestdepth" />
<input type="hidden" name="Weightweek" id="Weightweek"/>
<input type="hidden" name="Chestcircumference" id="Chestcircumference" />
<input type="hidden" name="Weightheight" id="Weightheight" />

	<?= toolErp::divAlert('info','Os gráficos representam o intervalo: -2z, 0z, +2z') ?>
  <div data-role="content" data-theme="a" >
    <div class="bodyWrapper">
      <form name="form" id="form" action="" method="post">
      	<fieldset class="add-border">
      		<br>
	        <fieldset data-role="controlgroup" id="radio" data-type="horizontal"  style="text-align: left;">
	        	<div class="row">
	        		<div class="col">
	          			<label for="radio_male"><span lang="en">Menino</span></label>
	        			<input type="radio" name="radio" id="radio_male" value="male" checked="checked" />
	        		</div>
	        		<div class="col">
	        			<input type="radio" name="radio" id="radio_female" value="female" />
	          			<label for="radio_female"><span lang="en">Menina</span></label>
	        		</div>
	        	</div>
	        </fieldset>
	        <div id="dateInputMobile" style="display: none;">
	          <div class="pedzDate">
	            <label for="birthday"><span lang="en">Data de Nascimento</span></label>
	            <input name="birthday" id="birthday">
	          </div>
	          <div class="pedzDate">
	            <label for="currentdate" ><span lang="en">Data Atual</span></label>
	            <input name="currentdate" id="currentdate"  >
	          </div>
	        </div>
        	<br>
	        <div id="dateInputDesktop" style="text-align: left;">
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
	        <br>
	        <div id="headerResult"><span lang="en">Idade</span>: <span id="calculatedAgeYears"></span> <span style="display: none;" id="calculatedAgeMonthsUpper"></span> <span id="calculatedAgeMonthsLower" style="display: none;">12</span> <span lang="en">anos</span> </div>
        </fieldset>
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
        </div>
        <ul class="zs-list">
          <li id="li-Weight">
            <div class="zs-box-left">
              <label><span lang="en">Peso</span></label>
              <input type="number" placeholder="kg" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Weight" id="Weight" value="" />
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
          <li id="li-Height">
            <div class="zs-box-left">
              <label for="Height"><span lang="en">Altura</span></label>
              <input type="number" placeholder="cm" value="" data-mini="true" onKeyUp="bmi_calc()" maxlength="6"  name="Height" id="Height"/>
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
            <div class="clearboth"></div>
          <li id="li-Bmi">
            <div class="zs-box-left">
              <label for="Bmi"><span lang="en">IMC</span></label>
              <input type="number" placeholder="kg/m2" data-mini="true" onKeyUp="bmi_calc()" readonly maxlength="6"  name="Bmi" id="Bmi" value="" />
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
        </ul>
      </form>
    </div>
  </div>
 