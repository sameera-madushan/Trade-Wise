<?php


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


/**
 * Send formatted json response (JSend)
 *
 * @param  String  $status
 * @param  String  $message
 * @param  array|null  $data
 * @param  Int  $code
 * @return JsonResponse
 */
function apiResponse(String $status = 'success'|'error', String $message = '', Array|object $data = []): JsonResponse
{
    $data_key = 'data';
    $code = 200;
    if ($status == 'error') {
        $data_key = 'errors';
        $code = 400;
    }
    return response()->json([
        'status' => $status,
        'message' => $message,
        $data_key => $data
    ], $code);
}

/**
 * Make array form comma separated string
 *
 * @param $string
 * @return array|false|string[]
 */
function makeArray($string)
{
    if (is_array($string)) return $string;
    return explode(',', trim($string, '[]')) ?? [];
}

/**
 * Add error to log
 *
 * @param  Exception  $e
 */
function addErrorToLog(\Exception $e)
{
    Log::error($e->getMessage()."\n".$e->getTraceAsString());
}

/**
 * Check isset and not empty given key on given array, then return if it found. else return default
 *
 * @param  string  $needle
 * @param  array  $haystack
 * @param  string  $default
 * @return mixed|string
 */
function in_data(string $needle, array $haystack, $default = '')
{
    if (isset($haystack[$needle]) && !empty($haystack[$needle])) {
        return $haystack[$needle];
    }
    return $default;
}

/**
 * Validate import file headers
 *
 * @param  string  $file
 * @param  array  $headings
 * @return stdClass
 */
function validateHeader(string $file, array $headings)
{
//    $validator = new stdClass();
//    $validator->isInvalid = false;
//    $input_headings = (new HeadingRowImport)->toArray(request()->file($file));
//    $diff = array_diff($input_headings[0][0], $headings);
//    if (count($diff) > 0) {
//        foreach ($diff as $key => $h){
//            $errors[] = $h.' header name is invalid';
//        }
//        $validator->isInvalid = true;
//        $validator->invalidCount = count($diff);
//        $validator->errors = $errors;
//    }
//    return $validator;
}

/**
 * Get user name by id
 *
 * @param $user_id
 * @return mixed|string
 */
function getUserName($user_id)
{
    if ($user_id == null) return '';
    $user = DB::table('users')->select('name')->where('id', $user_id)->first();
    if ($user){
        return $user->name;
    }
    return '';
}

/**
 * Get database table record summary to display on create and update pages
 *
 * @return array
 */
function getModelSummary($table = null)
{
    if ($table == null) return [];
    try {
        $last_rec = DB::table($table)->latest('created_at')->first();
        $last_change = DB::table($table)->latest('updated_at')->first();
        return [
            'last_record' => [
                'created_at' => isset($last_rec->created_at) ? $last_rec->created_at : null,
                'created_by' => isset($last_rec->created_by) ? getUserName($last_rec->created_by) : null,
            ],
            'last_change' => [
                'updated_at' => isset($last_change->updated_at) ? $last_change->updated_at : null,
                'updated_by' => isset($last_change->updated_by) ? getUserName($last_change->updated_by) : null,
            ],
            'total_records' => DB::table($table)->count('id'),
        ];
    } catch (\Exception $e) {
        return [];
    }
}