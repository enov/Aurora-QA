<?php

/**
 * This is unrelated to the demo calendar
 *
 * The purpose of this model/aurora/collection
 * is to throw Exceptions and test how Aurora modules behaves
 * under these circumstances.
 *
 */
class Model_Exceptionist
{

	public $id;
	public $label;
}

class Aurora_Exceptionist implements Interface_Aurora_Database, Interface_Aurora_Hook_Before_Load, Interface_Aurora_Hook_Before_Save, Interface_Aurora_Hook_Before_Delete, Interface_Aurora_JSON_Serialize, Interface_Aurora_JSON_Deserialize
{
	public function db_persist($model) {
		return array(
			'id' => $model->$id,
			'label' => $model->$label,
		);
	}
	public function db_retrieve($model, array $row) {
		$tbl = Au::db()->table($this);
		$model->id = $row[$tbl . '.id'];
		$model->label = $row[$tbl . '.label'];
	}
	public function before_load(&$params) {
		throw new Kohana_Exception("Testing Exception");
	}
	public function before_save($model_or_collection) {
		throw new Kohana_Exception("Testing Exception");
	}
	public function before_delete($model_or_collection) {
		throw new Kohana_Exception("Testing Exception");
	}
	public function json_deserialize($json) {
		throw new Kohana_Exception("Testing Exception");
	}
	public function json_serialize($object) {
		throw new Kohana_Exception("Testing Exception");
	}
}

class Collection_Exceptionist extends Collection
{

}