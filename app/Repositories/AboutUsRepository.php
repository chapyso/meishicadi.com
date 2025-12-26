<?php

namespace App\Repositories;

use App\Models\AboutUs;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class UserRepository
 */
class AboutUsRepository extends BaseRepository
{
    public function model(): string
    {
        return AboutUs::class;
    }

    public function getFieldsSearchable()
    {
        //
    }

    /**
     * @param $input
     * @param $UserId
     * @return mixed
     */
    public function store($inputs)
    {
        try {
            DB::beginTransaction();

            foreach ($inputs['title'] as $id => $input) {
                $aboutUs = AboutUs::whereId($id)->first();
                
                if (!$aboutUs) {
                    continue;
                }
                
                $aboutUs->update([
                    'title' => $input,
                    'description' => $inputs['description'][$id],
                ]);

                // Handle image upload or removal
                if (isset($inputs['image']) && isset($inputs['image'][$id])) {
                    if ($inputs['image'][$id] instanceof \Illuminate\Http\UploadedFile) {
                        // New image uploaded
                        $aboutUs->clearMediaCollection(AboutUs::PATH);
                        $aboutUs->addMedia($inputs['image'][$id])->toMediaCollection(AboutUs::PATH,
                            config('app.media_disc'));
                    }
                }
                
                // Handle image removal (if a special flag is sent)
                if (isset($inputs['remove_image']) && in_array($id, $inputs['remove_image'])) {
                    $aboutUs->clearMediaCollection(AboutUs::PATH);
                }
            }

            DB::commit();

            return $aboutUs;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('AboutUs update error: ' . $e->getMessage());
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function fileUpload($aboutUs, $file)
    {
        $aboutUs->clearMediaCollection(AboutUs::PATH);
        $media = $aboutUs->addMedia($file)->toMediaCollection(AboutUs::PATH, config('app.media_disc'));
        $aboutUs->update(['value' => $media->getFullUrl()]);
    }
}
