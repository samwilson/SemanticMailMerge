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
 * Result Format printer. Output is exactly the same as its parent.
 */
class SemanticMailMerge_ResultFormat extends SMWTableResultPrinter {

	/** @var ORMTable */
	protected $emailsTable;

	/** @var string */
	protected $pageTitle;

	/**
	 * @see SMWResultPrinter::getParamDefinitions
	 *
	 * @since 1.8
	 *
	 * @param $definitions array of IParamDefinition
	 *
	 * @return array of IParamDefinition|array
	 */
	public function getParamDefinitions(array $definitions) {
		$params = parent::getParamDefinitions($definitions);
		$def = new StringParam('template');
		$params['template'] = $def;
		return $params;
	}

	/**
	 * Get HTML output (exactly the same as for the 'table' result format) and
	 * prepare mail merge data. Perhaps storing the latter in the DB should be
	 * done elsewhere.
	 *
	 * @uses SMWTableResultPrinter::getResultText()
	 * @return string
	 */
	protected function getResultText(\SMWQueryResult $queryResult, $outputmode) {
		global $wgTitle;
		$this->pageTitle = "$wgTitle";

		$this->emailsTable = new SemanticMailMerge_ORM();
		$this->emailsTable->delete(array('title' => $this->pageTitle));

		$results = clone $queryResult;
		while ($row = $results->getNext()) {
			$this->handleRow($row);
		}

		return parent::getResultText($queryResult, $outputmode);
	}

	protected function handleRow($row) {

		$template_params = array();
		$field_num = 0;
		foreach ($row as $field) {
			$field_num++;
			$key = $field->getPrintRequest()->getLabel();
			if (empty($key)) {
				$key = $field_num;
			}
			$value = array();
			while (($object = $field->getNextDataValue()) !== false) {
				$value[] = Sanitizer::decodeCharReferences($object->getWikiValue());
			}
			$template_params[$key] = $value;
		}

		if (isset($template_params['To']) && count($template_params['To'])>0) {
			$data = array(
				'title' => $this->pageTitle,
				'params' => $template_params,
				'template' => $this->params['template'],
			);
			$row = $this->emailsTable->newRow($data);
			$row->save();
		}

	}

}
