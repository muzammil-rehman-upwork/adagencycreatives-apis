<?php

use App\Events\SendNotification;
use App\Http\Resources\Subscription\SubscriptionResource;
use App\Models\Address;
use App\Models\Application;
use App\Models\Attachment;
use App\Models\Industry;
use App\Models\Job;
use App\Models\Link;
use App\Models\Location;
use App\Models\Media;
use App\Models\Notification;
use App\Models\Phone;
use App\Models\Strength;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


if (! function_exists('getEmploymentTypes')) {
    function getEmploymentTypes($commaSeparatedNames)
    {
        return explode(',', $commaSeparatedNames);
    }
}


if (!function_exists('getIndustryNames')) {
    function getIndustryNames($commaSeparatedIds)
    {

        $ids = explode(',', $commaSeparatedIds);
        $industries = Industry::whereIn('uuid', $ids)->pluck('name')->toArray();

        return $industries;
    }
}

if (!function_exists('getMediaNames')) {
    function getMediaNames($commaSeparatedIds)
    {
        $ids = explode(',', $commaSeparatedIds);
        $medias = Media::whereIn('uuid', $ids)->pluck('name')->toArray();

        return $medias;
    }
}

if (!function_exists('getCharacterStrengthNames')) {
    function getCharacterStrengthNames($commaSeparatedIds)
    {
        $ids = explode(',', $commaSeparatedIds);
        $strengths = Strength::whereIn('uuid', $ids)->pluck('name')->toArray();

        return $strengths;
    }
}

if (!function_exists('getAttachmentBasePath')) {
    function getAttachmentBasePath()
    {
        $awsBucket = env('AWS_BUCKET');
        return "https://{$awsBucket}.s3.amazonaws.com/";
    }
}

/**
 * This method is called from Admin Controllers
 */
