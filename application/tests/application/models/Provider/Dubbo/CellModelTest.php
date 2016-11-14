<?php

namespace models\Provider\Dubbo;

class CellModelTest extends \PHPUnit {
		
	public function testFindCellBaseInfoById() {
		$cellId=\phpUnitLib\helper\Cell::getCellIdFromSearch();
		$cell=\Provider\Dubbo\CellModel::findCellBaseInfoById($cellId);
		\PhpUnit::assertArrayHasKey('code', $cell);
		\PhpUnit::assertEquals('00000', $cell['code']);
	}
	
}