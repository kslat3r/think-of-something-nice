<?php

class Form {

	private $required = array();
	private $error;

	function __construct($name) {
		$this->name	= $name;
		$this->alert = Alert::fetch('Alert');
	}

	function set_required($data)
	{
		$this->required	= $data;
	}

	public function posted() {
		if (isset($_POST) && isset($_POST['postedcheck']) && $_POST['postedcheck']==$this->name) {
			return true;
		}
		return false;
	}

	public function populated() {		
		global $Alert;
		$out = true;
		if (!is_array($this->required)) {
				return true;
		}
		foreach($this->required as $key => $value) {
			if (!isset($_POST[$key]) || $_POST[$key]==''){
				$Alert->set_alert('error', $value);
				$out = false;
			}	
		}
		return $out;
	}

	function get_value($key) {
		if (isset($_POST[$key])) {
			return $_POST[$key];
		} 
		else {
			return '';
		}		
	}

	public function receive($postvars, $checkbox=false, $sanitise=true) {
		foreach($postvars as $val) {
			if (isset($_POST[$val]) && !is_array($_POST[$val])) {
				if ($sanitise == true) {
					$data[$val]	= $this->sanitise($_POST[$val]);
				}
				else {
					$data[$val] = $_POST[$val];
				}
			}
			else if (isset($_POST[$val]) && is_array($_POST[$val])) {
				$i=0;
				foreach ($_POST[$val] as $var) {
					if ($sanitise == true) {
						$data[$i][$val] = $this->sanitise($var);
					}
					else {
						$data[$i][$val] = $var;
					}
					$i++;
				}
			}
			else if (!isset($_POST[$val]) && $checkbox==true) {
				$data[$val] = "0";			
			}			
	    }
		return $data;
	}

	public function check_password($password1, $password2) {
		if (($password1 != $password2) || empty($password1) || empty($password2)){
			$this->alert->set_alert('error', 'Your passwords do not match.');
			return false;
		}
		return true;
	}
	
	public function check_email($email) {
		if (!preg_match('/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/', $email)) {
			$this->alert->set_alert('error', 'Your e-mail address appears to be invalid.');
			return false;
		}
		return true;
	}
	
	public function strip_script($string) {
	    // Prevent inline scripting
    	$string = eregi_replace("<script[^>]*>.*</script[^>]*>", "", $string);
	    // Prevent linking to source files
    	$string = eregi_replace("<script[^>]*>", "", $string);
    	return $string;
	}
	
	public function sanitise($value) {
		$out = strip_tags($value);
		$out = stripslashes($out);
		return $out;
	}
	
	public function dayselect() {
		global $CurrentUser;
		if (is_object($CurrentUser)) {
			$datearray = explode("-", $CurrentUser->userDOB());
		}		
		echo '<option value="">Day</option>';
		for ($i=1; $i<=31; $i++) {
			echo '<option value="'.Util::pad($i).'"';
			if (isset($datearray) && $datearray[2] == Util::pad($i)) { 
				echo ' selected="selected"';
			}
			elseif (isset($_POST['userBirthDay']) && $_POST['userBirthDay'] == Util::pad($i)) {
				echo ' selected="selected"';
			}
			echo '>'.Util::pad($i).'</option>';
		}		
	}
	
	public function monthselect() {
		global $CurrentUser;
		if (is_object($CurrentUser)) {
			$datearray = explode("-", $CurrentUser->userDOB());
		}				
		echo '<option value="">Month</option>';
		for ($i=1; $i<=12; $i++) {
			echo '<option value="'.Util::pad($i).'"';
			if (isset($datearray) && $datearray[1] == Util::pad($i)) { 
				echo ' selected="selected"';
			}
			else if (isset($_POST['userBirthMonth']) && $_POST['userBirthMonth'] == Util::pad($i)) {
				echo ' selected="selected"';
			}
			echo '>'.Util::pad($i).'</option>';
		}		
	}
	
	public function yearselect() {
		global $CurrentUser;		
		if (is_object($CurrentUser)) {
			$datearray = explode("-", $CurrentUser->userDOB());
		}
		echo '<option value="">Year</option>';
		$year = date('Y');		
		for ($i = $year; $i >= $year - 90; $i--) {
			echo '<option value="'.Util::pad($i).'"';
			if (isset($datearray) && $datearray[0] == Util::pad($i)) { 
				echo ' selected="selected"';
			}
			else if (isset($_POST['userBirthYear']) && $_POST['userBirthYear'] == Util::pad($i)) {
				echo ' selected="selected"';
			}
			echo '>'.Util::pad($i).'</option>';
		}	
	}
	
