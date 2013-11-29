<?php
/**
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
/**
 * ORM class representing the 'smw_mailmerge' database table.
 */
class SemanticMailMerge_ORM extends ORMTable {

	public function __construct($tableName = '', array $fields = array(), array $defaults = array(), $rowClass = null, $fieldPrefix = '') {
		$tableName = 'smw_mailmerge';
		$fields = array(
			'id' => 'id',
			'title' => 'str',
			'template' => 'str',
			'params' => 'array',
		);
		parent::__construct($tableName, $fields, $defaults, $rowClass, $fieldPrefix);
	}

}
