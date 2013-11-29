-- This file is part of the MediaWiki extension 'SemanticMailMerge'.
--
-- SemanticMailMerge is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- SemanticMailMerge is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with SemanticMailMerge.  If not, see <http://www.gnu.org/licenses/>.

CREATE TABLE IF NOT EXISTS /*_*/smw_mailmerge (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) /*$wgDBTableOptions*/;
