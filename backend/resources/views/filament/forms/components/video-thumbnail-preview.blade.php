{{--
    Receives from VideoResource.php's form() method:
    - $youtubeId: ?string (The current value of the 'youtube_video_id' form field, passed via ->viewData())
--}}
@props(['youtubeId'])

<div>
    @if ($youtubeId && is_string($youtubeId) && strlen(trim($youtubeId)) > 0)
        @php
            $thumbnailUrl = "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
        @endphp
        <img src="{{ $thumbnailUrl }}" alt="YouTube Thumbnail Preview" class="max-w-xs h-auto rounded shadow mt-2 object-contain">
    @else
        {{-- This part will likely not show because the entire View component is hidden by Filament if youtubeId is empty --}}
        {{-- <p class="text-sm text-gray-500 mt-2">Enter a YouTube Video ID to see a preview.</p> --}}
    @endif
</div>