<?php

namespace App\Rules;

use App\Repositories\SettingRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class SettingValidatorRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $settingRepository = app(SettingRepository::class);

        foreach ($value as $key => $settingValue) {
            $setting = $settingRepository->findById($key);

            if (!$setting) {
                $fail("Setting with ID $key not found.");
                continue;
            }

            $rules = match ($setting->name) {
                'logo' => ['required', 'file', 'mimes:png,svg,jpeg,jpg'],
                'academic_year' => ['required', 'integer', 'gte:' . date('y')],

                // Uncomment and adjust version validation if needed
                // 'android_version' => [
                //     'required', 'string', 'regex:/^\d+\.\d+\.\d+$/',
                //     function ($attribute, $versionInput, $fail) use ($setting) {
                //         $currentVersion = $setting->value;
                //         if (version_compare($versionInput, $currentVersion, '<')) {
                //             $fail("The version must be newer than: $currentVersion.");
                //         }
                //     },
                // ],
                // 'ios_version' => [
                //     'required', 'string', 'regex:/^\d+\.\d+\.\d+$/',
                //     function ($attribute, $versionInput, $fail) use ($setting) {
                //         $currentVersion = $setting->value;
                //         if (version_compare($versionInput, $currentVersion, '<')) {
                //             $fail("The version must be newer than: $currentVersion.");
                //         }
                //     },
                // ],

                default => [],
            };

            if (!empty($rules)) {
                if($settingValue['name'] != 'logo'){
                    $validator = Validator::make(
                        ['value' => $settingValue['value']],
                        ['value' => $rules]
                    );

                    if ($validator->fails()) {
                        foreach ($validator->errors()->all() as $error) {
                            $fail($setting->name . ' : ' . $error);
                        }
                    }
                }
            }
        }
    }
}
