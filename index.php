<?php 
include_once './includes/magicquotes.inc.php';
include_once './includes/session.inc.php';
include_once './includes/memberClass.inc.php';
include_once './includes/helpers.inc.php';


// login url for login script
$login_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';  
 
// Check for login status, if not logged in then go to login script
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: ' . $login_url);
    exit();
}

// Grab the sort setting else set to default 3 = ORDER BY last_name
$sort = isset($_GET['sort']) ? $_GET['sort'] : 3;

// Calculate pagination information
// Grab the current page setting else set to default 1
$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 5;  // number of results per page
$skip = (($cur_page - 1) * $results_per_page);


if (isset($_GET['action']) and $_GET['action'] == 'search') {

    // The basic SELECT statement
    $select = 'SELECT *';
    $from   = ' FROM signUp';
    $where  = ' WHERE TRUE';
    
    $placeholders = array();
    
    // Grab the search keywords from the URL using GET
    $userSearch = 'firstName=' . urlencode($_GET['firstName']) . '&lastName=' . urlencode($_GET['lastName']) . '&message=' . urlencode($_GET['message']) .  '&action=search';
    
    if ( ! empty($_GET['firstName'])) { // First name is selected 
        $where .= " AND first_name = :first_name";
        $placeholders[':first_name'] = $_GET['firstName'];
    }
      
    if ( ! empty($_GET['lastName'])) {  // Last name is selected
        $where .= " AND last_name = :last_name";
        $placeholders[':last_name'] = $_GET['lastName'];
    }
      
    if ( ! empty($_GET['message'])) {  // Some message text was specified
        
        // Extract the search keywords into an array
        $cleanSearchWords = explode(' ', str_replace(',', ' ', $_GET['message']));
        $searchWords = array();
        if (count($cleanSearchWords) > 0) {
            foreach ($cleanSearchWords as $word) {
                if ( ! empty($word)) {
                    $searchWords[] = $word;
                }
            }
        }
        
        for ( $i = 0; $i < count($searchWords); $i++) {
            $whereList[] = "message LIKE :message" . $i; 
            $placeholders[':message' . $i] = '%' . $searchWords[$i] . '%';     
        }
        $where .= ' AND ' . implode( ' OR ', $whereList);
    }
        
    $sql = sortQuery( $select . $from . $where, $sort);
    $members = Member::getAllMembers($sql, $placeholders);
    $total = count($members);
    $num_pages = ceil($total / $results_per_page);
       
    $sortLinks = generate_sort_links($userSearch, $sort);    
 
    // Query again to get just the subset of results
    $sql .= " LIMIT $skip, $results_per_page";
    $members = Member::getAllMembers($sql, $placeholders);
    

    // Search results message     
    if (sizeof($members) == 0) {
        $searchMsg = '<p class="error">Sorry, no members match your selection criteria:</p><p>';
    } 
    else if ( ! empty($_GET['firstName']) || ! empty($_GET['lastName']) || ! empty($_GET['message'])){
        $searchMsg = '<p>Your search results for:&nbsp;&nbsp;';
    }
    if ( ! empty($_GET['firstName'])) {
        $searchMsg .= '<strong>First Name:</strong>&nbsp;&nbsp;' . html($_GET['firstName']) . '&nbsp;&nbsp;&nbsp;';
    }
    if ( ! empty($_GET['lastName'])) {
        $searchMsg .= '<strong>Last Name:</strong>&nbsp;&nbsp;' . html($_GET['lastName']) . '&nbsp;&nbsp;&nbsp;';
    }
    if ( ! empty($_GET['message'])) {
        $searchMsg .= '<strong>Message Containing Text:</strong>&nbsp;&nbsp;' . html(implode(' ', $searchWords)) . '&nbsp;&nbsp;&nbsp;';
    }
    $searchMsg .= '</p><br />';

}
else {  //No search criteria
    $userSearch = ''; 
    $sql = sortQuery('SELECT * FROM signUp', $sort);
    
    //Member::getAllMembers() static function: includes/memberClass.inc.php
    $members = Member::getAllMembers($sql);
    
    $total = count($members);
    $num_pages = ceil($total / $results_per_page);    // Query again to get just the subset of results
    $sql .= " LIMIT $skip, $results_per_page";
    $members = Member::getAllMembers($sql);
}

// Generate sort links for member table headings
$sortLinks = generate_sort_links($userSearch, $sort);  
 
// Generate navigational page links if we have more than one page
if ($num_pages > 1) {
    $pageLinks = generate_page_links($userSearch, $sort, $cur_page, $num_pages);
}

include 'members.html.php';