<?php
//
// Get a list of all current timezones, doing this dynamically because of DST
//
function timezoneList()
{
    $timezoneIdentifiers = DateTimeZone::listIdentifiers();
    $utcTime = new DateTime('now', new DateTimeZone('UTC'));
 
    $tempTimezones = array();
    foreach ($timezoneIdentifiers as $timezoneIdentifier) {
        $currentTimezone = new DateTimeZone($timezoneIdentifier);
 
        $tempTimezones[] = array(
            'offset' => (int)$currentTimezone->getOffset($utcTime),
            'identifier' => $timezoneIdentifier
        );
    }
 
    // Sort the array by offset,identifier ascending
    usort($tempTimezones, function($a, $b) {
		return ($a['offset'] == $b['offset'])
			? strcmp($a['identifier'], $b['identifier'])
			: $a['offset'] - $b['offset'];
    });
 
	$timezoneList = array();
    foreach ($tempTimezones as $tz) {
		$sign = ($tz['offset'] > 0) ? '+' : '-';
		$offset = gmdate('H:i', abs($tz['offset']));
        $timezoneList[$tz['identifier']] = $sign . $offset;
    }
 
    return $timezoneList;
}


//
// Compact List of for Timezone Dropdown while getting current offsets
//
function buildTimeZoneList ()
{
	$defaultZone = "America/Los_Angeles";

	$displayZones["Pacific/Midway"] = "Midway Island, Samoa";  
	$displayZones["Pacific/Honolulu"] = "Hawaii";  
	$displayZones["Pacific/Tahiti"] = "Tahiti";  
	$displayZones["America/Anchorage"] = "Alaska";  
	$displayZones["America/Los_Angeles"] = "Los Angeles, San Francisco, Seattle";  
	$displayZones["America/Phoenix"] = "Phoenix";  
	$displayZones["America/Tijuana"] = "Tijuana, Baja California";  
	$displayZones["America/Denver"] = "Mountain Time (US & Canada)";  
	$displayZones["America/Bogota"] = "Bogota/Cayman/Jamaica";  
	$displayZones["America/Mexico_City"] = "Mexico_City";  
	$displayZones["America/Chicago"] = "Chicago";  
	$displayZones["America/Indiana/Knox"] = "Indiana";  
	$displayZones["America/New_York"] = "New York";  
	$displayZones["America/Toronto"] = "Toronto";  
	$displayZones["America/Puerto_Rico"] = "Puerto Rico";  
	$displayZones["America/Argentina/Buenos_Aires"] = "Buenos Aires/San Juan";  
	$displayZones["America/Goose_Bay"] = "Greenland";  
	$displayZones["America/Sao_Paulo"] = "Sao Paulo";  
	$displayZones["Atlantic/Bermuda"] = "Bermuda";  
	$displayZones["America/St_Johns"] = "St Johns/Mid-Atlantic";  
	$displayZones["Atlantic/Cape_Verde"] = "Cape Verde";  
	$displayZones["UTC"] = "UTC";  
	$displayZones["Africa/Monrovia"] = "Monrovia, Reykjavik";  
	$displayZones["Europe/London"] = "Dublin, Edinburgh, Lisbon, London";  
	$displayZones["Africa/Casablanca"] = "Casablanca, Lagos, Tunis";  
	$displayZones["Africa/Cairo"] = "Cairo, Johannesburg, Tripoli";  
	$displayZones["Europe/Amsterdam"] = "Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna";  
	$displayZones["Europe/Belgrade"] = "Belgrade, Bratislava, Budapest, Ljubljana, Prague";  
	$displayZones["Europe/Brussels"] = "Brussels, Copenhagen, Madrid, Paris, Warsaw";  
	$displayZones["Europe/Athens"] = "Athens, Bucharest, Istanbul";  
	$displayZones["Asia/Jerusalem"] = "Jerusalem, Syowa, Moscow, St. Petersburg, Volgograd";  
	$displayZones["Europe/Bucharest"] = "Brussels, Copenhagen, Madrid, Paris, Warsaw";  
	$displayZones["Europe/Belgrade"] = "Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius";  
	$displayZones["Asia/Beirut"] = "Beirut, Cairo, Gaza, Kuwait";  
	$displayZones["Asia/Tehran"] = "Tehran";  
	$displayZones["Asia/Kabul"] = "Kabul";  
	$displayZones["Asia/Tashkent"] = "Islamabad, Karachi, Tashkent";  
	$displayZones["Asia/Colombo"] = "Colombo, Chennai, Kolkata, Mumbai, New Delhi";  
	$displayZones["Asia/Kathmandu"] = "Kathmandu";  
	$displayZones["Asia/Almaty"] = "Almaty, Novosibirsk";  
	$displayZones["Asia/Rangoon"] = "Yangon (Rangoon)";  
	$displayZones["Asia/Bangkok"] = "Bangkok, Hanoi, Jakarta";  
	$displayZones["Asia/Hong_Kong"] = "Beijing, Chongqing, Hong Kong, Urumqi";  
	$displayZones["Asia/Taipei"] = "Taipei, Perth";  
	$displayZones["Asia/Pyongyang"] = "Pyongyang";  
	$displayZones["Australia/Eucla"] = "Eucla";  
	$displayZones["Asia/Tokyo"] = "Osaka, Sapporo, Tokyo";  
	$displayZones["Australia/Darwin"] = "Darwin";  
	$displayZones["Australia/Brisbane"] = "Brisbane, Guam";  
	$displayZones["Australia/Sydney"] = "Canberra, Melbourne, Sydney, Pohnpei";  
	$displayZones["Pacific/Fiji"] = "Fiji, Wake, Wallis";  
	$displayZones["Pacific/Auckland"] = "Auckland, Enderbury";  
	$displayZones["Pacific/Chatham"] = "Chatham";  
	$displayZones["Pacific/Apia"] = "Apia, Kiritimati";  

	$timezoneList = timezoneList();
	
	foreach ($displayZones as $tzone => $location)
	{		
		if ($tzone == $defaultZone)
		{
			$timeSelect .= '<option selected="selected" value="' . $timezoneList[$tzone] . '"> (GMT' . $timezoneList[$tzone] . ') ' . $location . '</option>';
		}
		else
		{
			$timeSelect .= '<option value="' . $timezoneList[$tzone] . '"> (GMT' . $timezoneList[$tzone] . ') ' . $location . '</option>';
		} 
	}
	echo $timeSelect;
}

buildTimeZoneList();
?>