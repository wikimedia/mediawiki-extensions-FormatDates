<?php

/**
 * Parser hook in which free dates will be refactored to meet the
 * user's date formatting preference
 *
 * @file
 * @ingroup Extensions
 * @author Rob Church <robchur@gmail.com>
 * Please see the LICENSE file for terms of use and redistribution
 */
 
if( !defined( 'MEDIAWIKI' ) ) {
	echo( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" );
	exit( 1 );
}

$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'Date Formatter',
	'author' => 'Rob Church',
	'descriptionmsg' => 'formatdates-desc',
);

$wgMessagesDirs['DateFormatter'] = __DIR__ . '/i18n';

$wgAutoloadClasses['DateParser'] = __DIR__ . '/DateParser.php';
$wgAutoloadClasses['FormattableDate'] = __DIR__ . '/FormattableDate.php';
$wgHooks['ParserFirstCallInit'][] = 'efFormatDatesSetHook';

function efFormatDatesSetHook( $parser ) {
	$parser->setHook( 'date', 'efFormatDate' );
	return true;
}

function efFormatDate( $text, $args, &$parser ) {
	global $wgUseDynamicDates;
	if( $wgUseDynamicDates ) {
		$dp = new DateParser(
			\MediaWiki\MediaWikiServices::getInstance()->getContentLanguage(),
			DateParser::convertPref( $parser->getOptions()->getDateFormat() )
		);
		return $dp->reformat( $text );
	} else {
		return $text;
	}
}
