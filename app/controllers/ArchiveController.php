<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArchiveController extends BaseController {

	/**
	 * Display a list batch outputs page
	 *
	 * @return View
	 */
	
	public function openResumeListPage() {

		$batchoutputs = DB::table('histories')
					->where('histories.synced', '=', 1)
					->where('histories.reference_file', '<>', "")
					->where('histories.reference_file', 'NOT LIKE', "%error%")
					// ->where('histories.category', '<>', 'completion_error')
					// ->where('histories.category', '<>', 'transfer_error')
					->select(
						'histories.category',
						'histories.reference_file',
						'histories.synced',
						'histories.updated_at'
					)
					->orderBy('histories.updated_at', 'desc')
					->groupBy(
						'histories.reference_file'
    				)
                    ->get();
		return View::make('archive.list-resume', array(
			'page' => 'archive_resume',
			'batchoutputs' => $batchoutputs
		));
	}

	public function downloadResume($reference_file) {
		if (file_exists(public_path() . "/archived/" . $reference_file)) {
			header("Content-Length: " . filesize(public_path() . "/archived/" . $reference_file));
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $reference_file);
			readfile(public_path() . "/archived/" . $reference_file);
			exit();
		}
		else {
			return View::make('404');
		}
	}

	/**
	 * Display a detail category page
	 * 
	 * @param   $id identity of category
	 *
	 * @return View
	 */

	public function openResumeDetailPage($reference_file) {
		try {
			// $batchOutput = BatchOutput::findOrFail($id);
			if ($reference_file)
			if (strpos($reference_file, 'ympigm') !== false) {
				$batchoutputs = DB::table('histories')
							->join('materials', 'histories.transfer_material_id', '=', 'materials.id')
							->where('histories.synced', '=', 1)
							->where('histories.reference_file', '=', $reference_file)
							->select(
								'histories.category',
								'histories.transfer_barcode_number', 
								'histories.transfer_document_number', 
								'histories.transfer_issue_location', 
								'histories.transfer_issue_plant', 
								'histories.transfer_receive_location', 
								'histories.transfer_receive_plant', 
								'histories.transfer_cost_center',
								'histories.transfer_gl_account',
								'histories.transfer_transaction_code',
								'histories.transfer_movement_type',
								'histories.transfer_reason_code',
								'histories.error_description',
								'materials.material_number',
								'histories.transfer_material_id',
								'histories.lot as lot', 
								'histories.synced', 
								'histories.reference_file',
								'histories.user_id', 
								'histories.deleted_at', 
								'histories.created_at', 
								'histories.updated_at'
							)
							// ->groupBy(
							// 	'histories.reference_file'
							// )
				            ->having('histories.lot', '>', 0)
				            ->get();

				return View::make('archive.detail-resume', array(
					'page' => 'archive_resume',
					'type' => 'transfer',
					'batchoutputs' => $batchoutputs
				));
			}
			else {
				$batchoutputs = DB::table('histories')
							->join('materials', 'histories.completion_material_id', '=', 'materials.id')
							->where('histories.synced', '=', 1)
							->where('histories.reference_file', '=', $reference_file)
							->select(
								'histories.category',
								'histories.completion_barcode_number', 
								'histories.completion_description', 
								'histories.completion_location', 
								'histories.completion_issue_plant', 
								'histories.completion_material_id',
								'histories.completion_reference_number',
								'materials.material_number',
								DB::raw('SUM(histories.lot) as lot'), 
								'histories.synced', 
								'histories.reference_file',
								'histories.user_id', 
								'histories.deleted_at', 
								'histories.created_at', 
								'histories.updated_at'
							)
							->groupBy(
								'histories.completion_material_id',
		    					'histories.completion_location',
		    					'histories.completion_issue_plant'
		    				)
		                    ->having(DB::raw('SUM(histories.lot)'), '>', 0)
		                    ->get();

				return View::make('archive.detail-resume', array(
					'page' => 'archive_resume',
					'type' => 'completion',
					'batchoutputs' => $batchoutputs
				));

			}
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Display a list batch outputs page
	 *
	 * @return View
	 */
	
	public function openErrorListPage() {

		$histories = DB::table('histories')
					->where('histories.synced', '=', 0)
					->where('histories.lot', '>', 0)
					->whereNotNull('histories.reference_file')
					->whereNotNull('histories.error_description')
					->whereNull('histories.deleted_at')
					//->where('updated_at','>=',date('y-m-d'))
					->select(
						'histories.category',
						'histories.reference_file',
						'histories.synced',
						'histories.created_at'
					)
					->orderBy('histories.created_at', 'desc')
					->groupBy(
						'histories.reference_file'
    				)
                    ->get();
		return View::make('archive.list-error', array(
			'page' => 'archive_error',
			'histories' => $histories
		));

	}

	public function openErrorDetailPage($filename) {
		if (strpos($filename, 'gm') !== false) {
		    // transfer
			$histories = DB::table('histories')
						->where('histories.reference_file', '=', $filename)
						->where('histories.lot', '>', 0)
						->where('histories.synced', '=', 0)
						->whereNull('histories.deleted_at')
						->leftJoin('materials', 'histories.transfer_material_id', '=', 'materials.id')
						->select(
							'histories.id',
							'histories.category',
							'histories.transfer_barcode_number',
							'histories.transfer_document_number',
							'materials.material_number',
							'histories.transfer_document_number',
							'histories.transfer_issue_location',
							'histories.transfer_issue_plant',
							'histories.transfer_receive_location',
							'histories.transfer_receive_plant',
							'histories.transfer_cost_center',
							'histories.transfer_gl_account',
							'histories.transfer_transaction_code',
							'histories.transfer_movement_type',
							'histories.transfer_reason_code',
							'histories.reference_file',
							'histories.lot',
							'histories.synced',
							'histories.error_description',
							'histories.created_at'
						)
						->orderBy('histories.updated_at', 'desc')
	                    ->get();
			return View::make('archive.detail-error', array(
				'page' => 'archive_error',
				'filename' => $filename,
				'category' => 'transfer_error',
				'histories' => $histories
			));
		}
		else {
			// completion
			$histories = DB::table('histories')
						->where('histories.reference_file', '=', $filename)
						->where('histories.lot', '>', 0)
						->where('histories.synced', '=', 0)
						->whereNull('histories.deleted_at')
						->leftJoin('materials', 'histories.completion_material_id', '=', 'materials.id')
						->select(
							'histories.id',
							'histories.category',
							'histories.completion_barcode_number',
							'histories.completion_description',
							'histories.completion_location',
							'histories.completion_issue_plant',
							'materials.material_number',
							'histories.completion_reference_number',
							'histories.reference_file',
							'histories.lot',
							'histories.synced',
							'histories.error_description',
							'histories.created_at'
						)
						->orderBy('histories.updated_at', 'desc')
	                    ->get();
			return View::make('archive.detail-error', array(
				'page' => 'archive_error',
				'filename' => $filename,
				'category' => 'completion_error',
				'histories' => $histories
			));
		}
	}

	public function deleteError($reference_file) {

		$histories = History::where('histories.reference_file', '=', $reference_file)->delete();
		// $histories = DB::table('histories')
		// 			->where('histories.reference_file', '=', $reference_file)
  //                   ->delete();

		return Redirect::route('listarchiveerror');
	}
}