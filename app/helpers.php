<?php

use App\Models\Attachment;
use App\Models\Industry;
use App\Models\Link;
use App\Models\Media;
use App\Models\Phone;
use App\Models\Strength;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('getIndustryNames')) {
    function getIndustryNames($commaSeparatedIds)
    {

        $ids = explode(',', $commaSeparatedIds);
        $industries = Industry::whereIn('uuid', $ids)->pluck('name')->toArray();

        return $industries;
    }
}

if (! function_exists('getMediaNames')) {
    function getMediaNames($commaSeparatedIds)
    {
        $ids = explode(',', $commaSeparatedIds);
        $medias = Media::whereIn('uuid', $ids)->pluck('name')->toArray();

        return $medias;
    }
}

if (! function_exists('getCharacterStrengthNames')) {
    function getCharacterStrengthNames($commaSeparatedIds)
    {
        $ids = explode(',', $commaSeparatedIds);
        $strengths = Strength::whereIn('uuid', $ids)->pluck('name')->toArray();

        return $strengths;
    }
}

if (! function_exists('getAttachmentBasePath')) {
    function getAttachmentBasePath()
    {
        return 'https://ad-agency-creatives.s3.amazonaws.com/';
    }
}

/**
 * This method is called from Admin Controllers
 */
if (! function_exists('storeImage')) {
    function storeImage($request, $user_id, $resource_type)
    {
        $uuid = Str::uuid();
        $file = $request->file;

        $extension = $file->getClientOriginalExtension();
        $folder = $resource_type.'/'.$uuid;
        $filePath = Storage::disk('s3')->put($folder, $file);

        $attachment = Attachment::create([
            'uuid' => $uuid,
            'user_id' => $user_id,
            'resource_type' => $resource_type,
            'path' => $filePath,
            'name' => $file->getClientOriginalName(),
            'extension' => $extension,
        ]);

        return $attachment;
    }
}

if (! function_exists('replacePlaceholders')) {
    function replacePlaceholders($format, $replacements)
    {
        return str_replace(array_keys($replacements), array_values($replacements), $format);
    }
}

if (! function_exists('processIndustryExperience')) {
    function processIndustryExperience(Request $request, &$filters, $experienceKey = 'industry_experience')
    {
        if (! isset($filters['filter'][$experienceKey])) {
            return null;
        }

        $experience_ids = $filters['filter'][$experienceKey];
        unset($filters['filter'][$experienceKey]);
        $request->replace($filters);

        $experience_ids = $experience_ids ? explode(',', $experience_ids) : [];

        return Industry::whereIn('uuid', $experience_ids)->pluck('uuid')->toArray();
    }
}

if (! function_exists('processMediaExperience')) {
    function processMediaExperience(Request $request, &$filters, $experienceKey = 'media_experience')
    {
        if (! isset($filters['filter'][$experienceKey])) {
            return null;
        }

        $experience_ids = $filters['filter'][$experienceKey];
        unset($filters['filter'][$experienceKey]);
        $request->replace($filters);

        $experience_ids = $experience_ids ? explode(',', $experience_ids) : [];

        return Media::whereIn('uuid', $experience_ids)->pluck('uuid')->toArray();
    }

}

if (! function_exists('applyExperienceFilter')) {
    function applyExperienceFilter($query, $experience, $experienceType, $tableName)
    {
        $query->whereIn('id', function ($query) use ($experience, $experienceType, $tableName) {
            $query->select('id')
                ->from($tableName)
                ->where(function ($q) use ($experience, $experienceType) {
                    foreach ($experience as $targetId) {
                        $q->orWhereRaw("FIND_IN_SET(?, $experienceType)", [$targetId]);
                    }
                });
        });
    }

}

if (! function_exists('updatePhone')) {
    function updatePhone($user, $phone_number, $label)
    {
        if ($phone_number == null || $phone_number == '') {
            return;
        }

        $country_code = '+1';

        if (strpos($phone_number, $country_code) === 0) {
            $phone_number = substr($phone_number, strlen($country_code));
            $phone_number = trim($phone_number);
        }

        $phone = Phone::where('user_id', $user->id)->where('label', $label)->first();
        if ($phone) {
            $phone->update(['country_code' => $country_code, 'phone_number' => $phone_number]);
        } else {

            Phone::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'label' => $label,
                'country_code' => $country_code,
                'phone_number' => $phone_number,
            ]);
        }
    }

}

if (! function_exists('updateLink')) {
    function updateLink($user, $url, $label)
    {
        if ($url == null || $url == '') {
            return;
        }

        $link = Link::where('user_id', $user->id)->where('label', $label)->first();
        if ($link) {
            $link->update(['url' => $url]);
        } else {

            Link::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'label' => $label,
                'url' => $url,
            ]);
        }
    }
}