	public function countryselect() {
		global $CurrentUser;
		$optionsarray = array();
		$i = 0;			
		$optionsarray[$i] = '<option value="">--Select--</option>'; $i++;
		$optionsarray[$i] = '<option value="AF">Afghanistan</option>'; $i++;
		$optionsarray[$i] = '<option value="AX">Aland Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="AL">Albania</option>'; $i++;
		$optionsarray[$i] = '<option value="DZ">Algeria</option>'; $i++;
		$optionsarray[$i] = '<option value="AS">American Samoa</option>'; $i++;
		$optionsarray[$i] = '<option value="AD">Andorra</option>'; $i++;
		$optionsarray[$i] = '<option value="AO">Angola</option>'; $i++;
		$optionsarray[$i] = '<option value="AI">Anguilla</option>'; $i++;
		$optionsarray[$i] = '<option value="AQ">Antarctica</option>'; $i++;
		$optionsarray[$i] = '<option value="AG">Antigua and Barbuda</option>'; $i++;
		$optionsarray[$i] = '<option value="AR">Argentina</option>'; $i++;
		$optionsarray[$i] = '<option value="AM">Armenia</option>'; $i++;
		$optionsarray[$i] = '<option value="AW">Aruba</option>'; $i++;
		$optionsarray[$i] = '<option value="AU">Australia</option>'; $i++;
		$optionsarray[$i] = '<option value="AT">Austria</option>'; $i++;
		$optionsarray[$i] = '<option value="AZ">Azerbaijan</option>'; $i++;
		$optionsarray[$i] = '<option value="BS">Bahamas</option>'; $i++;
		$optionsarray[$i] = '<option value="BH">Bahrain</option>'; $i++;
		$optionsarray[$i] = '<option value="BD">Bangladesh</option>'; $i++;
		$optionsarray[$i] = '<option value="BB">Barbados</option>'; $i++;
		$optionsarray[$i] = '<option value="BY">Belarus</option>'; $i++;
		$optionsarray[$i] = '<option value="BE">Belgium</option>'; $i++;
		$optionsarray[$i] = '<option value="BZ">Belize</option>'; $i++;
		$optionsarray[$i] = '<option value="BJ">Benin</option>'; $i++;
		$optionsarray[$i] = '<option value="BM">Bermuda</option>'; $i++;
		$optionsarray[$i] = '<option value="BT">Bhutan</option>'; $i++;
		$optionsarray[$i] = '<option value="BO">Bolivia</option>'; $i++;
		$optionsarray[$i] = '<option value="BA">Bosnia and Herzegovina</option>'; $i++;
		$optionsarray[$i] = '<option value="BW">Botswana</option>'; $i++;
		$optionsarray[$i] = '<option value="BV">Bouvet Island</option>'; $i++;
		$optionsarray[$i] = '<option value="BR">Brazil</option>'; $i++;
		$optionsarray[$i] = '<option value="IO">British Indian Ocean Territory</option>'; $i++;
		$optionsarray[$i] = '<option value="BN">Brunei Darussalam</option>'; $i++;
		$optionsarray[$i] = '<option value="BG">Bulgaria</option>'; $i++;
		$optionsarray[$i] = '<option value="BF">Burkina Faso</option>'; $i++;
		$optionsarray[$i] = '<option value="BI">Burundi</option>'; $i++;
		$optionsarray[$i] = '<option value="KH">Cambodia</option>'; $i++;
		$optionsarray[$i] = '<option value="CM">Cameroon</option>'; $i++;
		$optionsarray[$i] = '<option value="CA">Canada</option>'; $i++;
		$optionsarray[$i] = '<option value="CV">Cape Verde</option>'; $i++;
		$optionsarray[$i] = '<option value="KY">Cayman Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="CF">Central African Republic</option>'; $i++;
		$optionsarray[$i] = '<option value="TD">Chad</option>'; $i++;
		$optionsarray[$i] = '<option value="CL">Chile</option>'; $i++;
		$optionsarray[$i] = '<option value="CN">China</option>'; $i++;
		$optionsarray[$i] = '<option value="CX">Christmas Island</option>'; $i++;
		$optionsarray[$i] = '<option value="CC">Cocos (Keeling) Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="CO">Colombia</option>'; $i++;
		$optionsarray[$i] = '<option value="KM">Comoros</option>'; $i++;
		$optionsarray[$i] = '<option value="CG">Congo</option>'; $i++;
		$optionsarray[$i] = '<option value="CK">Cook Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="CR">Costa Rica</option>'; $i++;
		$optionsarray[$i] = '<option value="CI">Cote D\'Ivoire (Ivory Coast)</option>'; $i++;
		$optionsarray[$i] = '<option value="HR">Croatia (Hrvatska)</option>'; $i++;
		$optionsarray[$i] = '<option value="CU">Cuba</option>'; $i++;
		$optionsarray[$i] = '<option value="CY">Cyprus</option>'; $i++;
		$optionsarray[$i] = '<option value="CZ">Czech Republic</option>'; $i++;
		$optionsarray[$i] = '<option value="CD">Democratic Republic of the Congo</option>'; $i++;
		$optionsarray[$i] = '<option value="DK">Denmark</option>'; $i++;
		$optionsarray[$i] = '<option value="DJ">Djibouti</option>'; $i++;
		$optionsarray[$i] = '<option value="DM">Dominica</option>'; $i++;
		$optionsarray[$i] = '<option value="DO">Dominican Republic</option>'; $i++;
		$optionsarray[$i] = '<option value="TP">East Timor</option>'; $i++;
		$optionsarray[$i] = '<option value="EC">Ecuador</option>'; $i++;
		$optionsarray[$i] = '<option value="EG">Egypt</option>'; $i++;
		$optionsarray[$i] = '<option value="SV">El Salvador</option>'; $i++;
		$optionsarray[$i] = '<option value="GQ">Equatorial Guinea</option>'; $i++;
		$optionsarray[$i] = '<option value="ER">Eritrea</option>'; $i++;
		$optionsarray[$i] = '<option value="EE">Estonia</option>'; $i++;
		$optionsarray[$i] = '<option value="ET">Ethiopia</option>'; $i++;
		$optionsarray[$i] = '<option value="FK">Falkland Islands (Malvinas)</option>'; $i++;
		$optionsarray[$i] = '<option value="FO">Faroe Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="FM">Federated States of Micronesia</option>'; $i++;
		$optionsarray[$i] = '<option value="FJ">Fiji</option>'; $i++;
		$optionsarray[$i] = '<option value="FI">Finland</option>'; $i++;
		$optionsarray[$i] = '<option value="FR">France</option>'; $i++;
		$optionsarray[$i] = '<option value="FX">France, Metropolitan</option>'; $i++;
		$optionsarray[$i] = '<option value="GF">French Guiana</option>'; $i++;
		$optionsarray[$i] = '<option value="PF">French Polynesia</option>'; $i++;
		$optionsarray[$i] = '<option value="TF">French Southern Territories</option>'; $i++;
		$optionsarray[$i] = '<option value="GA">Gabon</option>'; $i++;
		$optionsarray[$i] = '<option value="GM">Gambia</option>'; $i++;
		$optionsarray[$i] = '<option value="GE">Georgia</option>'; $i++;
		$optionsarray[$i] = '<option value="DE">Germany</option>'; $i++;
		$optionsarray[$i] = '<option value="GH">Ghana</option>'; $i++;
		$optionsarray[$i] = '<option value="GI">Gibraltar</option>'; $i++;
		$optionsarray[$i] = '<option value="GB">Great Britain</option>'; $i++;
		$optionsarray[$i] = '<option value="GR">Greece</option>'; $i++;
		$optionsarray[$i] = '<option value="GL">Greenland</option>'; $i++;
		$optionsarray[$i] = '<option value="GD">Grenada</option>'; $i++;
		$optionsarray[$i] = '<option value="GP">Guadeloupe</option>'; $i++;
		$optionsarray[$i] = '<option value="GU">Guam</option>'; $i++;
		$optionsarray[$i] = '<option value="GT">Guatemala</option>'; $i++;
		$optionsarray[$i] = '<option value="GN">Guinea</option>'; $i++;
		$optionsarray[$i] = '<option value="GW">Guinea-Bissau</option>'; $i++;
		$optionsarray[$i] = '<option value="GY">Guyana</option>'; $i++;
		$optionsarray[$i] = '<option value="HT">Haiti</option>'; $i++;
		$optionsarray[$i] = '<option value="HM">Heard Island and McDonald Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="HN">Honduras</option>'; $i++;
		$optionsarray[$i] = '<option value="HK">Hong Kong</option>'; $i++;
		$optionsarray[$i] = '<option value="HU">Hungary</option>'; $i++;
		$optionsarray[$i] = '<option value="IS">Iceland</option>'; $i++;
		$optionsarray[$i] = '<option value="IN">India</option>'; $i++;
		$optionsarray[$i] = '<option value="ID">Indonesia</option>'; $i++;
		$optionsarray[$i] = '<option value="IR">Iran</option>'; $i++;
		$optionsarray[$i] = '<option value="IQ">Iraq</option>'; $i++;
		$optionsarray[$i] = '<option value="IE">Ireland</option>'; $i++;
		$optionsarray[$i] = '<option value="IL">Israel</option>'; $i++;
		$optionsarray[$i] = '<option value="IT">Italy</option>'; $i++;
		$optionsarray[$i] = '<option value="JM">Jamaica</option>'; $i++;
		$optionsarray[$i] = '<option value="JP">Japan</option>'; $i++;
		$optionsarray[$i] = '<option value="JO">Jordan</option>'; $i++;
		$optionsarray[$i] = '<option value="KZ">Kazakhstan</option>'; $i++;
		$optionsarray[$i] = '<option value="KE">Kenya</option>'; $i++;
		$optionsarray[$i] = '<option value="KI">Kiribati</option>'; $i++;
		$optionsarray[$i] = '<option value="KP">Korea (North)</option>'; $i++;
		$optionsarray[$i] = '<option value="KR">Korea (South)</option>'; $i++;
		$optionsarray[$i] = '<option value="KW">Kuwait</option>'; $i++;
		$optionsarray[$i] = '<option value="KG">Kyrgyzstan</option>'; $i++;
		$optionsarray[$i] = '<option value="LA">Laos</option>'; $i++;
		$optionsarray[$i] = '<option value="LV">Latvia</option>'; $i++;
		$optionsarray[$i] = '<option value="LB">Lebanon</option>'; $i++;
		$optionsarray[$i] = '<option value="LS">Lesotho</option>'; $i++;
		$optionsarray[$i] = '<option value="LR">Liberia</option>'; $i++;
		$optionsarray[$i] = '<option value="LY">Libya</option>'; $i++;
		$optionsarray[$i] = '<option value="LI">Liechtenstein</option>'; $i++;
		$optionsarray[$i] = '<option value="LT">Lithuania</option>'; $i++;
		$optionsarray[$i] = '<option value="LU">Luxembourg</option>'; $i++;
		$optionsarray[$i] = '<option value="MO">Macao</option>'; $i++;
		$optionsarray[$i] = '<option value="MK">Macedonia</option>'; $i++;
		$optionsarray[$i] = '<option value="MG">Madagascar</option>'; $i++;
		$optionsarray[$i] = '<option value="MW">Malawi</option>'; $i++;
		$optionsarray[$i] = '<option value="MY">Malaysia</option>'; $i++;
		$optionsarray[$i] = '<option value="MV">Maldives</option>'; $i++;
		$optionsarray[$i] = '<option value="ML">Mali</option>'; $i++;
		$optionsarray[$i] = '<option value="MT">Malta</option>'; $i++;
		$optionsarray[$i] = '<option value="MH">Marshall Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="MQ">Martinique</option>'; $i++;
		$optionsarray[$i] = '<option value="MR">Mauritania</option>'; $i++;
		$optionsarray[$i] = '<option value="MU">Mauritius</option>'; $i++;
		$optionsarray[$i] = '<option value="YT">Mayotte</option>'; $i++;
		$optionsarray[$i] = '<option value="MX">Mexico</option>'; $i++;
		$optionsarray[$i] = '<option value="MD">Moldova</option>'; $i++;
		$optionsarray[$i] = '<option value="MC">Monaco</option>'; $i++;
		$optionsarray[$i] = '<option value="MN">Mongolia</option>'; $i++;
		$optionsarray[$i] = '<option value="MS">Montserrat</option>'; $i++;
		$optionsarray[$i] = '<option value="MA">Morocco</option>'; $i++;
		$optionsarray[$i] = '<option value="MZ">Mozambique</option>'; $i++;
		$optionsarray[$i] = '<option value="MM">Myanmar</option>'; $i++;
		$optionsarray[$i] = '<option value="NA">Namibia</option>'; $i++;
		$optionsarray[$i] = '<option value="NR">Nauru</option>'; $i++;
		$optionsarray[$i] = '<option value="NP">Nepal</option>'; $i++;
		$optionsarray[$i] = '<option value="NL">Netherlands</option>'; $i++;
		$optionsarray[$i] = '<option value="AN">Netherlands Antilles</option>'; $i++;
		$optionsarray[$i] = '<option value="NC">New Caledonia</option>'; $i++;
		$optionsarray[$i] = '<option value="NZ">New Zealand (Aotearoa)</option>'; $i++;
		$optionsarray[$i] = '<option value="NI">Nicaragua</option>'; $i++;
		$optionsarray[$i] = '<option value="NE">Niger</option>'; $i++;
		$optionsarray[$i] = '<option value="NG">Nigeria</option>'; $i++;
		$optionsarray[$i] = '<option value="NU">Niue</option>'; $i++;
		$optionsarray[$i] = '<option value="NF">Norfolk Island</option>'; $i++;
		$optionsarray[$i] = '<option value="MP">Northern Mariana Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="NO">Norway</option>'; $i++;
		$optionsarray[$i] = '<option value="OM">Oman</option>'; $i++;
		$optionsarray[$i] = '<option value="PK">Pakistan</option>'; $i++;
		$optionsarray[$i] = '<option value="PW">Palau</option>'; $i++;
		$optionsarray[$i] = '<option value="PS">Palestinian Territory</option>'; $i++;
		$optionsarray[$i] = '<option value="PA">Panama</option>'; $i++;
		$optionsarray[$i] = '<option value="PG">Papua New Guinea</option>'; $i++;
		$optionsarray[$i] = '<option value="PY">Paraguay</option>'; $i++;
		$optionsarray[$i] = '<option value="PE">Peru</option>'; $i++;
		$optionsarray[$i] = '<option value="PH">Philippines</option>'; $i++;
		$optionsarray[$i] = '<option value="PN">Pitcairn</option>'; $i++;
		$optionsarray[$i] = '<option value="PL">Poland</option>'; $i++;
		$optionsarray[$i] = '<option value="PT">Portugal</option>'; $i++;
		$optionsarray[$i] = '<option value="PR">Puerto Rico</option>'; $i++;
		$optionsarray[$i] = '<option value="QA">Qatar</option>'; $i++;
		$optionsarray[$i] = '<option value="RE">Reunion</option>'; $i++;
		$optionsarray[$i] = '<option value="RO">Romania</option>'; $i++;
		$optionsarray[$i] = '<option value="RU">Russian Federation</option>'; $i++;
		$optionsarray[$i] = '<option value="RW">Rwanda</option>'; $i++;
		$optionsarray[$i] = '<option value="GS">S. Georgia and S. Sandwich Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="SH">Saint Helena</option>'; $i++;
		$optionsarray[$i] = '<option value="KN">Saint Kitts and Nevis</option>'; $i++;
		$optionsarray[$i] = '<option value="LC">Saint Lucia</option>'; $i++;
		$optionsarray[$i] = '<option value="PM">Saint Pierre and Miquelon</option>'; $i++;
		$optionsarray[$i] = '<option value="VC">Saint Vincent and the Grenadines</option>'; $i++;
		$optionsarray[$i] = '<option value="WS">Samoa</option>'; $i++;
		$optionsarray[$i] = '<option value="SM">San Marino</option>'; $i++;
		$optionsarray[$i] = '<option value="ST">Sao Tome and Principe</option>'; $i++;
		$optionsarray[$i] = '<option value="SA">Saudi Arabia</option>'; $i++;
		$optionsarray[$i] = '<option value="SN">Senegal</option>'; $i++;
		$optionsarray[$i] = '<option value="CS">Serbia and Montenegro</option>'; $i++;
		$optionsarray[$i] = '<option value="SC">Seychelles</option>'; $i++;
		$optionsarray[$i] = '<option value="SL">Sierra Leone</option>'; $i++;
		$optionsarray[$i] = '<option value="SG">Singapore</option>'; $i++;
		$optionsarray[$i] = '<option value="SK">Slovakia</option>'; $i++;
		$optionsarray[$i] = '<option value="SI">Slovenia</option>'; $i++;
		$optionsarray[$i] = '<option value="SB">Solomon Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="SO">Somalia</option>'; $i++;
		$optionsarray[$i] = '<option value="ZA">South Africa</option>'; $i++;
		$optionsarray[$i] = '<option value="ES">Spain</option>'; $i++;
		$optionsarray[$i] = '<option value="LK">Sri Lanka</option>'; $i++;
		$optionsarray[$i] = '<option value="SD">Sudan</option>'; $i++;
		$optionsarray[$i] = '<option value="SR">Suriname</option>'; $i++;
		$optionsarray[$i] = '<option value="SJ">Svalbard and Jan Mayen</option>'; $i++;
		$optionsarray[$i] = '<option value="SZ">Swaziland</option>'; $i++;
		$optionsarray[$i] = '<option value="SE">Sweden</option>'; $i++;
		$optionsarray[$i] = '<option value="CH">Switzerland</option>'; $i++;
		$optionsarray[$i] = '<option value="SY">Syria</option>'; $i++;
		$optionsarray[$i] = '<option value="TW">Taiwan</option>'; $i++;
		$optionsarray[$i] = '<option value="TJ">Tajikistan</option>'; $i++;
		$optionsarray[$i] = '<option value="TZ">Tanzania</option>'; $i++;
		$optionsarray[$i] = '<option value="TH">Thailand</option>'; $i++;
		$optionsarray[$i] = '<option value="TL">Timor-Leste</option>'; $i++;
		$optionsarray[$i] = '<option value="TG">Togo</option>'; $i++;
		$optionsarray[$i] = '<option value="TK">Tokelau</option>'; $i++;
		$optionsarray[$i] = '<option value="TO">Tonga</option>'; $i++;
		$optionsarray[$i] = '<option value="TT">Trinidad and Tobago</option>'; $i++;
		$optionsarray[$i] = '<option value="TN">Tunisia</option>'; $i++;
		$optionsarray[$i] = '<option value="TR">Turkey</option>'; $i++;
		$optionsarray[$i] = '<option value="TM">Turkmenistan</option>'; $i++;
		$optionsarray[$i] = '<option value="TC">Turks and Caicos Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="TV">Tuvalu</option>'; $i++;
		$optionsarray[$i] = '<option value="UG">Uganda</option>'; $i++;
		$optionsarray[$i] = '<option value="UA">Ukraine</option>'; $i++;
		$optionsarray[$i] = '<option value="AE">United Arab Emirates</option>'; $i++;
		$optionsarray[$i] = '<option value="UK">United Kingdom</option>'; $i++;
		$optionsarray[$i] = '<option value="US">United States</option>'; $i++;
		$optionsarray[$i] = '<option value="UM">United States Minor Outlying Islands</option>'; $i++;
		$optionsarray[$i] = '<option value="UY">Uruguay</option>'; $i++;
		$optionsarray[$i] = '<option value="UZ">Uzbekistan</option>'; $i++;
		$optionsarray[$i] = '<option value="VU">Vanuatu</option>'; $i++;
		$optionsarray[$i] = '<option value="VA">Vatican City State (Holy See)</option>'; $i++;
		$optionsarray[$i] = '<option value="VE">Venezuela</option>'; $i++;
		$optionsarray[$i] = '<option value="VN">Viet Nam</option>'; $i++;
		$optionsarray[$i] = '<option value="VG">Virgin Islands (British)</option>'; $i++;
		$optionsarray[$i] = '<option value="VI">Virgin Islands (U.S.)</option>'; $i++;
		$optionsarray[$i] = '<option value="WF">Wallis and Futuna</option>'; $i++;
		$optionsarray[$i] = '<option value="EH">Western Sahara</option>'; $i++;
		$optionsarray[$i] = '<option value="YE">Yemen</option>'; $i++;
		$optionsarray[$i] = '<option value="ZM">Zambia</option>'; $i++;
		$optionsarray[$i] = '<option value="ZW">Zimbabwe</option>'; $i++;
		foreach ($optionsarray as $key=>$option) {
			if ($CurrentUser != false && strpos($option, $CurrentUser->userCountry(), 15) != false) {
				$optionsarray[$key] = preg_replace('%\"\>%', '" selected="selected">', $option);
			}
			elseif (isset($_POST['userCountry']) && strpos($option, $_POST['userCountry'], 15) != false) {
				 $optionsarray[$key] = preg_replace('%\"\>%', '" selected="selected">', $option);
			}
		}
		foreach ($optionsarray as $option) {
			echo $option;
		} 
	}
	
}
?>