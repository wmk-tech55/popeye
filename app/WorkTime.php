<?php

namespace MixCode;

use MixCode\Utils\UsingMultiLang;
use Illuminate\Database\Eloquent\Model;
use MixCode\Utils\UsingSerializeDate;
use MixCode\Utils\UsingUuid;

class WorkTime extends Model
{
    use UsingUuid, UsingSerializeDate, UsingMultiLang;

    protected $appends = ['day_name_by_lang'];

    protected $fillable = [
        'en_day_name',
        'ar_day_name',
        'day_order',
        'raw_order',
        'day_is_vacation',
        'open_time',
        'close_time',
        'service_id',
    ];

    protected $casts = [
        'day_is_vacation' => 'boolean',
        'open_time'       => 'datetime:H:i',
        'close_time'      => 'datetime:H:i',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class, 'service_id')->withoutGlobalScopes();
    }

    public static function multiLangFields(): array
    {
        return ['day_name'];
    }

    public function getDayNameByLangAttribute()
    {
        $field = $this->getLang() . '_day_name';

        return $this->{$field};
    }

    public static function defaultWorkTimes()
    {
        return [

            [
                'raw_order'       => 0,
                'day_order'       => 1,
                'en_day_name'     => 'Sunday',
                'ar_day_name'     => 'الأحد',
                'day_is_vacation' => false,
                'open_time'       => '00:00',
                'close_time'      => '00:00',
            ],
            [
                'raw_order'       => 1,
                'day_order'       => 2,
                'en_day_name'     => 'Monday',
                'ar_day_name'     => 'الأثنين',
                'day_is_vacation' => false,
                'open_time'       => '00:00',
                'close_time'      => '00:00',
            ],
            [
                'raw_order'       => 2,
                'day_order'       => 3,
                'en_day_name'     => 'Tuesday',
                'ar_day_name'     => 'الثلاثاء',
                'day_is_vacation' => false,
                'open_time'       => '00:00',
                'close_time'      => '00:00',
            ],
            [
                'raw_order'       => 3,
                'day_order'       => 4,
                'en_day_name'     => 'Wednesday',
                'ar_day_name'     => 'الأربعاء',
                'day_is_vacation' => false,
                'open_time'       => '00:00',
                'close_time'      => '00:00',
            ],
            [
                'raw_order'       => 4,
                'day_order'       => 5,
                'en_day_name'     => 'Thursday',
                'ar_day_name'     => 'الخميس',
                'day_is_vacation' => false,
                'open_time'       => '00:00',
                'close_time'      => '00:00',
            ],
            [
                'raw_order'       => 5,
                'day_order'       => 6,
                'en_day_name'     => 'Friday',
                'ar_day_name'     => 'الجمعة',
                'day_is_vacation' => false,
                'open_time'       => '00:00',
                'close_time'      => '00:00',
            ],
            [
                'raw_order'       => 6,
                'day_order'       => 7,
                'en_day_name'     => 'Saturday',
                'ar_day_name'     => 'السبت',
                'day_is_vacation' => false,
                'open_time'       => '00:00',
                'close_time'      => '00:00',
            ]
        ];
    }


     /**
     * attach user work hours
     *
     * @param array $data
     * @return $this
     */
    public static function attacheWorkingTimes($request)
    {
        $work_time_data = [];
  
        if (count($request->working_times) > 0  && count($request->working_times) == 7) {
           
          
             foreach ($request->working_times as $value) {

                $open_time  = $value['open_time']   == "00:00" ? "00:00" : $value['open_time'] . ":00" ;
                $close_time = $value['close_time']  == "00:00" ? "00:00" : $value['close_time'] . ":00" ;
     
                $work_time_data[] = [
                    "en_day_name"     => $value['en_day_name'],
                    "ar_day_name"     => $value['ar_day_name'],
                    "day_order"       => $value['day_order'],
                    "raw_order"       => $value['raw_order'],
                    "day_is_vacation" => $value['day_is_vacation'],
                    "open_time"       => $open_time,
                    "close_time"      => $close_time,
                ];
            }

             
            auth()->user()->work_times()->delete(); 
            auth()->user()->initWorkTimes($work_time_data); 
        }

    }

    
    public function initWorkTimes($work_time_data = null)
    {

        $workTimesData = static::defaultWorkTimes();

        if ($work_time_data != null) {

            $workTimesData = $work_time_data;
        }

         $this->work_times()->createMany($workTimesData);
    }


    public function isVacation()
    {
        return $this->day_is_vacation == true;
    }
}