if (!function_exists('storeImage')) {
    function storeImage($request, $user_id, $resource_type)
    {
        $uuid = Str::uuid();
        $file = $request->file;
        if (is_array($file) && count($file) > 0) {
            $files = $file;
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $folder = $resource_type . '/' . $uuid;
                $filePath = Storage::disk('s3')->put($folder, $file);
                $attachments[] = Attachment::create([
                    'uuid' => $uuid,
                    'user_id' => $user_id,
                    'resource_type' => $resource_type,
                    'path' => $filePath,
                    'name' => $file->getClientOriginalName(),
                    'extension' => $extension,
                ]);
            }
            return $attachments;
        } else {
            $extension = $file->getClientOriginalExtension();
            $folder = $resource_type . '/' . $uuid;
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
}

if (!function_exists('replacePlaceholders')) {
    function replacePlaceholders($format, $replacements)
    {
        return str_replace(array_keys($replacements), array_values($replacements), $format);
    }
}

if (!function_exists('processIndustryExperience')) {
    function processIndustryExperience(Request $request, &$filters, $experienceKey = 'industry_experience')
    {
        if (!isset($filters['filter'][$experienceKey])) {
            return null;
        }

        $experience_ids = $filters['filter'][$experienceKey];
        unset($filters['filter'][$experienceKey]);
        $request->replace($filters);

        $experience_ids = $experience_ids ? explode(',', $experience_ids) : [];

        return Industry::whereIn('uuid', $experience_ids)->pluck('uuid')->toArray();
    }
}

if (!function_exists('processMediaExperience')) {
    function processMediaExperience(Request $request, &$filters, $experienceKey = 'media_experience')
    {
        if (!isset($filters['filter'][$experienceKey])) {
            return null;
        }

        $experience_ids = $filters['filter'][$experienceKey];
        unset($filters['filter'][$experienceKey]);
        $request->replace($filters);

        $experience_ids = $experience_ids ? explode(',', $experience_ids) : [];

        return Media::whereIn('uuid', $experience_ids)->pluck('uuid')->toArray();
    }
}

if (!function_exists('applyExperienceFilter')) {
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

if (!function_exists('updatePhone')) {
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

if (!function_exists('updateLink')) {
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

function url_exists($url) {
    $headers = @get_headers($url);
	if( strpos( $headers[0], '200' ) === false ) return false;
	return true;
}

function formate_url($url) {
    $find = ['https://', 'http://', 'www.'];
    $replace   = ['', '', ''];

    $formatted_url = str_replace($find, $replace, $url);
    if (url_exists('https://'. $formatted_url)) {
    	return 'https://'. $formatted_url;
    } else if (url_exists('http://'. $formatted_url)) {
    	return 'http://'. $formatted_url;
    } else if (url_exists('https://www.'. $formatted_url)) {
    	return 'https://www.'. $formatted_url;
    } else if (url_exists('http://www.'. $formatted_url)) {
    	return 'http://www.'. $formatted_url;
    }

    return $url;
}

if (!function_exists('updateLocation')) {
    function updateLocation($request, $user, $label)
    {
        $state = Location::where('uuid', $request->state_id)->first();
        $city = Location::where('uuid', $request->city_id)->first();

        if ($state && $city) {
            $address = $user->addresses->first();
            if (!$address) {
                $address = new Address();
                $address->uuid = Str::uuid();
                $address->user_id = $user->id;
                $address->label = $label;
                $address->country_id = 1;
            }
            // dump($state, $city);
            $address->state_id = $state->id;
            $address->city_id = $city->id;
            $address->save();
        }
    }
}

if (!function_exists('get_user_slug')) {
    function get_user_slug($user)
    {
        // dd($user->role);
        $slug = null;
        if ($user->role == 'creative') {
            $slug = $user->creative ? $user->creative->slug : $user->username;
        } elseif ($user->role == 'agency' || $user->role == 'advisor') {
            $slug = $user->agency ? $user->agency->slug : $user->username;
        } elseif ($user->role == 'admin') {
            $slug = $user->username;
        }

        return $slug;
    }
}

if (!function_exists('create_notification')) {
    function create_notification($user_id, $msg, $type = 'job_board', $body = [])
    {
        $notification = Notification::create([
            'uuid' => Str::uuid(),
            'user_id' => $user_id,
            'type' => $type,
            'body' => $body,
            'message' => $msg,
        ]);

        $event_data = [
            'receiver_id' => '697c1e7d-015a-3ff1-9a6e-9d3c4c6454c3',
            'body' => $msg,
        ];
        // event(new SendNotification($event_data));

        return $notification;
    }
}

if (!function_exists('get_profile_picture')) {
    function get_profile_picture($user)
    {
        $defaultImage = asset('assets/img/placeholder.png');
        $attachmentBasePath = getAttachmentBasePath();
        if (in_array($user->role, ['admin', 'creative']) && $user->profile_picture) {
            return $attachmentBasePath . $user->profile_picture->path;
        } elseif (in_array($user->role, ['agency', 'advisor', 'recruiter']) && $user->agency_logo) {
            return $attachmentBasePath . $user->agency_logo->path;
        }

        return $defaultImage;
    }
}
if (!function_exists('get_profile_picture_id')) {
    function get_profile_picture_id($user)
    {
        $defaultImage = 0;
        if (in_array($user->role, ['admin', 'creative']) && $user->profile_picture) {
            return $user->profile_picture->id;
        } elseif (in_array($user->role, ['agency', 'advisor', 'recruiter']) && $user->agency_logo) {
            return $user->agency_logo->id;
        }

        return $defaultImage;
    }
}

if (!function_exists('get_resume')) {
    function get_resume($user)
    {
        if (isset($user->resume)) {
            return getAttachmentBasePath() . $user->resume->path;
        } else {
            return route('download.resume', $user->id);
        }
    }
}

if (!function_exists('get_location')) {
    function get_location($user)
    {
        $address = $user->addresses ? collect($user->addresses)->firstWhere('label', 'personal') : null;

        if ($address) {
            return [
                'state_id' => $address->state ? $address->state->uuid : null,
                'state' => $address->state ? $address->state->name : null,
                'city_id' => $address->city ? $address->city->uuid : null,
                'city' => $address->city ? $address->city->name : null,
            ];
        } else {
            return [
                'state_id' => null,
                'state' => null,
                'city_id' => null,
                'city' => null,
            ];
        }
    }
}

if (!function_exists('subscription_status')) {
    function subscription_status($user)
    {
        $subscription = $user->active_subscription;
        if ($subscription) {
            return new SubscriptionResource($subscription);
        } else {
            return response()->json([], 404);
        }
    }
}

if (!function_exists('get_subscription_status_string')) { //either active or expired
    function get_subscription_status_string($user)
    {
        $subscription = $user->active_subscription;

        if ($subscription) {
            $endsAtDate = Carbon::parse($subscription->ends_at);

            if ($endsAtDate->isPast()) {
                return 'expired';
            }

            return 'active';
        }

        return 'expired';
    }
}

if (!function_exists('are_they_friend')) { //either active or expired
    function are_they_friend($user1Id, $user2Id)
    {
        $friendship = DB::table('friendships')
            ->where(function ($query) use ($user1Id, $user2Id) {
                $query->where('user1_id', $user1Id)->where('user2_id', $user2Id);
            })
            ->orWhere(function ($query) use ($user1Id, $user2Id) {
                $query->where('user1_id', $user2Id)->where('user2_id', $user1Id);
            })
            ->first();

        return $friendship ? true : false;
    }
}

if (!function_exists('hasAppliedToAgencyJob')) { //either active or expired
    function hasAppliedToAgencyJob($creativeUserId, $loggedInAgencyId)
    {
        $hasApplied = Application::where('user_id', $creativeUserId)
            ->whereIn('job_id', function ($query) use ($loggedInAgencyId) {
                $query->select('id')->from(with(new Job())->getTable())->where('user_id', $loggedInAgencyId);
            })
            ->exists();

        return $hasApplied;
    }
}

if (!function_exists('get_similar_roles')) { //We will use this funtion where we need to check for all three agency related roles
    function get_similar_roles()
    {
        return ['agency', 'advisor', 'recruiter'];
    }
}

if (!function_exists('get_agency_logo')) { //We will use this funtion where we need to check for all three agency related roles
    function get_agency_logo($job, $user)
    {

        if($job->attachment_id != null ){
            return Attachment::find($job->attachment_id);

        }
        else{
            // dd($user->agency_logo);
            return $user->agency_logo;
        }
    }
}
