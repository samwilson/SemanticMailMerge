<?php
/**
 * CLI script for sending mail, to be called at the required schedule and passed
 * a single '--title' or '-t' argument specifying the page containing a
 * mailmerge format query.
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
require_once __DIR__ . '/../../../maintenance/Maintenance.php';
require_once __DIR__ . '/../includes/Sender.php';
$maintClass = "SemanticMailMerge_Sender";
require_once( RUN_MAINTENANCE_IF_MAIN );
