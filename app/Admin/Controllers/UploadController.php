<?php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use CherryneChou\Admin\Traits\HasUploadedFile;
use CherryneChou\Admin\Traits\RestfulResponse;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    use RestfulResponse,HasUploadedFile;

    public function handle()
    {
        try {
            $disk = $this->disk('admin');

            $image = request()->input('upload_data');

            $image = str_replace('data:image/png;base64,', '', $image);

            $image = str_replace(' ', '+', $image);

            $imagePath = date('Y_m_d') . '/' . Str::random (10) . '.png';

            $disk->put($imagePath, base64_decode($image));

            $data = [
                'remoteName'   =>  $disk->url($imagePath),
                'name'              =>  $imagePath,
            ];

            return $this->success($data,trans('admin.upload_succeeded'));

        }catch (\Exception $exception){
            return $this->failed($exception->getMessage());
        }
    }
}
