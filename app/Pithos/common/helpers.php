<?php

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

function getStatusCode($type)
{

    foreach (\App\Pithos\common\Constant::statusCodes as $key => $statusCode) {
        if ($type == $key) {
            return [
                'code'    => $statusCode['code'],
                'message' => $statusCode['message']
            ];
        }
    }
}

function writeAttachData($file, $post_id, $specific_path, $type = "image")
{
    $file_name = $file->getClientOriginalName();
    $file_extension = $file->getClientOriginalExtension();
    $file_size = $file->getClientSize();
    $file_type = $file->getClientMimeType();

    $acceptable = [];
    switch ($type) {
        case "image" :
            $acceptable = array('image/jpeg', 'image/gif', 'image/png');
            break;
        case "video" :
            $acceptable = array("video/mp4");
            break;
        case "other" :
            $acceptable = array('text/csv', 'text/plain', 'application/vnd.ms-excel');
    }
    if (sizeof($acceptable) > 0) {

        //check file type;
        if (in_array($file_type, $acceptable) <= 0) {
            return 1;
        } // check file size;
        elseif ($file_size > 2000000) {
            return 2;
        }

        $base_path = public_path() . $specific_path;
        $today = date('d-m-y');
        if (!file_exists($base_path . '/'. $today)) {
            mkdir($base_path . '/' . $today, 0777, true);
        }
        $file_path = $base_path . '/' . $today;
        $file_name_ary = explode('.', $file_name);
        $file_name_value = $file_name_ary[0] . '_' . $post_id . '.' . $file_extension;

        if ($type == 'image') {
            Image::make($file->getRealPath())->resize(640, 480, function ($constraint) {
                $constraint->aspectRatio();
            })->save($file_path . '/' . $file_name_value);
            $attach_url = $specific_path .'/'. $today . '/' . $file_name_value;

        } else {
            $attach_url = $specific_path . $today . '/' . $file_name_value;
            $file->move($base_path . '/' . $today, $file_name_value);
        }

        return $attach_url;
    }
}

function UploadFile($upload, $acceptable, $uploadPath)
{
    $filename = $upload->getClientOriginalName();
    $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
    $base_filename = pathinfo($filename, PATHINFO_FILENAME);
    $file_type = $upload->getClientMimeType();

    if (in_array($file_type, $acceptable) <= 0) {
        return 1;
    }
    $base_path = public_path() . $uploadPath;
    $today = date('d-m-y');

    //rename file to avoid duplicate uploading
    $date_time = (new DateTime())->getTimestamp();
    $filename = $base_filename . "_" . $date_time . '.' . $file_extension;

    if (!file_exists($base_path . $today)) {
        mkdir($base_path . '/' . $today, 0777, true);
    }
    $upload_path = $base_path . $today . '/' . $filename;

    $upload->move($base_path . '/' . $today, $filename);
    return $upload_path;

}

function DeleteFile($url)
{
    if (file_exists(public_path() . $url)) {
        File::delete(public_path() . $url);
    }
}

function inputSanitizer(array $input)
{
    $output = [];
    foreach ($input as $key => $item) {
        $item = html_entity_decode($item);
        $output[$key] = strip_tags(trim(stripslashes($item)));
    }
    return $output;
}


function base64FromStr($base64_string)
{
    $data = explode(";", $base64_string);
    $data1 = $data[1];
    if (base64_decode($data1) == false) {
        return false;
    } else {
        return true;
    }
}
function base64_to_jpeg($base64_string, $output_file)
{
    // open the output file for writing
    $ifp = fopen($output_file, 'wb');

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode(',', $base64_string);

    // we could add validation here with ensuring count( $data ) > 1
    if (sizeof($data) > 1) {
        fwrite($ifp, base64_decode($data[1]));

        // clean up the file resource
        fclose($ifp);

        return $output_file;
    }
    return false;
}



?>