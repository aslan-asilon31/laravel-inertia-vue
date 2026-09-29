<?php

namespace App\Helpers\VideoUpload\Traits;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait WithVideoUpload
{
  protected function saveVideo($folderName = 'files/videos', $newVideoUrl, $newVideoName, $oldVideoUrl)
  {
    if ($newVideoUrl instanceof TemporaryUploadedFile) {
      $extension = $newVideoUrl->extension();
      $newFullVideoName  = $newVideoName . '_' . str(now())->slug()->toString() . ".$extension";
      $newVideoUrl = '/' . $newVideoUrl->storeAs($folderName, $newFullVideoName, 'public');
      if ($oldVideoUrl && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldVideoUrl)) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldVideoUrl);
      }
    }

    return $newVideoUrl;
  }

  public function removeVideoUrl()
  {
    $this->masterForm->video_url = null;
  }

  public function deleteVideo(string|null $videoUrl = null)
  {
    if ($videoUrl && \Illuminate\Support\Facades\Storage::disk('public')->exists($videoUrl)) {
      \Illuminate\Support\Facades\Storage::disk('public')->delete($videoUrl);
    }
  }
}
