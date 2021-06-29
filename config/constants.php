<?php

/*
 * Constants
 * To access
 * Config::get('constants.roles')
 * Config::get('constants.roles.ADMIN_ROLE')
 * 
 */
return [
    'roles' => [
        'ADMIN_ROLE' => 'ADMIN_ROLE',
        'USER_ROLE' => 'USER_ROLE'
    ],
    
    'orders' => [
        'ORDER_CREATED' => 'ORDER_CREATED'
    ],
    
    'kits' => [
        'KIT_REGISTERED' => 'KIT_REGISTERED',
        'KIT_DISPATCHED' => 'KIT_DISPATCHED'
    ],
    
    'samples' => [
        'SAMPLE_RECEIVED' => 'SAMPLE_RECEIVED',
        'SAMPLE_REGISTERED' => 'SAMPLE_REGISTERED'
    ],
    
    'results' => [
        'RESULT_RECEIVED' => 'RESULT_RECEIVED'
    ],
    
    'result' => [
        'COBAS' => [
            'NEGATIVT' => 'NEGATIVT',
            'HPV16' => 'HPV16',
            'HPV18' => 'HPV18',
            'ÖVRIGT_HPV' => 'ÖVRIGT_HPV',
            'FAILED' => 'FAILED',
            'INVALID' => 'INVALID',
        ],
        
        'LUMINEX' => [
            'NEGATIVT' => 'NEGATIVT',
            'HPV16' => 'HPV16',
            'HPV18' => 'HPV18',
            'ÖVRIGT_HPV' => 'ÖVRIGT_HPV',
            'FAILED' => 'FAILED',
        ],
        
        'RTPCR' => [
            'NEGATIVT' => 'NEGATIVT',
            'HPV6' => 'HPV6',
            'HPV11' => 'HPV11',
            'HPV16' => 'HPV16',
            'HPV18' => 'HPV18',
            'HPV31' => 'HPV31',
            'HPV33' => 'HPV33',
            'HPV35' => 'HPV35',
            'HPV45' => 'HPV45',
            'HPV52' => 'HPV52',
            'HPV58' => 'HPV58',
            //'ÖVRIGT_HPV' => 'ÖVRIGT_HPV',
            'FAILED' => 'FAILED',
        ],
        
        'FINAL_REPORTING' => [
            'NEGATIVT' => 'NEGATIVT',
            'HPV16' => 'HPV16',
            'HPV18' => 'HPV18',
            'HPV45' => 'HPV45',
            'ÖVRIGT_HPV_LÅG' => 'ÖVRIGT_HPV_LÅG',
            'ÖVRIGT_HPV_MEDEL' => 'ÖVRIGT_HPV_MEDEL',
        ]
    ],
    
    'messages' => [
        'RESULT_MESSAGE' => [
            'NEGATIVT' => '<p>Inget HPV-virus påvisades i provet.</p> 
                           <p>HPV-negativa kvinnor har låg risk och vi kommer inte att kontakta dig igen. </p>
                           <p>Bästa Hälsningar och Stort Tack för att Du deltog.</p>
                           <p>Joakim Dillner</p>',
            'HPV16' => '<p>HPV16 påvisades i provet. Det innebär en viss risk för livmoderhalscancer, men endast för en liten del av de kvinnor som har det och endast efter en mycket lång tid, oftast flera decennier.</p>
                        <p>Vi kommer att meddela resultatet till kvinnokliniken i din region.</p> 
                        <p>Bästa Hälsningar och Stort Tack för att Du deltog.</p>
                        <p>Joakim Dillner</p>',
            'HPV18' => '<p>HPV18 påvisades i provet. Det innebär en viss risk för livmoderhalscancer, men endast för en liten del av de kvinnor som har det och endast efter en mycket lång tid, oftast flera decennier.</p>
                        <p>Vi kommer att meddela resultatet till kvinnokliniken i din region.</p> 
                        <p>Bästa Hälsningar och Stort Tack för att Du deltog.</p>
                        <p>Joakim Dillner</p>',
            'HPV45' => '<p>HPV45 påvisades i provet. Det innebär en viss risk för livmoderhalscancer, men endast för en liten del av de kvinnor som har det och endast efter en mycket lång tid, oftast flera decennier.</p>
                        <p>Vi kommer att meddela resultatet till kvinnokliniken i din region.</p> 
                        <p>Bästa Hälsningar och Stort Tack för att Du deltog.</p>
                        <p>Joakim Dillner</p>',
            'ÖVRIGT_HPV_LÅG' => '<p>HPV av övrig typ påvisades i provet. Övrigt HPV har lägre risk än de HPV-virus (16/18) som främst associerar med livmoderhalscancer.</p> 
                                <p>Blir du kallad till rutinmässig screening från din region är det alltid viktigt att du hörsammar kallelsen och testar dig.</p>
                                <p>Bästa Hälsningar och Stort Tack för att Du deltog.</p>
                                <p>Joakim Dillner</p>',
            'ÖVRIGT_HPV_MEDEL' => '<p>HPV av övrig typ påvisades i provet. Övrigt HPV har lägre risk än de HPV-virus (16/18) som främst associerar med livmoderhalscancer, men vi kommer att erbjuda dig ett förnyat prov i nästa års utskick.</p> 
                                   <p>Blir du kallad till rutinmässig screening från din region är det alltid viktigt att du hörsammar kallelsen och testar dig.</p>
                                   <p>Bästa Hälsningar och Stort Tack för att Du deltog.</p>
                                   <p>Joakim Dillner</p>',
        ],
        
        'STATUS_MESSAGE' => [
            'ORDER_CREATED' => 'ORDER_CREATED',
            'KIT_REGISTERED' => 'KIT_REGISTERED',
            'KIT_DISPATCHED' => 'KIT_DISPATCHED',
            'SAMPLE_RECEIVED' => 'SAMPLE_RECEIVED',
            'SAMPLE_REGISTERED' => 'SAMPLE_REGISTERED',
            'RESULT_RECEIVED' => 'RESULT_RECEIVED'
        ]
    ]
];