<?php
    require "config.php";
    require "lib_db.php"; // MySQL DB helpers
    require "lib_message.php"; // Slack message construction helpers
	require "lib_slack.php"; // Slack helpers
	require "lib_curl.php"; // Wrappers for API calls via cURL
	require "lib_app_installs.php"; // Helpers for the app_installs db
    require "lib_trains.php"; // Train station
    require "lib_train_actions.php"; // Helpers for updating Lunch Train messages
    require "lib_time_parser.php"; // Time is a human construct

    ini_set('log_errors', 1);
    ini_set('error_log', '/var/log/apache2/error.log');

    // Break the request URI up for parsing
	$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);


    // OAuth install URL
    $auth_scopes = Array();
    $GLOBALS['oauth_url'] = $GLOBALS['base_auth_url']."?scope=".join(",", $auth_scopes)."&client_id=".$GLOBALS['client_id'];

    // LunchTrain onboarding DM content
    $GLOBALS['install_message'] = array(
        'text' => ":steam_locomotive:-:hamburger:-:fries:-:stew:-:curry:-:ramen:\n*You've installed LunchTrain!*\nThere's one more step and we're ready to get rolling:",
        'parse' => 'full',
        'attachments' => array(
            array(
                "mrkdwn_in" => Array('text'),
                'fallback' => '...',
                'text' => "Add the `/LunchTrain` command and let Lunch Train post to your team's channels",
                'callback_id' => 'add_scope',
                'actions' => array(
                    array(
                        'name' => 'add_scopes',
                        'text' => "Let's do this :sparkles:",
                        'type' => 'button',
                        'value' => 'add_scopes'
                    )
                )
            )
        )
    );
