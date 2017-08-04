<?php

namespace App\Http\Controllers\MyAccount;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ZipArchive;

class CustomDomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('users.customdomain');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($sub_domain, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        if ($request->has('flag')) {
            if ($request->input('flag') == 'download') {
                $domain_type = $request->input('custom_domain_type');
                $userinfo = auth()->user();
                $u_name = $userinfo->domain;
                $re_file_name = public_path() . '/custom-doamin/' . $domain_type . '/index.php';
                $newfile_name = public_path() . '/custom-doamin/' . $domain_type . '/index_' . $u_name . '.php';
                copy($re_file_name, $newfile_name);

                $file = fopen($newfile_name, "a+");
                $data = fread($file, filesize($newfile_name));
                fclose($file);
                $newdata = str_replace('VAR_USER_NAME', $u_name, $data);
                file_put_contents($newfile_name, "");
                $file = fopen($newfile_name, "a+");
                fwrite($file, $newdata);
                fclose($file);

                $fileNames = array('index.php' => $newfile_name, 'htaccess'  => public_path() . '/custom-doamin/' . $domain_type . '/htaccess');
                $zipName = $u_name . '.zip';
                $zipPath = public_path() . '/zip-files/' . $domain_type . '/';

                $zip = new ZipArchive();
                if ($zip->open($zipPath . $zipName, ZIPARCHIVE::CREATE) !== TRUE) {
                    echo "Cannot Open";
                }

                foreach ($fileNames as $key => $files) {
                    $zip->addFile($files, $key);
                }
                $zip->close();

                unlink($newfile_name);

                header("Content-type: application/zip");
                header("Content-Disposition: attachment; filename=" . $zipName . "");
                header("Expires: 0");
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header("Content-length: " . filesize($zipPath . $zipName));
                readfile($zipPath . $zipName);
                unlink($zipPath . $zipName);
                exit;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
