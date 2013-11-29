<?php
/**
 * Extension configuration.
 *
 * This file is part of the MediaWiki extension 'SemanticMailMerge'.
 *
 * SemanticMailMerge is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SemanticMailMerge is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SemanticMailMerge.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @file
 */
$wgExtensionCredits['semantic'][] = array(
	'path' => __FILE__,
	'name' => 'SemanticMailMerge',
	'version' => '0.0.2',
	'author' => '[http://mediawiki.org/wiki/User:Samwilson Sam Wilson]',
	'url' => 'https://www.mediawiki.org/wiki/Extension:SemanticMailMerge',
	'descriptionmsg' => 'semanticmailmerge-desc'
);
$wgExtensionMessagesFiles['SemanticMailMerge'] = __DIR__ . '/SemanticMailMerge.i18n.php';
$wgAutoloadClasses['SemanticMailMerge_Sender'] = __DIR__ . '/includes/Sender.php';

/**
 * Result Format
 */
$wgAutoloadClasses['SemanticMailMerge_ResultFormat'] = __DIR__ . '/includes/ResultFormat.php';
$srfgFormats[] = 'mailmerge';
$smwgResultFormats['mailmerge'] = 'SemanticMailMerge_ResultFormat';

/**
 * Database table
 */
$wgAutoloadClasses['SemanticMailMerge_ORM'] = __DIR__ . '/includes/ORM.php';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'SemanticMailMerge_SchemaUpdates';
function SemanticMailMerge_SchemaUpdates(DatabaseUpdater $updater) {
	$sqldir = __DIR__.'/maintenance/';
	$updater->addExtensionTable('smw_mailmerge', $sqldir.'table.sql');
	$updater->addExtensionIndex('smw_mailmerge', 'title', $sqldir.'index.sql');
	return true;
}
