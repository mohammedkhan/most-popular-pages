<?php
//This is based largely on Google's HelloAnalyticsApi example, hence the lack of WordPress coding standards (sorry!)
require_once 'google-api-php-client/src/Google/Client.php';
require_once 'google-api-php-client/src/Google/Service.php';
session_start();
$client = new Google_Client();
$client->setApplicationName('Most Popular Pages');
//Retrieve these parmeters from WordPress db:
$client->setClientId(get_option('client_id'));
$client->setClientSecret(get_option('client_secret'));
$client->setRedirectUri(get_option('client_redirect_uri'));
$client->setDeveloperKey(get_option('developer_key'));
$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
$client->setUseObjects(true);
$client->authenticate();
$_SESSION['token'] = $client->getAccessToken();
$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));

if (isset($_SESSION['token'])) {
	$client->setAccessToken($_SESSION['token']);
}

if (!$client->getAccessToken()) {
	print 'There was a problem with the authentication process. Please check your settings.'
} else {
	$analytics = new apiAnalyticsService($client);
	run($analytics);
}

function run(&$analytics) {
	try {
		$profileId = getFirstProfileId($analytics);
		if (isset($profileId)) {
			$results = getResults($analytics, $profileId);
			if ($results->getRows() > 0) {
				print($results)
			} else {
				print 'No results returned';
			}
		}
	} catch (apiServiceException $e) {
		// Error from the API.
		print 'There was an API error : ' . $e->getCode() . ' : ' . $e->getMessage();
	} catch (Exception $e) {
		print 'There was a general error : ' . $e->getMessage();
	}
}

function getFirstprofileId(&$analytics) {
	$accounts = $analytics->management_accounts->listManagementAccounts();

	if (count($accounts->getItems()) > 0) {
		$items = $accounts->getItems();
		$firstAccountId = $items[0]->getId();

		$webproperties = $analytics->management_webproperties->listManagementWebproperties($firstAccountId);

		if (count($webproperties->getItems()) > 0) {
			$items = $webproperties->getItems();
			$firstWebpropertyId = $items[0]->getId();
			$profiles = $analytics->management_profiles->listManagementProfiles($firstAccountId, $firstWebpropertyId);

			if (count($profiles->getItems()) > 0) {
				$items = $profiles->getItems();
				return $items[0]->getId();
			} else {
				throw new Exception('No views (profiles) found for this user.');
			}
		} else {
			throw new Exception('No webproperties found for this user.');
		}
	} else {
		throw new Exception('No accounts found for this user.');
	}
}

function getResults(&$analytics, &$profileId) {
	$todaysDate = getTodaysDate();
	return $analytics->data_ga->get(
		'ga:' . $profileId,
		$todaysDate,
		date_sub($todaysDate, date_interval_create_from_date_string("30 days")),
		'ga:pageviews,ga:hostname,ga:pagePath,ga:pageTitle',
		'ga:pagePath',
		'-ga:pageviews',
		'10');
	}
}

function printResults(&$results) {
	$page .= '<h2>The Most Popular Pages from the last 30 days</h2>';
	$page .= '<ol>';

	foreach ($results->getRows() as $row) {
		$page .= "<li><a href='$row(1)$row(2)'>$row(3)</a></li>";
	}
	$page .= '</ol>';
	print $page;
}

// Returns today's date in the format yyyy-MM-dd
function getTodaysDate() {
	return date_format(getDate(), 'Y-m-d');
}

?>
