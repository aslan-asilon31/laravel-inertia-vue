<?php

namespace App\Helpers\ImageUpload\Traits;

trait WithImageUpload
{
  protected function saveImage($folderName = 'files/images', $imageName = null, $newImageUrl = null, $oldImageUrl = null)
  {
    if ($newImageUrl instanceof \Illuminate\Http\UploadedFile) {
      $ext = $newImageUrl->getClientOriginalExtension();
      $imageName = $imageName . '.' . $ext;

      $destinationPath = public_path($folderName);
      if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
      }

      $newImageUrl->move($destinationPath, $imageName);

      return '/' . trim($folderName, '/') . '/' . $imageName;
    }

    return $newImageUrl;
  }


  protected function deleteImage($imageUrl = null, $disk = 'public')
  {
    if ($imageUrl && \Illuminate\Support\Facades\Storage::disk($disk)->exists($imageUrl)) {
      \Illuminate\Support\Facades\Storage::disk($disk)->delete($imageUrl);
    }
  }
}
