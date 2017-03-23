<?php

namespace Backend\Enums;

class EventEnum
{
    const TYPE_AIT_2016_Q1 = 1;
    const TYPE_AIT_2016_Q4 = 2;
    const TYPE_AIT_2017_Q2 = 3;

    const AIT_2016_Q4_SUBJECT   = 'asiatour_2016_q4';

    const AIT_Q4_START_DATE = '2016-08-01';

    const EVENT_NAME = [
        self::TYPE_AIT_2016_Q1 => [
            'orig'  => 'Asia Innovation Tour 2016 Q1',
            'short' => '2016 AIT Q1'
        ],
        self::TYPE_AIT_2016_Q4 => [
            'orig'  => 'Asia Innovation Tour 2016 Q4',
            'short' => '2016 AIT Q4'
        ],
        self::TYPE_AIT_2017_Q2 => [
            'orig'  => 'Asia Innovation Tour 2017 Q2',
            'short' => '2017 AIT Q2'
        ],
    ];

    const QUESTIONNAIRE_COLUMN_NAME = [
        self::TYPE_AIT_2016_Q1 => [
            'job_title'                           => 'Job title',
            'company_name'                        => 'Company name',
            'phone'                               => 'Phone',
            'trip_participation'                  => 'Trip participation',
            'flight_local_to_shenzhen_flight'     => 'Flight local to shenzhen flight',
            'flight_local_to_shenzhen_datetime'   => 'Flight local to shenzhen datetime',
            'flight_shenzhen_to_beijing_flight'   => 'Flight shenzhen to beijing flight',
            'flight_shenzhen_to_beijing_datetime' => 'Flight shenzhen to beijing datetime',
            'flight_beijing_to_taipei_flight'     => 'Flight beijing to taipei flight',
            'flight_beijing_to_taipei_datetime'   => 'Flight beijing to taipei datetime',
            'attend_to_april_dinner'              => 'Attend to april dinner',
            'bring_prototype'                     => 'Bring prototype',
            'other_member_to_join'                => 'Other member to join',
            'wechat_account'                      => 'Wechat account',
            'forward_material'                    => 'Forward material'
        ],
        self::TYPE_AIT_2016_Q4 => [
            'join_tour'                           => '',
            'job_title'                           => 'Job title',
            'company_name'                        => 'Company name',
            'phone'                               => 'Phone',
            'trip_participation'                  => 'Trip participation',
        ]
    ];

    const QUESTIONNAIRE_VIEWS = [
        self::TYPE_AIT_2016_Q1 => 'report.questionnaires.asia-innovation-tour-q1',
        self::TYPE_AIT_2016_Q4 => 'report.questionnaires.asia-innovation-tour-q4'
    ];
}
