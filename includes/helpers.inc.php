<?php

/* Uses Google geocode to get JSON object of address lookup
 * @param ($streetAddress, $city, $state, $zip)
 * Returns geocode JSON object
 */
function getAddressData ($streetAddress, $city, $state, $zip) {
    // building the JSON URL string for Google API call 
    $gStrAddress = str_replace(' ', '+', $streetAddress).",";
    $gCity    = '+'.str_replace(' ', '+', $city).",";
    $gState   = '+'.str_replace(' ', '+', $state);
    $gZip     = isset($zip)? '+'.str_replace(' ', '', $zip) : '';    
    
    $gAddress = $gStrAddress . $gCity . $gState . $gZip;       
    $url = "http://maps.google.com/maps/api/geocode/json?address=$gAddress&sensor=false";
        
    $jsonData   = file_get_contents($url);
    return json_decode($jsonData);
}
 
function getLongLat ($streetAddress, $city, $state, $zip) {
    $data = getAddressData ($streetAddress, $city, $state, $zip);
    $lat = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $long = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

    $coord = array('lat'=> $lat, 'long'=> $long);
    return $coord;
}
    
/* Uses Google geocode to check status of address url, if status 'OK' returns TRUE, else returns FALSE
 * @param $addressData JSON object format
 */
function validAddress ($streetAddress, $city, $state, $zip) {
    $data = getAddressData ($streetAddress, $city, $state, $zip);
    return $data->{'status'} == 'OK' ? TRUE : FALSE;
}

/* Reorder keys in array to match a particular order
 * @param $array is target array to reorder
 * @param $keynames is comma separated string of keynames or array of keynames in desired order
 */
function array_reorder_keys(&$array, $keynames){
    if(empty($array) || !is_array($array) || empty($keynames)) return;
    if(!is_array($keynames)) $keynames = explode(',',$keynames);
    if(!empty($keynames)) $keynames = array_reverse($keynames);
    foreach($keynames as $n){
        if(array_key_exists($n, $array)){
            $newarray = array($n=>$array[$n]); //copy the node before unsetting
            unset($array[$n]); //remove the node
            $array = $newarray + array_filter($array); //combine copy with filtered array
        }
    }
}

// US states
$usStates = array(
    'AL'=>'Alabama',
    'AK'=>'Alaska',
    'AZ'=>'Arizona',
    'AR'=>'Arkansas',
    'CA'=>'California',
    'CO'=>'Colorado',
    'CT'=>'Connecticut',
    'DE'=>'Delaware',
    'DC'=>'District of Columbia',
    'FL'=>'Florida',
    'GA'=>'Georgia',
    'HI'=>'Hawaii',
    'ID'=>'Idaho',
    'IL'=>'Illinois',
    'IN'=>'Indiana',
    'IA'=>'Iowa',
    'KS'=>'Kansas',
    'KY'=>'Kentucky',
    'LA'=>'Louisiana',
    'ME'=>'Maine',
    'MD'=>'Maryland',
    'MA'=>'Massachusetts',
    'MI'=>'Michigan',
    'MN'=>'Minnesota',
    'MS'=>'Mississippi',
    'MO'=>'Missouri',
    'MT'=>'Montana',
    'NE'=>'Nebraska',
    'NV'=>'Nevada',
    'NH'=>'New Hampshire',
    'NJ'=>'New Jersey',
    'NM'=>'New Mexico',
    'NY'=>'New York',
    'NC'=>'North Carolina',
    'ND'=>'North Dakota',
    'OH'=>'Ohio',
    'OK'=>'Oklahoma',
    'OR'=>'Oregon',
    'PA'=>'Pennsylvania',
    'RI'=>'Rhode Island',
    'SC'=>'South Carolina',
    'SD'=>'South Dakota',
    'TN'=>'Tennessee',
    'TX'=>'Texas',
    'UT'=>'Utah',
    'VT'=>'Vermont',
    'VA'=>'Virginia',
    'WA'=>'Washington',
    'WV'=>'West Virginia',
    'WI'=>'Wisconsin',
    'WY'=>'Wyoming'
    );

// School grades
$ages = array( 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18);
    
// Little league field positions
$positions = array(
    'catcher',
    'first base',
    'second base',
    'third base',
    'pitcher',
    'right field',
    'center field',
    'left field',
    'short stop'
    );

// Returns TRUE if $input matches captcha phrase in $_SESSION['captcha']
function valid_captcha($input) {
    $code = $_SESSION['captcha'];
    unset($_SESSION['captcha']);
    return (strcasecmp($input, $code) == 0);
} 
   
// Returns new string with html special characters escaped in string $text
function html($text) {
	return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
}

// Echos string $text with html special characters escaped
function htmlout($text) {
	echo html($text);
}

// This function builds a search query from the passed query and sort setting
function sortQuery($query, $sort) {

    // Sort the search query using the sort setting
    switch ($sort) {
        // Ascending by first name
        case 1:
          $query .= " ORDER BY first_name";
          break;
        // Descending by first name
        case 2:
          $query .= " ORDER BY first_name DESC";
          break;
        // Ascending by last name
        case 3:
          $query .= " ORDER BY last_name";
          break;
        // Descending by last name
        case 4:
          $query .= " ORDER BY last_name DESC";
          break;
        default:
          $query .= " ORDER BY last_name";
          break;        
    }
    return $query;
}

// This function builds heading links based on the specified sort setting and user search query string
function generate_sort_links($user_search, $sort) {
    $sort_links = '';
    
    switch ($sort) {
    case 1:
      $sort_links .= '<th><a href = "?' . $user_search . '&sort=2">First Name</a></th>';
      $sort_links .= '<th><a href = "?' . $user_search . '&sort=3">Last Name</a></th>';
      break;
    case 3:
      $sort_links .= '<th><a href = "?' . $user_search . '&sort=1">First Name</a></th>';
      $sort_links .= '<th><a href = "?' . $user_search . '&sort=4">Last Name</a></th>';
      break;
    default:
      $sort_links .= '<th><a href = "?' . $user_search . '&sort=1">First Name</a></th>';
      $sort_links .= '<th><a href = "?' . $user_search . '&sort=3">Last Name</a></th>';
    }
    return $sort_links;
}

// This function builds navigational page links based on the current page and the number of pages
function generate_page_links($user_search, $sort, $cur_page, $num_pages) {
    $page_links = '';
    
    // If this page is not the first page, generate the "previous" link
    if ($cur_page > 1) {
      $page_links .= '<a href="?' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page - 1) . '"><-</a> ';
    }
    else {
      $page_links .= '<- ';
    }
    
    // Loop through the pages generating the page number links
    for ($i = 1; $i <= $num_pages; $i++) {
      if ($cur_page == $i) {
        $page_links .= ' ' . $i;
      }
      else {
        $page_links .= ' <a href="?' . $user_search . '&sort=' . $sort . '&page=' . $i . '"> ' . $i . '</a>';
      }
    }
    
    // If this page is not the last page, generate the "next" link
    if ($cur_page < $num_pages) {
      $page_links .= ' <a href="?' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page + 1) . '">-></a>';
    }
    else {
    $page_links .= ' ->';
    }
    
    return $page_links;
}